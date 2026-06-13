<?php
declare(strict_types=1);

class DiyKitModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(): array {
        $rows = $this->db->query('SELECT * FROM diy_kits ORDER BY created_at DESC')->fetchAll();
        return array_map('normalizeDiyKit', $rows);
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM diy_kits WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ? normalizeDiyKit($row) : null;
    }

    public function create(array $d): array {
        $imgs  = is_array($d['images'] ?? null) ? json_encode($d['images']) : null;
        $comps = is_array($d['components'] ?? null) ? json_encode($d['components']) : null;
        $st = $this->db->prepare(
            'INSERT INTO diy_kits (name,description,price,thumbnail,images,components,pdf_url,video_url,difficulty,stock)
             VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
        $st->execute([
            $d['name'],
            $d['description'] ?? null,
            (float)$d['price'],
            $d['thumbnail'] ?? null,
            $imgs,
            $comps,
            $d['pdf_url'] ?? null,
            $d['video_url'] ?? null,
            $d['difficulty'] ?? null,
            (int)($d['stock'] ?? 0),
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['name','description','price','thumbnail','pdf_url','video_url','difficulty','stock'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "{$f}=?"; $vals[] = $d[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE diy_kits SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM diy_kits WHERE id=?');
        return $st->execute([$id]);
    }
}
