<?php
declare(strict_types=1);

class ContactModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO contact_messages (name,email,phone,subject,message) VALUES (?,?,?,?,?)'
        );
        $st->execute([
            $d['name'],
            $d['email'],
            $d['phone'] ?? null,
            $d['subject'] ?? null,
            $d['message'],
        ]);
        $id = (int)$this->db->lastInsertId();
        $st = $this->db->prepare('SELECT * FROM contact_messages WHERE id=?');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function all(int $limit = 100): array {
        $st = $this->db->prepare('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ?');
        $st->execute([$limit]);
        return $st->fetchAll();
    }
}
