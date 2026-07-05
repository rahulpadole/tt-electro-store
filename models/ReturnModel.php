<?php
declare(strict_types=1);

class ReturnModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO returns (order_id, user_id, reason, description, status, created_at, updated_at)
             VALUES (?, ?, ?, ?, \'pending\', NOW(), NOW())'
        );
        $st->execute([
            (int)$d['order_id'],
            (int)$d['user_id'],
            $d['reason'],
            $d['description'] ?? null,
        ]);
        $id = (int)$this->db->lastInsertId();

        if (!empty($d['images']) && is_array($d['images'])) {
            foreach ($d['images'] as $url) {
                if (!empty($url)) {
                    $this->db->prepare(
                        'INSERT INTO return_images (return_id, image_url) VALUES (?, ?)'
                    )->execute([$id, $url]);
                }
            }
        }

        return $this->findById($id);
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare(
            'SELECT r.*, o.order_number, o.total as order_total,
                    u.name as user_name, u.email as user_email
             FROM returns r
             JOIN orders o ON r.order_id = o.id
             JOIN users u  ON r.user_id  = u.id
             WHERE r.id = ?'
        );
        $st->execute([$id]);
        $row = $st->fetch();
        if (!$row) return null;
        $row['images'] = $this->getImages($id);
        return $row;
    }

    public function getImages(int $returnId): array {
        $st = $this->db->prepare('SELECT image_url FROM return_images WHERE return_id = ?');
        $st->execute([$returnId]);
        return array_column($st->fetchAll(), 'image_url');
    }

    public function getForOrder(int $orderId): ?array {
        $st = $this->db->prepare(
            'SELECT * FROM returns WHERE order_id = ? ORDER BY created_at DESC LIMIT 1'
        );
        $st->execute([$orderId]);
        $row = $st->fetch();
        if (!$row) return null;
        $row['images'] = $this->getImages((int)$row['id']);
        return $row;
    }

    public function getForUser(int $userId): array {
        $st = $this->db->prepare(
            'SELECT r.*, o.order_number FROM returns r
             JOIN orders o ON r.order_id = o.id
             WHERE r.user_id = ? ORDER BY r.created_at DESC'
        );
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public function all(int $limit = 50, int $offset = 0): array {
        $st = $this->db->prepare(
            'SELECT r.*, o.order_number, o.total as order_total,
                    u.name as user_name, u.email as user_email
             FROM returns r
             JOIN orders o ON r.order_id = o.id
             JOIN users u  ON r.user_id  = u.id
             ORDER BY r.created_at DESC LIMIT ? OFFSET ?'
        );
        $st->execute([$limit, $offset]);
        return $st->fetchAll();
    }

    public function count(): int {
        return (int)$this->db->query('SELECT COUNT(*) FROM returns')->fetchColumn();
    }

    public function countByStatus(string $status): int {
        $st = $this->db->prepare('SELECT COUNT(*) FROM returns WHERE status = ?');
        $st->execute([$status]);
        return (int)$st->fetchColumn();
    }

    public function updateStatus(int $id, string $status, ?string $adminNotes = null, ?float $refundAmount = null): ?array {
        $fields = ['status = ?', 'updated_at = NOW()'];
        $params = [$status];

        if ($adminNotes !== null) {
            $fields[] = 'admin_notes = ?';
            $params[]  = $adminNotes;
        }
        if ($refundAmount !== null) {
            $fields[] = 'refund_amount = ?';
            $params[]  = $refundAmount;
        }

        $params[] = $id;
        $this->db->prepare('UPDATE returns SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($params);
        return $this->findById($id);
    }
}
