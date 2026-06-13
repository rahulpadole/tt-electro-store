<?php
declare(strict_types=1);

class NewsletterModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function subscribe(string $email): array {
        $st = $this->db->prepare('SELECT id FROM newsletter WHERE email=?');
        $st->execute([$email]);
        if ($st->fetch()) return ['exists' => true, 'message' => 'Already subscribed.'];
        $st = $this->db->prepare('INSERT INTO newsletter (email) VALUES (?)');
        $st->execute([$email]);
        $id = (int)$this->db->lastInsertId();
        $st = $this->db->prepare('SELECT * FROM newsletter WHERE id=?');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function unsubscribe(int $id): bool {
        $st = $this->db->prepare('DELETE FROM newsletter WHERE id=?');
        return $st->execute([$id]);
    }

    public function all(): array {
        return $this->db->query('SELECT * FROM newsletter ORDER BY subscribed_at DESC')->fetchAll();
    }

    public function count(): int {
        return (int)$this->db->query('SELECT COUNT(*) FROM newsletter')->fetchColumn();
    }
}
