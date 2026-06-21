<?php
declare(strict_types=1);

class NotificationModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function forUser(int $userId): array {
        $st = $this->db->prepare(
            'SELECT * FROM notifications WHERE user_id=? OR user_id IS NULL ORDER BY created_at DESC LIMIT 50'
        );
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO notifications (user_id,title,message,type,link) VALUES (?,?,?,?,?)'
        );
        $st->execute([
            $d['user_id'] ?? null,
            $d['title'],
            $d['message'],
            $d['type'] ?? 'info',
            $d['link'] ?? null,
        ]);
        $id = (int)$this->db->lastInsertId();
        $st = $this->db->prepare('SELECT * FROM notifications WHERE id=?');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function markRead(int $id, int $userId): bool {
        $st = $this->db->prepare(
            'UPDATE notifications SET is_read=1 WHERE id=? AND (user_id=? OR user_id IS NULL)'
        );
        return $st->execute([$id, $userId]);
    }

    public function markAllRead(int $userId): bool {
        $st = $this->db->prepare(
            'UPDATE notifications SET is_read=1 WHERE user_id=? OR user_id IS NULL'
        );
        return $st->execute([$userId]);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM notifications WHERE id=?');
        return $st->execute([$id]);
    }

    public function unreadCount(int $userId): int {
        $st = $this->db->prepare(
            'SELECT COUNT(*) FROM notifications WHERE (user_id=? OR user_id IS NULL) AND is_read=0'
        );
        $st->execute([$userId]);
        return (int)$st->fetchColumn();
    }

    public function all(int $limit = 100): array {
        $st = $this->db->prepare(
            'SELECT n.*,u.name as user_name FROM notifications n LEFT JOIN users u ON n.user_id=u.id ORDER BY n.created_at DESC LIMIT ?'
        );
        $st->execute([$limit]);
        return $st->fetchAll();
    }
}
