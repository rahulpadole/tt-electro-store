<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/bootstrap.php';
requireAdmin();

$rm     = new ReturnModel();
$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method === 'GET') {
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $limit   = 20;
    $offset  = ($page - 1) * $limit;
    $returns = $rm->all($limit, $offset);
    $total   = $rm->count();
    jsonSuccess([
        'returns'     => $returns,
        'total'       => $total,
        'page'        => $page,
        'total_pages' => (int)ceil($total / $limit),
        'pending'     => $rm->countByStatus('pending'),
        'approved'    => $rm->countByStatus('approved'),
        'rejected'    => $rm->countByStatus('rejected'),
    ]);
}

if ($method === 'PATCH') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) jsonError('Return ID required', 422);

    $d       = getJsonBody();
    $status  = trim($d['status'] ?? '');
    $allowed = ['pending', 'approved', 'rejected', 'picked_up', 'refunded'];
    if (!in_array($status, $allowed, true)) jsonError('Invalid status', 422);

    $adminNotes   = isset($d['admin_notes'])   ? trim($d['admin_notes'])   : null;
    $refundAmount = isset($d['refund_amount'])  ? (float)$d['refund_amount'] : null;

    $ret = $rm->updateStatus($id, $status, $adminNotes ?: null, $refundAmount ?: null);
    if (!$ret) jsonError('Return not found', 404);

    $om    = new OrderModel();
    $um    = new UserModel();
    $order = $om->findById((int)$ret['order_id']);
    $user  = $um->findById((int)$ret['user_id']);

    if ($order && $user) {
        $statusLines = [
            'approved'  => 'Great news! Your return request for order #' . $order['order_number'] . ' has been <strong>approved</strong>. We will arrange a pickup soon.',
            'rejected'  => 'Your return request for order #' . $order['order_number'] . ' was not approved. ' . ($adminNotes ?: 'Please contact support for details.'),
            'picked_up' => 'Your return for order #' . $order['order_number'] . ' has been <strong>picked up</strong>. Your refund will be processed shortly.',
            'refunded'  => 'Your <strong>refund of ₹' . number_format((float)($ret['refund_amount'] ?? 0), 2) . '</strong> for order #' . $order['order_number'] . ' has been processed successfully.',
        ];
        $line    = $statusLines[$status] ?? 'Your return status has been updated to: ' . $status . '.';
        $subject = APP_NAME . ' — Return Update (' . $order['order_number'] . ')';
        $body    = buildOrderStatusEmailBody($order, $user['name'] ?? 'Customer', $line);
        if (!empty($user['email'])) sendOrderEmail($user['email'], $user['name'] ?? '', $subject, $body);
    }

    jsonSuccess($ret, 'Return status updated');
}

jsonError('Method not allowed', 405);
