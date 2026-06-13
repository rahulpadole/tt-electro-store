<?php
declare(strict_types=1);

class WishlistModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function getForUser(int $userId): array {
        $st = $this->db->prepare(
            'SELECT w.id,w.product_id,w.added_at,
                    p.name,p.price,p.original_price,p.flash_sale_price,p.is_flash_sale,
                    p.thumbnail,p.slug,p.stock,p.discount
             FROM wishlist w JOIN products p ON w.product_id=p.id
             WHERE w.user_id=? ORDER BY w.added_at DESC'
        );
        $st->execute([$userId]);
        $rows = $st->fetchAll();
        foreach ($rows as &$r) {
            $r['is_flash_sale'] = (bool)(int)$r['is_flash_sale'];
        }
        return $rows;
    }

    public function add(int $userId, int $productId): array {
        $st = $this->db->prepare('SELECT id FROM wishlist WHERE user_id=? AND product_id=?');
        $st->execute([$userId, $productId]);
        if ($st->fetch()) return ['exists' => true];
        $st = $this->db->prepare('INSERT INTO wishlist (user_id,product_id) VALUES (?,?)');
        $st->execute([$userId, $productId]);
        $id = (int)$this->db->lastInsertId();
        $st = $this->db->prepare('SELECT * FROM wishlist WHERE id=?');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function remove(int $userId, int $productId): bool {
        $st = $this->db->prepare('DELETE FROM wishlist WHERE user_id=? AND product_id=?');
        return $st->execute([$userId, $productId]);
    }

    public function count(int $userId): int {
        $st = $this->db->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id=?');
        $st->execute([$userId]);
        return (int)$st->fetchColumn();
    }

    public function isWishlisted(int $userId, int $productId): bool {
        $st = $this->db->prepare('SELECT 1 FROM wishlist WHERE user_id=? AND product_id=?');
        $st->execute([$userId, $productId]);
        return (bool)$st->fetchColumn();
    }
}
