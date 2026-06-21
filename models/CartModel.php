<?php
declare(strict_types=1);

class CartModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function getItems(int $userId): array {
        $st = $this->db->prepare(
            'SELECT ci.id,ci.product_id,ci.quantity,ci.coupon_code,
                    p.name,p.price,p.flash_sale_price,p.is_flash_sale,p.thumbnail,p.stock
             FROM cart_items ci
             JOIN products p ON ci.product_id=p.id
             WHERE ci.user_id=? ORDER BY ci.created_at DESC'
        );
        $st->execute([$userId]);
        $rows = $st->fetchAll();
        foreach ($rows as &$r) {
            $r['is_flash_sale'] = (bool)(int)$r['is_flash_sale'];
        }
        return $rows;
    }

    public function addItem(int $userId, int $productId, int $quantity = 1): array {
        $st = $this->db->prepare('SELECT id,quantity FROM cart_items WHERE user_id=? AND product_id=?');
        $st->execute([$userId, $productId]);
        $existing = $st->fetch();
        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $st = $this->db->prepare('UPDATE cart_items SET quantity=? WHERE id=?');
            $st->execute([$newQty, $existing['id']]);
            $id = $existing['id'];
        } else {
            $st = $this->db->prepare('INSERT INTO cart_items (user_id,product_id,quantity) VALUES (?,?,?)');
            $st->execute([$userId, $productId, $quantity]);
            $id = (int)$this->db->lastInsertId();
        }
        $st = $this->db->prepare('SELECT * FROM cart_items WHERE id=?');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function updateItem(int $itemId, int $userId, int $quantity): ?array {
        $st = $this->db->prepare('UPDATE cart_items SET quantity=? WHERE id=? AND user_id=?');
        $st->execute([$quantity, $itemId, $userId]);
        $st = $this->db->prepare('SELECT * FROM cart_items WHERE id=?');
        $st->execute([$itemId]);
        return $st->fetch() ?: null;
    }

    public function removeItem(int $itemId, int $userId): bool {
        $st = $this->db->prepare('DELETE FROM cart_items WHERE id=? AND user_id=?');
        return $st->execute([$itemId, $userId]);
    }

    public function clearCart(int $userId): bool {
        $st = $this->db->prepare('DELETE FROM cart_items WHERE user_id=?');
        return $st->execute([$userId]);
    }

    public function count(int $userId): int {
        $st = $this->db->prepare('SELECT COALESCE(SUM(quantity),0) FROM cart_items WHERE user_id=?');
        $st->execute([$userId]);
        return (int)$st->fetchColumn();
    }
}
