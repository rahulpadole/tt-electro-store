<?php
declare(strict_types=1);

class AdminNotificationModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function recent(int $limit = 30): array {
        $limit = max(1, min(100, $limit));
        $st = $this->db->prepare("SELECT * FROM admin_notifications ORDER BY created_at DESC LIMIT {$limit}");
        $st->execute();
        return $st->fetchAll();
    }

    public function unreadCount(): int {
        $st = $this->db->query('SELECT COUNT(*) FROM admin_notifications WHERE is_read = 0');
        return (int)$st->fetchColumn();
    }

    public function markRead(int $id): bool {
        $st = $this->db->prepare('UPDATE admin_notifications SET is_read = 1 WHERE id = ?');
        return $st->execute([$id]);
    }

    public function markAllRead(): bool {
        $st = $this->db->prepare('UPDATE admin_notifications SET is_read = 1 WHERE is_read = 0');
        return $st->execute();
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM admin_notifications WHERE id = ?');
        return $st->execute([$id]);
    }

    /**
     * Derive an admin-panel URL for a notification based on its type/data.
     */
    public static function linkFor(array $n): string {
        $data = json_decode((string)($n['data'] ?? '{}'), true) ?: [];
        return match ($n['type'] ?? '') {
            'new_order', 'shipment_failed' => '/admin/orders' . (isset($data['order_id']) ? '?order_id=' . (int)$data['order_id'] : ''),
            'new_return' => '/admin/returns' . (isset($data['return_id']) ? '?return_id=' . (int)$data['return_id'] : ''),
            default => '/admin/notifications',
        };
    }
}
