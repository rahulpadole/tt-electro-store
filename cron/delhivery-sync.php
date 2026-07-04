<?php
declare(strict_types=1);

/**
 * Delhivery Shipment Auto-Sync Cron
 * Run via Hostinger Cron Jobs every 30–60 minutes:
 *   php /path/to/cron/delhivery-sync.php
 *
 * Fetches live tracking for all active Delhivery shipments and sends
 * email/SMS notifications when the delivery status changes.
 */

define('CRON_RUN', true);
require_once dirname(__DIR__) . '/bootstrap.php';

if (!delhiveryIsConfigured()) {
    echo "[delhivery-sync] Delhivery API key not configured. Exiting.\n";
    exit(0);
}

$om      = new OrderModel();
$um      = new UserModel();
$orders  = $om->getActiveShipments();
$total   = count($orders);
$updated = 0;

echo "[delhivery-sync] Found {$total} active Delhivery shipment(s).\n";

foreach ($orders as $order) {
    $awb = $order['awb_number'] ?? '';
    if (empty($awb)) continue;

    echo "[delhivery-sync] Checking AWB {$awb} (Order #{$order['order_number']})... ";

    $result = delhiveryTrackShipment($awb);
    if (!$result['success']) {
        echo "FAILED: " . ($result['error'] ?? 'Unknown error') . "\n";
        continue;
    }

    $prevStatus = $order['delivery_status'] ?? '';
    $newStatus  = $result['status'] ?? $prevStatus;
    $eta        = $result['expected_delivery_date'] ?? ($order['expected_delivery_date'] ?? null);

    if ($newStatus === $prevStatus) {
        echo "no change ({$newStatus}).\n";
        continue;
    }

    echo "status changed: {$prevStatus} → {$newStatus}.\n";

    $updates = ['delivery_status' => $newStatus];
    if ($eta) $updates['expected_delivery_date'] = $eta;

    $updatedOrder = $om->updateDelhivery((int)$order['id'], $updates);

    if (!$updatedOrder) {
        echo "[delhivery-sync] WARNING: DB update failed for order #{$order['order_number']}\n";
        continue;
    }

    // Auto-update order status
    $statusLc = strtolower($newStatus);
    if (in_array($statusLc, ['delivered'], true)) {
        $om->updateStatus((int)$order['id'], 'delivered');
        $updatedOrder['status'] = 'delivered';
    } elseif (in_array($statusLc, ['rto delivered', 'return delivered'], true)) {
        $om->updateStatus((int)$order['id'], 'cancelled');
        $updatedOrder['status'] = 'cancelled';
    } elseif (in_array($statusLc, ['manifested', 'in transit', 'out for delivery'], true)) {
        if (($order['status'] ?? '') === 'processing') {
            $om->updateStatus((int)$order['id'], 'shipped');
            $updatedOrder['status'] = 'shipped';
        }
    }

    // Fire email + SMS notification
    $user = !empty($order['user_id']) ? $um->findById((int)$order['user_id']) : null;
    notifyDelhiveryUpdate($updatedOrder, $user ?: null, false);

    $updated++;
}

echo "[delhivery-sync] Done. {$updated}/{$total} shipments had status changes.\n";
