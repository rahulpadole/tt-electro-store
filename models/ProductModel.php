<?php
declare(strict_types=1);

class ProductModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    private function base(): string {
        return "SELECT p.*,c.name as category_name,c.slug as category_slug,b.name as brand_name
                FROM products p
                LEFT JOIN categories c ON p.category_id=c.id
                LEFT JOIN brands b ON p.brand_id=b.id";
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare($this->base() . ' WHERE p.id=? AND p.is_active=1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ? normalizeProduct($row) : null;
    }

    public function findBySlug(string $slug): ?array {
        $st = $this->db->prepare($this->base() . ' WHERE p.slug=? AND p.is_active=1');
        $st->execute([$slug]);
        $row = $st->fetch();
        return $row ? normalizeProduct($row) : null;
    }

    public function all(array $filters = [], int $page = 1, int $perPage = ITEMS_PER_PAGE): array {
        $where = ['p.is_active=1']; $params = [];

        if (!empty($filters['q'])) {
            $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['category_id'])) {
            $where[] = "p.category_id=?"; $params[] = (int)$filters['category_id'];
        }
        if (!empty($filters['brand_id'])) {
            $where[] = "p.brand_id=?"; $params[] = (int)$filters['brand_id'];
        }
        if (!empty($filters['min_price'])) {
            $where[] = "p.price>=?"; $params[] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "p.price<=?"; $params[] = (float)$filters['max_price'];
        }
        if (!empty($filters['featured'])) { $where[] = "p.is_featured=1"; }
        if (!empty($filters['trending'])) { $where[] = "p.is_trending=1"; }

        $whereStr = implode(' AND ', $where);
        $sortMap = [
            'price_asc'  => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'newest'     => 'p.created_at DESC',
            'popular'    => 'p.is_best_seller DESC',
        ];
        $sort = $sortMap[$filters['sort'] ?? ''] ?? 'p.created_at DESC';

        $countSt = $this->db->prepare(
            "SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id=c.id LEFT JOIN brands b ON p.brand_id=b.id WHERE {$whereStr}"
        );
        $countSt->execute($params);
        $total = (int)$countSt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $st = $this->db->prepare(
            $this->base() . " WHERE {$whereStr} ORDER BY {$sort} LIMIT {$perPage} OFFSET {$offset}"
        );
        $st->execute($params);
        $items = array_map('normalizeProduct', $st->fetchAll());

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    public function featured(int $limit = 8): array {
        $st = $this->db->prepare($this->base() . ' WHERE p.is_featured=1 AND p.is_active=1 ORDER BY p.created_at DESC LIMIT ?');
        $st->execute([$limit]);
        return array_map('normalizeProduct', $st->fetchAll());
    }

    public function trending(int $limit = 8): array {
        $st = $this->db->prepare($this->base() . ' WHERE p.is_trending=1 AND p.is_active=1 ORDER BY p.created_at DESC LIMIT ?');
        $st->execute([$limit]);
        return array_map('normalizeProduct', $st->fetchAll());
    }

    public function bestSellers(int $limit = 8): array {
        $st = $this->db->prepare($this->base() . ' WHERE p.is_best_seller=1 AND p.is_active=1 ORDER BY p.created_at DESC LIMIT ?');
        $st->execute([$limit]);
        return array_map('normalizeProduct', $st->fetchAll());
    }

    public function flashSale(int $limit = 8): array {
        $st = $this->db->prepare($this->base() . ' WHERE p.is_flash_sale=1 AND p.is_active=1 AND (p.flash_sale_ends IS NULL OR p.flash_sale_ends > NOW()) ORDER BY p.created_at DESC LIMIT ?');
        $st->execute([$limit]);
        return array_map('normalizeProduct', $st->fetchAll());
    }

    public function create(array $d): array {
        $st = $this->db->prepare(
            "INSERT INTO products (name,slug,description,price,original_price,discount,stock,thumbnail,images,tags,category_id,brand_id,is_featured,is_trending,is_best_seller,specifications,is_active)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)"
        );
        $st->execute([
            $d['name'],
            $d['slug'] ?? slug($d['name']),
            $d['description'] ?? null,
            (float)$d['price'],
            $d['original_price'] ?? null,
            $d['discount'] ?? null,
            (int)($d['stock'] ?? 0),
            $d['thumbnail'] ?? null,
            is_array($d['images'] ?? null) ? json_encode($d['images']) : ($d['images'] ?? null),
            is_array($d['tags'] ?? null) ? json_encode($d['tags']) : ($d['tags'] ?? null),
            (int)$d['category_id'],
            $d['brand_id'] ?? null,
            (int)(bool)($d['is_featured'] ?? false),
            (int)(bool)($d['is_trending'] ?? false),
            (int)(bool)($d['is_best_seller'] ?? false),
            $d['specifications'] ?? null,
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['name','description','price','original_price','discount','stock','thumbnail','images','tags',
                    'category_id','brand_id','is_featured','is_trending','is_best_seller',
                    'is_flash_sale','flash_sale_price','flash_sale_ends','specifications','is_active'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (!array_key_exists($f, $d)) continue;
            $fields[] = "{$f}=?";
            if (in_array($f, ['images','tags']) && is_array($d[$f])) {
                $vals[] = json_encode($d[$f]);
            } else {
                $vals[] = $d[$f];
            }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare("UPDATE products SET " . implode(',', $fields) . ",updated_at=NOW() WHERE id=?");
        $st->execute($vals);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('UPDATE products SET is_active=0 WHERE id=?');
        return $st->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query('SELECT COUNT(*) FROM products WHERE is_active=1')->fetchColumn();
    }

    public function lowStock(int $threshold = 10): array {
        $st = $this->db->prepare('SELECT id,name,stock,thumbnail FROM products WHERE is_active=1 AND stock<=? ORDER BY stock ASC LIMIT 20');
        $st->execute([$threshold]);
        return $st->fetchAll();
    }
}
