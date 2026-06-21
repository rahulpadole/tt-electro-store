<?php
declare(strict_types=1);

class ReviewModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function forProduct(int $productId): array {
        $st = $this->db->prepare(
            'SELECT r.*,u.name as user_name,u.avatar as user_avatar
             FROM reviews r JOIN users u ON r.user_id=u.id
             WHERE r.product_id=? ORDER BY r.created_at DESC'
        );
        $st->execute([$productId]);
        return $st->fetchAll();
    }

    public function create(int $productId, int $userId, array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO reviews (product_id,user_id,rating,title,body) VALUES (?,?,?,?,?)'
        );
        $st->execute([$productId, $userId, (int)$d['rating'], $d['title'] ?? null, $d['body'] ?? null]);
        $id = (int)$this->db->lastInsertId();
        $st = $this->db->prepare('SELECT * FROM reviews WHERE id=?');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM reviews WHERE id=?');
        return $st->execute([$id]);
    }

    public function all(int $limit = 50, int $offset = 0): array {
        $st = $this->db->prepare(
            'SELECT r.*,u.name as user_name,p.name as product_name
             FROM reviews r JOIN users u ON r.user_id=u.id JOIN products p ON r.product_id=p.id
             ORDER BY r.created_at DESC LIMIT ? OFFSET ?'
        );
        $st->execute([$limit, $offset]);
        return $st->fetchAll();
    }

    public function avgRating(int $productId): float {
        $st = $this->db->prepare('SELECT COALESCE(AVG(rating),0) FROM reviews WHERE product_id=?');
        $st->execute([$productId]);
        return round((float)$st->fetchColumn(), 1);
    }
}
