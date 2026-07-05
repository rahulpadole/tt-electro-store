<?php
declare(strict_types=1);

/**
 * Auto-shipment orchestrator for Delhivery.
 * Call triggerAutoShipment($order) right after order creation.
 * Completely non-blocking — any failure is logged silently so the
 * customer's order is never lost.
 */

function triggerAutoShipment(array $order): void {
    if (!delhiveryIsConfigured()) {
        _logDelivery((int)($order['id'] ?? 0), 'skipped', 'Delhivery API key not configured');
        return;
    }

    if (!empty($order['awb_number'])) {
        return;
    }

    $addr  = is_array($order['shipping_address'] ?? null) ? $order['shipping_address'] : [];
    $items = $order['items'] ?? [];

    try {
        $result = delhiveryCreateShipment($order, $addr, $items);

        if (!empty($result['success']) && !empty($result['awb_number'])) {
            $om      = new OrderModel();
            $updated = $om->updateDelhivery((int)$order['id'], [
                'delivery_partner'       => 'Delhivery',
                'awb_number'             => $result['awb_number'],
                'delivery_status'        => $result['status'] ?? 'Manifested',
                'expected_delivery_date' => $result['expected_delivery_date'] ?? null,
            ]);
            _logDelivery((int)$order['id'], 'success', 'AWB: ' . $result['awb_number']);

            if ($updated) {
                $user = (new UserModel())->findById((int)$order['user_id']);
                notifyDelhiveryUpdate($updated, $user ?: null, true);
            }
        } else {
            $err = $result['error'] ?? 'Delhivery returned no AWB';
            _logDelivery((int)$order['id'], 'failed', $err);
            notifyAdminShipmentFailed($order, $err);
        }
    } catch (\Throwable $e) {
        $msg = $e->getMessage();
        _logDelivery((int)($order['id'] ?? 0), 'error', $msg);
        error_log('[ShipmentService] Exception for order #' . ($order['id'] ?? '?') . ': ' . $msg);
        notifyAdminShipmentFailed($order, $msg);
    }
}

function _logDelivery(int $orderId, string $status, string $message): void {
    try {
        Database::getConnection()->prepare(
            'INSERT INTO delivery_logs (order_id, event_type, status, message, created_at)
             VALUES (?, ?, ?, ?, NOW())'
        )->execute([$orderId ?: null, 'shipment_create', $status, $message]);
    } catch (\Throwable $e) {
        error_log('[ShipmentService] Log failed: ' . $e->getMessage());
    }
}
