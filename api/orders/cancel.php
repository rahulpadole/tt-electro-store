<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/bootstrap.php';

requireLogin();
if (!isPost()) jsonError('Method not allowed', 405);

$orderId = (int)($_GET['id'] ?? 0);
if (!$orderId) jsonError('Order ID required', 422);

$d      = getJsonBody();
$reason = trim($d['reason'] ?? '');

$om    = new OrderModel();
$order = $om->findById($orderId);

if (!$order) jsonError('Order not found', 404);
if ((int)$order['user_id'] !== getCurrentUserId() && !isAdmin()) jsonError('Forbidden', 403);

$nonCancellable = ['shipped', 'delivered', 'cancelled'];
if (in_array($order['status'], $nonCancellable)) {
    jsonError('This order cannot be cancelled once it has been ' . $order['status'], 422);
}

$updated = $om->cancelOrder($orderId, $reason);
jsonSuccess($updated, 'Order cancelled successfully.');
