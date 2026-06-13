<?php
declare(strict_types=1);

class OrderModel {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function findById(int $id): ?array {
        $st = $this->db->prepare('SELECT * FROM orders WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        if (!$row) return null;
        $row = normalizeOrder($row);
        $row['items'] = $this->getItems($id);
        return $row;
    }

    public function findByOrderNumber(string $num): ?array {
        $st = $this->db->prepare('SELECT * FROM orders WHERE order_number=?');
        $st->execute([$num]);
        $row = $st->fetch();
        if (!$row) return null;
        $row = normalizeOrder($row);
        $row['items'] = $this->getItems((int)$row['id']);
        return $row;
    }

    public function getForUser(int $userId): array {
        $st = $this->db->prepare('SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC');
        $st->execute([$userId]);
        return array_map('normalizeOrder', $st->fetchAll());
    }

    public function getItems(int $orderId): array {
        $st = $this->db->prepare('SELECT * FROM order_items WHERE order_id=?');
        $st->execute([$orderId]);
        return $st->fetchAll();
    }

    public function create(array $d): array {
        $items = $d['items'] ?? [];

        $this->db->beginTransaction();
        try {
            // Stock check & decrement for each item
            foreach ($items as $item) {
                $pid = (int)$item['product_id'];
                $qty = (int)$item['quantity'];

                $st = $this->db->prepare(
                    'SELECT stock FROM products WHERE id=? FOR UPDATE'
                );
                $st->execute([$pid]);
                $row = $st->fetch();

                if (!$row) {
                    $this->db->rollBack();
                    throw new \RuntimeException("Product #{$pid} not found");
                }
                if ((int)$row['stock'] < $qty) {
                    $this->db->rollBack();
                    throw new \RuntimeException("Insufficient stock for product #{$pid} (available: {$row['stock']}, requested: {$qty})");
                }

                $this->db->prepare(
                    'UPDATE products SET stock = stock - ? WHERE id=?'
                )->execute([$qty, $pid]);
            }

            $orderNumber = 'TTE-' . strtoupper(substr(uniqid(), 0, 8));
            $timeline    = json_encode([['status' => 'pending', 'time' => date('c'), 'label' => 'Order Placed']]);
            $addr        = is_array($d['shipping_address']) ? json_encode($d['shipping_address']) : $d['shipping_address'];

            $st = $this->db->prepare(
                "INSERT INTO orders (user_id,order_number,status,subtotal,discount,shipping,tax,total,
                  shipping_address,payment_method,notes,coupon_code,status_timeline)
                 VALUES (?,?,'pending',?,?,?,?,?,?,?,?,?,?)"
            );
            $st->execute([
                $d['user_id'],
                $orderNumber,
                (float)$d['subtotal'],
                (float)($d['discount'] ?? 0),
                (float)($d['shipping'] ?? 0),
                (float)($d['tax'] ?? 0),
                (float)$d['total'],
                $addr,
                $d['payment_method'] ?? 'cod',
                $d['notes'] ?? null,
                $d['coupon_code'] ?? null,
                $timeline,
            ]);
            $orderId = (int)$this->db->lastInsertId();

            foreach ($items as $item) {
                $this->db->prepare(
                    'INSERT INTO order_items (order_id,product_id,product_name,thumbnail,quantity,price) VALUES (?,?,?,?,?,?)'
                )->execute([
                    $orderId,
                    (int)$item['product_id'],
                    $item['product_name'],
                    $item['thumbnail'] ?? null,
                    (int)$item['quantity'],
                    (float)$item['price'],
                ]);
            }

            $this->db->commit();
            return $this->findById($orderId);

        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    public function updateStatus(int $id, string $status): ?array {
        $st = $this->db->prepare('SELECT status_timeline FROM orders WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        $timeline = json_decode($row['status_timeline'] ?? '[]', true) ?: [];
        $labels = [
            'pending'    => 'Order Placed',
            'processing' => 'Processing',
            'shipped'    => 'Shipped',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
        ];
        $timeline[] = ['status' => $status, 'time' => date('c'), 'label' => $labels[$status] ?? ucfirst($status)];
        $st = $this->db->prepare("UPDATE orders SET status=?,status_timeline=?,updated_at=NOW() WHERE id=?");
        $st->execute([$status, json_encode($timeline), $id]);
        return $this->findById($id);
    }

    public function all(int $limit = 50, int $offset = 0): array {
        $st = $this->db->prepare(
            'SELECT o.*,u.name as user_name,u.email as user_email
             FROM orders o JOIN users u ON o.user_id=u.id
             ORDER BY o.created_at DESC LIMIT ? OFFSET ?'
        );
        $st->execute([$limit, $offset]);
        return array_map('normalizeOrder', $st->fetchAll());
    }

    public function count(): int {
        return (int)$this->db->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    }

    public function totalRevenue(): float {
        return (float)$this->db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status!='cancelled'")->fetchColumn();
    }

    public function revenueByMonth(): array {
        $st = $this->db->query(
            "SELECT DATE_FORMAT(created_at,'%b') as month,
                    DATE_FORMAT(created_at,'%Y-%m') as month_key,
                    SUM(total) as revenue
             FROM orders
             WHERE status!='cancelled' AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY DATE_FORMAT(created_at,'%Y-%m'), DATE_FORMAT(created_at,'%b')
             ORDER BY month_key ASC"
        );
        return $st->fetchAll();
    }

    public function salesByCategory(): array {
        $st = $this->db->query(
            "SELECT c.name, SUM(oi.price*oi.quantity) as total
             FROM order_items oi
             JOIN products p ON oi.product_id=p.id
             JOIN categories c ON p.category_id=c.id
             JOIN orders o ON oi.order_id=o.id
             WHERE o.status!='cancelled'
             GROUP BY c.name ORDER BY total DESC LIMIT 8"
        );
        return $st->fetchAll();
    }

    public function topProducts(int $limit = 5): array {
        $st = $this->db->prepare(
            "SELECT p.id,p.name,p.thumbnail,
                    SUM(oi.quantity) as sold,
                    SUM(oi.price*oi.quantity) as revenue
             FROM order_items oi
             JOIN products p ON oi.product_id=p.id
             GROUP BY p.id,p.name,p.thumbnail
             ORDER BY sold DESC LIMIT ?"
        );
        $st->execute([$limit]);
        return $st->fetchAll();
    }
}
