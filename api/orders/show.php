<?php declare(strict_types=1);
$om = new OrderModel();
$id = (int)($_GET['id'] ?? 0);

if (isGet()) {
    requireLogin();
    $order = $om->findById($id);
    if (!$order) jsonError('Not found', 404);
    if ($order['user_id'] != getCurrentUserId() && !isAdmin()) jsonError('Forbidden', 403);
    jsonSuccess($order);
}

if (isPatch()) {
    requireAdmin();
    $d      = getJsonBody();
    $status = $d['status'] ?? '';
    $allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($status, $allowed, true)) {
        jsonError('Invalid status. Must be one of: ' . implode(', ', $allowed), 422);
    }
    $order = $om->updateStatus($id, $status);
    if (!$order) jsonError('Not found', 404);
    jsonSuccess($order, 'Status updated');
}

jsonError('Method not allowed', 405);
