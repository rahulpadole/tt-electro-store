<?php
declare(strict_types=1);

class CouponModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(): array {
        return $this->db->query('SELECT * FROM coupons ORDER BY created_at DESC')->fetchAll();
    }

    public function findByCode(string $code): ?array {
        $st = $this->db->prepare(
            "SELECT * FROM coupons WHERE code=? AND is_active=1 AND (expires_at IS NULL OR expires_at > NOW())"
        );
        $st->execute([strtoupper($code)]);
        return $st->fetch() ?: null;
    }

    public function validate(string $code, float $orderAmount): array {
        $coupon = $this->findByCode($code);
        if (!$coupon) return ['valid' => false, 'message' => 'Invalid or expired coupon code.'];
        if ($coupon['min_order_amount'] && $orderAmount < (float)$coupon['min_order_amount']) {
            return ['valid' => false, 'message' => 'Minimum order amount ₹' . number_format((float)$coupon['min_order_amount'], 0) . ' not met.'];
        }
        $discount = 0;
        if ($coupon['discount_type'] === 'percent') {
            $discount = $orderAmount * ((float)$coupon['discount'] / 100);
            if ($coupon['max_discount']) $discount = min($discount, (float)$coupon['max_discount']);
        } else {
            $discount = (float)$coupon['discount'];
        }
        return ['valid' => true, 'coupon' => $coupon, 'discount' => round($discount, 2)];
    }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO coupons (code,discount_type,discount,min_order_amount,max_discount,is_active,expires_at)
             VALUES (?,?,?,?,?,?,?)'
        );
        $st->execute([
            strtoupper($d['code']),
            $d['discount_type'] ?? 'percent',
            (float)$d['discount'],
            $d['min_order_amount'] ?? null,
            $d['max_discount'] ?? null,
            (int)(bool)($d['is_active'] ?? true),
            $d['expires_at'] ?? null,
        ]);
        $id = (int)$this->db->lastInsertId();
        $st = $this->db->prepare('SELECT * FROM coupons WHERE id=?');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM coupons WHERE id=?');
        return $st->execute([$id]);
    }
}
