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

    public function findByAwb(string $awb): ?array {
        $st = $this->db->prepare('SELECT * FROM orders WHERE awb_number=?');
        $st->execute([$awb]);
        $row = $st->fetch();
        if (!$row) return null;
        $row = normalizeOrder($row);
        $row['items'] = $this->getItems((int)$row['id']);
        return $row;
    }

    public function getActiveShipments(): array {
        $st = $this->db->prepare(
            "SELECT * FROM orders
             WHERE delivery_partner='Delhivery'
               AND awb_number IS NOT NULL
               AND awb_number != ''
               AND status NOT IN ('delivered','cancelled')
             ORDER BY created_at DESC"
        );
        $st->execute();
        return array_map('normalizeOrder', $st->fetchAll());
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
            $addr        = is_array($d['shipping_address']) ? json_encode($d['shipping_address']) : $d['shipping_address'];

            $initialStatus = ($d['payment_status'] ?? 'pending') === 'paid' ? 'processing' : 'pending';
            $initialLabel  = $initialStatus === 'processing' ? 'Payment Confirmed' : 'Order Placed';
            $timeline      = json_encode([['status' => $initialStatus, 'time' => date('c'), 'label' => $initialLabel]]);

            $st = $this->db->prepare(
                "INSERT INTO orders (user_id,order_number,status,subtotal,discount,shipping,tax,total,
                  shipping_address,payment_method,payment_status,razorpay_order_id,razorpay_payment_id,
                  notes,coupon_code,status_timeline)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
            );
            $st->execute([
                $d['user_id'],
                $orderNumber,
                $initialStatus,
                (float)$d['subtotal'],
                (float)($d['discount'] ?? 0),
                (float)($d['shipping'] ?? 0),
                (float)($d['tax'] ?? 0),
                (float)$d['total'],
                $addr,
                $d['payment_method'] ?? 'cod',
                $d['payment_status'] ?? 'pending',
                $d['razorpay_order_id'] ?? null,
                $d['razorpay_payment_id'] ?? null,
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

    public function cancelOrder(int $id, string $reason = ''): ?array {
        $st = $this->db->prepare('SELECT status_timeline FROM orders WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        $timeline = json_decode($row['status_timeline'] ?? '[]', true) ?: [];
        $timeline[] = ['status' => 'cancelled', 'time' => date('c'), 'label' => 'Order Cancelled'];
        $st = $this->db->prepare(
            "UPDATE orders SET status='cancelled', status_timeline=?, cancelled_at=NOW(), cancellation_reason=?, updated_at=NOW() WHERE id=?"
        );
        $st->execute([json_encode($timeline), $reason, $id]);
        return $this->findById($id);
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

    public function updateDelhivery(int $id, array $data): ?array {
        $st = $this->db->prepare('SELECT status_timeline FROM orders WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        if (!$row) return null;

        $timeline = json_decode($row['status_timeline'] ?? '[]', true) ?: [];
        $fields = [];
        $values = [];

        foreach (['delivery_partner', 'awb_number', 'delivery_status', 'expected_delivery_date'] as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "`{$f}`=?";
                $values[] = $data[$f];
            }
        }
        if (empty($fields)) return $this->findById($id);

        if (!empty($data['awb_number']) && empty($row['awb_number'] ?? null)) {
            $timeline[] = ['status' => 'shipped', 'time' => date('c'), 'label' => 'Shipped via Delhivery (AWB: ' . $data['awb_number'] . ')'];
            $fields[] = "`status`='shipped'";
            $fields[] = "`status_timeline`=?";
            $values[] = json_encode($timeline);
        }

        $values[] = $id;
        $sql = 'UPDATE orders SET ' . implode(',', $fields) . ', updated_at=NOW() WHERE id=?';
        $this->db->prepare($sql)->execute($values);
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

    private function buildFilterWhere(array $filters): array {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'o.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['payment_method'])) {
            $where[] = 'o.payment_method = ?';
            $params[] = $filters['payment_method'];
        }
        if (!empty($filters['search'])) {
            $where[] = '(o.order_number LIKE ? OR u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ? OR o.awb_number LIKE ?)';
            $s = '%' . $filters['search'] . '%';
            array_push($params, $s, $s, $s, $s, $s);
        }
        if (!empty($filters['date_from'])) {
            $where[] = 'DATE(o.created_at) >= ?';
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where[] = 'DATE(o.created_at) <= ?';
            $params[] = $filters['date_to'];
        }

        return [implode(' AND ', $where), $params];
    }

    public function search(array $filters = [], int $limit = 20, int $offset = 0): array {
        [$whereSql, $params] = $this->buildFilterWhere($filters);
        $sql = "SELECT o.*,u.name as user_name,u.email as user_email,u.phone as user_phone
                FROM orders o JOIN users u ON o.user_id=u.id
                WHERE {$whereSql}
                ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
        $st = $this->db->prepare($sql);
        $i = 1;
        foreach ($params as $p) { $st->bindValue($i++, $p); }
        $st->bindValue($i++, $limit, PDO::PARAM_INT);
        $st->bindValue($i++, $offset, PDO::PARAM_INT);
        $st->execute();
        return array_map('normalizeOrder', $st->fetchAll());
    }

    public function searchCount(array $filters = []): int {
        [$whereSql, $params] = $this->buildFilterWhere($filters);
        $sql = "SELECT COUNT(*) FROM orders o JOIN users u ON o.user_id=u.id WHERE {$whereSql}";
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return (int)$st->fetchColumn();
    }

    public function searchAll(array $filters = []): array {
        [$whereSql, $params] = $this->buildFilterWhere($filters);
        $sql = "SELECT o.*,u.name as user_name,u.email as user_email,u.phone as user_phone
                FROM orders o JOIN users u ON o.user_id=u.id
                WHERE {$whereSql}
                ORDER BY o.created_at DESC";
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return array_map('normalizeOrder', $st->fetchAll());
    }

    public function bulkUpdateStatus(array $ids, string $status): int {
        if (empty($ids)) return 0;
        $labels = [
            'pending'    => 'Order Placed',
            'processing' => 'Processing',
            'shipped'    => 'Shipped',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
        ];
        $updated = 0;
        foreach ($ids as $id) {
            $this->updateStatus((int)$id, $status);
            $updated++;
        }
        return $updated;
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

    public function todayRevenue(): float {
        return (float)$this->db->query(
            "SELECT COALESCE(SUM(total),0) FROM orders WHERE status!='cancelled' AND DATE(created_at)=CURDATE()"
        )->fetchColumn();
    }

    public function monthRevenue(): float {
        return (float)$this->db->query(
            "SELECT COALESCE(SUM(total),0) FROM orders WHERE status!='cancelled'
             AND YEAR(created_at)=YEAR(CURDATE()) AND MONTH(created_at)=MONTH(CURDATE())"
        )->fetchColumn();
    }

    public function countToday(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE()")->fetchColumn();
    }

    public function countByStatus(string $status): int {
        $st = $this->db->prepare('SELECT COUNT(*) FROM orders WHERE status = ?');
        $st->execute([$status]);
        return (int)$st->fetchColumn();
    }

    public function paymentMethodBreakdown(): array {
        $st = $this->db->query(
            "SELECT COALESCE(payment_method,'cod') as method, COUNT(*) as cnt
             FROM orders GROUP BY payment_method ORDER BY cnt DESC"
        );
        return $st->fetchAll();
    }

    public function recentActivity(int $limit = 8): array {
        $st = $this->db->prepare(
            "SELECT o.id, o.order_number, o.status, o.total, o.created_at, u.name as user_name
             FROM orders o JOIN users u ON o.user_id=u.id
             ORDER BY o.created_at DESC LIMIT ?"
        );
        $st->execute([$limit]);
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
