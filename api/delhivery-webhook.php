<?php
declare(strict_types=1);

/**
 * Delhivery Tracking Webhook
 * POST /api/delhivery/webhook
 *
 * Delhivery sends a POST with shipment status updates.
 * We look up the order by AWB, update delivery_status, and fire email/SMS.
 *
 * Secure with a shared secret: set DELHIVERY_WEBHOOK_SECRET in .env
 * and configure the same token in your Delhivery account webhook settings.
 */

require_once dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

// ── Verify shared secret (token in header or query param) ─────────────────
$webhookSecret = getenv('DELHIVERY_WEBHOOK_SECRET') ?: '';
if (!empty($webhookSecret)) {
    $incomingToken = $_SERVER['HTTP_X_DELHIVERY_TOKEN']
        ?? $_SERVER['HTTP_AUTHORIZATION']
        ?? ($_GET['token'] ?? '');
    $incomingToken = ltrim($incomingToken, 'Token ');
    if (!hash_equals($webhookSecret, $incomingToken)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
}

// ── Parse payload ─────────────────────────────────────────────────────────
$rawBody = file_get_contents('php://input');
$payload = json_decode((string)$rawBody, true);

if (!$payload) {
    // Delhivery sometimes sends form-encoded
    parse_str($rawBody, $payload);
}

if (empty($payload)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Empty payload']);
    exit;
}

// ── Extract AWB and status ────────────────────────────────────────────────
$awb    = $payload['waybill'] ?? $payload['awb'] ?? ($payload['ShipmentData'][0]['Shipment']['AWB'] ?? null);
$status = $payload['status']  ?? $payload['Status'] ?? ($payload['ShipmentData'][0]['Shipment']['Status']['Status'] ?? null);
$eta    = $payload['expected_delivery_date']
       ?? $payload['ExpectedDeliveryDate']
       ?? ($payload['ShipmentData'][0]['Shipment']['ExpectedDeliveryDate'] ?? null);

if (empty($awb)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'AWB number missing from payload']);
    exit;
}

// ── Look up order ─────────────────────────────────────────────────────────
$om    = new OrderModel();
$um    = new UserModel();
$order = $om->findByAwb((string)$awb);

if (!$order) {
    // Not our shipment — respond 200 so Delhivery stops retrying
    echo json_encode(['success' => true, 'message' => 'AWB not tracked']);
    exit;
}

// ── Update only if status actually changed ────────────────────────────────
$prevStatus = $order['delivery_status'] ?? '';
$newStatus  = $status ? (string)$status : $prevStatus;

if ($newStatus === $prevStatus && !$eta) {
    echo json_encode(['success' => true, 'message' => 'No change']);
    exit;
}

$updates = ['delivery_status' => $newStatus];
if ($eta) $updates['expected_delivery_date'] = $eta;

$updated = $om->updateDelhivery((int)$order['id'], $updates);

if (!$updated) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB update failed']);
    exit;
}

// ── Auto-update order status on delivery/return ───────────────────────────
$statusLc = strtolower($newStatus);
if (in_array($statusLc, ['delivered'], true)) {
    $om->updateStatus((int)$order['id'], 'delivered');
    $updated['status'] = 'delivered';
} elseif (in_array($statusLc, ['rto delivered', 'return delivered'], true)) {
    $om->updateStatus((int)$order['id'], 'cancelled');
    $updated['status'] = 'cancelled';
} elseif (in_array($statusLc, ['manifested', 'in transit', 'out for delivery'], true)) {
    if ($order['status'] === 'processing') {
        $om->updateStatus((int)$order['id'], 'shipped');
        $updated['status'] = 'shipped';
    }
}

// ── Fire email + SMS notification ─────────────────────────────────────────
if ($newStatus !== $prevStatus) {
    $user = !empty($order['user_id']) ? $um->findById((int)$order['user_id']) : null;
    notifyDelhiveryUpdate($updated, $user ?: null, false);
}

echo json_encode(['success' => true, 'message' => 'Webhook processed', 'awb' => $awb, 'status' => $newStatus]);
