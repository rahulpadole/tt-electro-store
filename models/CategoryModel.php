<?php
declare(strict_types=1);

class CategoryModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(): array {
        return $this->db->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM categories WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array {
        $st = $this->db->prepare('SELECT * FROM categories WHERE slug=?');
        $st->execute([$slug]);
        return $st->fetch() ?: null;
    }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO categories (name,slug,description,image,icon) VALUES (?,?,?,?,?)'
        );
        $st->execute([
            $d['name'],
            $d['slug'] ?? slug($d['name']),
            $d['description'] ?? null,
            $d['image'] ?? null,
            $d['icon'] ?? null,
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['name','slug','description','image','icon'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "{$f}=?"; $vals[] = $d[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE categories SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM categories WHERE id=?');
        return $st->execute([$id]);
    }
}
