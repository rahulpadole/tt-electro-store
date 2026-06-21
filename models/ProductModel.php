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

    public function create(array $d): array
    {
        try {
    
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $slug = $d['slug'] ?? slug($d['name']);

            $check = $this->db->prepare(
                "SELECT COUNT(*) FROM products WHERE slug=?"
            );
            $check->execute([$slug]);
            
            if ($check->fetchColumn() > 0) {
                $slug .= '-' . time();
            }
    
            $sql = "
            INSERT INTO products (
                name,
                slug,
                description,
                price,
                original_price,
                discount,
                stock,
                thumbnail,
                images,
                tags,
                category_id,
                brand_id,
                is_featured,
                is_trending,
                is_best_seller,
                is_new_arrival,
                is_offer,
                specifications,
                is_active
            )
            VALUES (
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1
            )";
    
            $st = $this->db->prepare($sql);
    
            $st->execute([
                trim($d['name']),
                $slug,
                $d['description'] ?? null,
                (float)$d['price'],
                !empty($d['original_price']) ? (float)$d['original_price'] : null,
                !empty($d['discount']) ? (float)$d['discount'] : null,
                (int)($d['stock'] ?? 0),
                $d['thumbnail'] ?? null,
                json_encode($d['images'] ?? []),
                json_encode($d['tags'] ?? []),
                (int)$d['category_id'],
                ($d['brand_id'] === '' || $d['brand_id'] === null)
                    ? null
                    : (int)$d['brand_id'],
                !empty($d['is_featured']) ? 1 : 0,
                !empty($d['is_trending']) ? 1 : 0,
                !empty($d['is_best_seller']) ? 1 : 0,
                !empty($d['is_new_arrival']) ? 1 : 0,
                !empty($d['is_offer']) ? 1 : 0,
                $d['specifications'] ?? null
            ]);
    
            return $this->findById((int)$this->db->lastInsertId());
    
        } catch (Throwable $e) {
    
            die(json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], JSON_PRETTY_PRINT));
        }
    }

    public function update(int $id, array $d): ?array
    {
        try {
    
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $allowed = [
                'name',
                'description',
                'price',
                'original_price',
                'discount',
                'stock',
                'thumbnail',
                'images',
                'tags',
                'category_id',
                'brand_id',
                'is_featured',
                'is_trending',
                'is_best_seller',
                'is_new_arrival',
                'is_offer',
                'is_flash_sale',
                'flash_sale_price',
                'flash_sale_ends',
                'specifications',
                'is_active'
            ];
    
            $fields = [];
            $vals = [];
    
            foreach ($allowed as $f) {
    
                if (!array_key_exists($f, $d)) {
                    continue;
                }
    
                $fields[] = "{$f}=?";
    
                if (in_array($f, ['images', 'tags']) && is_array($d[$f])) {
    
                    $vals[] = json_encode($d[$f], JSON_UNESCAPED_SLASHES);
    
                } elseif ($f === 'brand_id') {
    
                    $vals[] = ($d[$f] === '' || $d[$f] === null)
                        ? null
                        : (int)$d[$f];
    
                } elseif ($f === 'category_id') {
    
                    $vals[] = (int)$d[$f];
    
                } elseif (in_array($f, [
                    'is_featured',
                    'is_trending',
                    'is_best_seller',
                    'is_new_arrival',
                    'is_offer',
                    'is_flash_sale',
                    'is_active'
                ])) {
    
                    $vals[] = (int)(bool)$d[$f];
    
                } elseif (in_array($f, [
                    'original_price',
                    'discount',
                    'flash_sale_price'
                ])) {
    
                    $vals[] = ($d[$f] === '' || $d[$f] === null)
                        ? null
                        : (float)$d[$f];
    
                } elseif ($f === 'flash_sale_ends') {
    
                    $vals[] = empty($d[$f]) ? null : $d[$f];
    
                } else {
    
                    $vals[] = $d[$f];
                }
            }
    
            if (empty($fields)) {
                return $this->findById($id);
            }
    
            $vals[] = $id;
    
            $sql = "UPDATE products SET "
                . implode(',', $fields)
                . ", updated_at=NOW() WHERE id=?";
    
            $st = $this->db->prepare($sql);
            $st->execute($vals);
    
            return $this->findById($id);
    
        } catch (Throwable $e) {
    
            http_response_code(500);
    
            header('Content-Type: application/json');
    
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ], JSON_PRETTY_PRINT);
    
            exit;
        }
    }

    public function delete(int $id): bool {

        $st = $this->db->prepare("
            UPDATE products
            SET
                is_active = 0,
                slug = CONCAT(slug,'-deleted-',id)
            WHERE id=?
        ");
    
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
