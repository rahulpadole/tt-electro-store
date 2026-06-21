<?php
declare(strict_types=1);

class BannerModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function active(): array {
        return $this->db->query('SELECT * FROM banners WHERE is_active=1 ORDER BY position ASC')->fetchAll();
    }

    public function all(): array {
        return $this->db->query('SELECT * FROM banners ORDER BY position ASC')->fetchAll();
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM banners WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO banners (title,subtitle,image,link,badge,is_active,position) VALUES (?,?,?,?,?,?,?)'
        );
        $st->execute([
            $d['title'],
            $d['subtitle'] ?? null,
            $d['image'],
            $d['link'] ?? null,
            $d['badge'] ?? null,
            (int)(bool)($d['is_active'] ?? true),
            (int)($d['position'] ?? 0),
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['title','subtitle','image','link','badge','is_active','position'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "{$f}=?"; $vals[] = $d[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE banners SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM banners WHERE id=?');
        return $st->execute([$id]);
    }
}
