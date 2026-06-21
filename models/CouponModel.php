<?php
declare(strict_types=1);

class CouponModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function all(): array {
        return $this->db->query('SELECT * FROM coupons ORDER BY created_at DESC')->fetchAll();
    }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM coupons WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
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
            return [
                'valid'   => false,
                'message' => 'Minimum order amount ₹' . number_format((float)$coupon['min_order_amount'], 0) . ' required.',
            ];
        }

        $discount = 0;
        if ($coupon['discount_type'] === 'percent') {
            $discount = $orderAmount * ((float)$coupon['discount'] / 100);
            if ($coupon['max_discount']) {
                $discount = min($discount, (float)$coupon['max_discount']);
            }
        } else {
            // fixed / flat
            $discount = (float)$coupon['discount'];
        }

        return ['valid' => true, 'coupon' => $coupon, 'discount' => round($discount, 2)];
    }

    public function create(array $d): array {
        $st = $this->db->prepare(
            'INSERT INTO coupons (code, discount_type, discount, min_order_amount, max_discount, is_active, expires_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $st->execute([
            strtoupper(trim($d['code'])),
            $d['discount_type'] ?? 'percent',
            (float)$d['discount'],
            isset($d['min_order_amount']) && $d['min_order_amount'] !== '' ? (float)$d['min_order_amount'] : null,
            isset($d['max_discount'])     && $d['max_discount']     !== '' ? (float)$d['max_discount']     : null,
            (int)(bool)($d['is_active'] ?? true),
            isset($d['expires_at']) && $d['expires_at'] !== '' ? $d['expires_at'] : null,
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function update(int $id, array $d): ?array {
        $allowed = ['code', 'discount_type', 'discount', 'min_order_amount', 'max_discount', 'is_active', 'expires_at'];
        $fields  = [];
        $vals    = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $d)) {
                $fields[] = "{$f}=?";
                $val = $d[$f];
                if ($f === 'code') $val = strtoupper(trim((string)$val));
                $vals[] = ($val === '' ? null : $val);
            }
        }
        if (empty($fields)) return $this->findById($id);
        $vals[] = $id;
        $st = $this->db->prepare('UPDATE coupons SET ' . implode(',', $fields) . ' WHERE id=?');
        $st->execute($vals);
        return $this->findById($id);
    }

    public function toggleActive(int $id): ?array {
        $coupon = $this->findById($id);
        if (!$coupon) return null;
        $newState = (int)!$coupon['is_active'];
        $st = $this->db->prepare('UPDATE coupons SET is_active=? WHERE id=?');
        $st->execute([$newState, $id]);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare('DELETE FROM coupons WHERE id=?');
        return $st->execute([$id]);
    }
}
