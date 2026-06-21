<?php
declare(strict_types=1);

class UserModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function findById(int $id): ?array {
        $st = $this->db->prepare(
            'SELECT id,name,email,phone,phone_verified,avatar,google_avatar,
                    google_id,role,loyalty_points,is_active,created_at
             FROM users WHERE id=?'
        );
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array {
        $st = $this->db->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
        $st->execute([strtolower(trim($email))]);
        return $st->fetch() ?: null;
    }

    public function findByGoogleId(string $googleId): ?array {
        $st = $this->db->prepare('SELECT * FROM users WHERE google_id=? LIMIT 1');
        $st->execute([$googleId]);
        return $st->fetch() ?: null;
    }

    public function create(array $data): array {
        $st = $this->db->prepare(
            'INSERT INTO users (name,email,password,phone,phone_verified,google_id,google_avatar,role)
             VALUES (?,?,?,?,?,?,?,?)'
        );
        $st->execute([
            $data['name'],
            strtolower(trim($data['email'])),
            $data['password'],
            $data['phone']           ?? null,
            $data['phone_verified']  ?? 0,
            $data['google_id']       ?? null,
            $data['google_avatar']   ?? null,
            $data['role']            ?? 'user',
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $data): ?array {
        $allowed = ['name','phone','avatar'];
        $fields  = []; $vals = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $data)) { $fields[] = "{$f}=?"; $vals[] = $data[$f]; }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $this->db->prepare(
            'UPDATE users SET ' . implode(',', $fields) . ',updated_at=NOW() WHERE id=?'
        )->execute($vals);
        return $this->findById($id);
    }

    public function updatePassword(int $id, string $hashedPassword): void {
        $this->db->prepare(
            'UPDATE users SET password=?,updated_at=NOW() WHERE id=?'
        )->execute([$hashedPassword, $id]);
    }

    public function verifyPhone(int $id, string $phone): void {
        $this->db->prepare(
            'UPDATE users SET phone=?,phone_verified=1,updated_at=NOW() WHERE id=?'
        )->execute([$phone, $id]);
    }

    public function all(int $limit = 100, int $offset = 0): array {
        $st = $this->db->prepare(
            'SELECT id,name,email,phone,phone_verified,role,loyalty_points,is_active,created_at
             FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?'
        );
        $st->execute([$limit, $offset]);
        return $st->fetchAll();
    }

    public function count(): int {
        return (int)$this->db->query('SELECT COUNT(*) FROM users WHERE role != "guest"')->fetchColumn();
    }

    public function hasPassword(int $id): bool {
        $st = $this->db->prepare('SELECT password FROM users WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        return !empty($row['password']);
    }
}
