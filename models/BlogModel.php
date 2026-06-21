<?php
declare(strict_types=1);

class BlogModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(int $page = 1, int $perPage = ITEMS_PER_PAGE, string $category = ''): array {
        $where = ['1=1']; $params = [];
        if ($category) { $where[] = "category=?"; $params[] = $category; }
        $whereStr = implode(' AND ', $where);

        $st = $this->db->prepare("SELECT COUNT(*) FROM blogs WHERE {$whereStr}");
        $st->execute($params);
        $total = (int)$st->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $st = $this->db->prepare("SELECT * FROM blogs WHERE {$whereStr} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}");
        $st->execute($params);
        $items = array_map('normalizeBlog', $st->fetchAll());

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    public function findBySlug(string $slug): ?array {
        $st = $this->db->prepare('SELECT * FROM blogs WHERE slug=?');
        $st->execute([$slug]);
        $row = $st->fetch();
        return $row ? normalizeBlog($row) : null;
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM blogs WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ? normalizeBlog($row) : null;
    }

    public function create(array $d): array {
        $tagsJson = is_array($d['tags'] ?? null) ? json_encode($d['tags']) : ($d['tags'] ?? null);
        $st = $this->db->prepare(
            'INSERT INTO blogs (title,slug,excerpt,content,thumbnail,author_name,category,tags,reading_time)
             VALUES (?,?,?,?,?,?,?,?,?)'
        );
        $st->execute([
            $d['title'],
            $d['slug'] ?? slug($d['title']),
            $d['excerpt'] ?? null,
            $d['content'],
            $d['thumbnail'] ?? null,
            $d['author_name'] ?? 'TT Electro',
            $d['category'] ?? null,
            $tagsJson,
            $d['reading_time'] ?? null,
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['title','slug','excerpt','content','thumbnail','author_name','category','reading_time'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "{$f}=?"; $vals[] = $d[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE blogs SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM blogs WHERE id=?');
        return $st->execute([$id]);
    }

    public function incrementViews(int $id): void {
        $this->db->prepare('UPDATE blogs SET view_count=view_count+1 WHERE id=?')->execute([$id]);
    }
}
