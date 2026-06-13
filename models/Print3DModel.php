<?php
declare(strict_types=1);

class Print3DModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function forUser(int $userId): array {
        $st = $this->db->prepare('SELECT * FROM print3d_requests WHERE user_id=? ORDER BY created_at DESC');
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare(
            'SELECT r.*,u.name as user_name,u.email as user_email
             FROM print3d_requests r JOIN users u ON r.user_id=u.id WHERE r.id=?'
        );
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function all(int $limit = 50, int $offset = 0): array {
        $st = $this->db->prepare(
            'SELECT r.*,u.name as user_name,u.email as user_email
             FROM print3d_requests r JOIN users u ON r.user_id=u.id
             ORDER BY r.created_at DESC LIMIT ? OFFSET ?'
        );
        $st->execute([$limit, $offset]);
        return $st->fetchAll();
    }

    public function create(int $userId, array $d): array {
        $st = $this->db->prepare(
            "INSERT INTO print3d_requests (user_id,file_url,image_url,material,quantity,description,status)
             VALUES (?,?,?,?,?,?,'pending')"
        );
        $st->execute([
            $userId,
            $d['file_url'] ?? null,
            $d['image_url'] ?? null,
            $d['material'],
            (int)$d['quantity'],
            $d['description'] ?? null,
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['status','estimated_price','admin_note','file_url','image_url'];
        $fields = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "{$f}=?"; $vals[] = $d[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE print3d_requests SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }
}
