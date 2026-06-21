<?php
declare(strict_types=1);

class OfferModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(): array {
        return $this->db->query('SELECT * FROM offers ORDER BY created_at DESC')->fetchAll();
    }

    public function active(): array {
        return $this->db->query(
            "SELECT * FROM offers WHERE (ends_at IS NULL OR ends_at > NOW()) ORDER BY created_at DESC"
        )->fetchAll();
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM offers WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO offers (title,description,type,discount,ends_at,image,badge) VALUES (?,?,?,?,?,?,?)'
        );
        $st->execute([
            $d['title'],
            $d['description'] ?? null,
            $d['type'] ?? 'flash',
            $d['discount'] ?? null,
            $d['ends_at'] ?? null,
            $d['image'] ?? null,
            $d['badge'] ?? null,
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['title','description','type','discount','ends_at','image','badge'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "{$f}=?"; $vals[] = $d[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE offers SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM offers WHERE id=?');
        return $st->execute([$id]);
    }
}
