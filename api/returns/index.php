<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/bootstrap.php';
requireLogin();

$rm  = new ReturnModel();
$uid = getCurrentUserId();

if (isGet()) {
    jsonSuccess($rm->getForUser($uid));
}

if (isPost()) {
    $d = getJsonBody();

    $v = Validator::make($d)->required('order_id')->required('reason');
    if ($v->fails()) jsonError('Validation failed', 422, $v->errors());

    $orderId = (int)($d['order_id'] ?? 0);
    if (!$orderId) jsonError('Invalid order ID', 422);

    $om    = new OrderModel();
    $order = $om->findById($orderId);
    if (!$order || (int)$order['user_id'] !== $uid) jsonError('Order not found', 404);

    if ($order['status'] !== 'delivered') {
        jsonError('Only delivered orders can be returned', 422);
    }

    $timeline    = is_array($order['status_timeline']) ? $order['status_timeline'] : [];
    $deliveredAt = null;
    foreach (array_reverse($timeline) as $t) {
        if (($t['status'] ?? '') === 'delivered') {
            $deliveredAt = strtotime($t['time'] ?? '');
            break;
        }
    }
    if ($deliveredAt && (time() - $deliveredAt) > 86400) {
        jsonError('The 24-hour return window for this order has closed', 422);
    }

    if ($rm->getForOrder($orderId)) {
        jsonError('A return request already exists for this order', 422);
    }

    $reason = trim($d['reason'] ?? '');
    if (strlen($reason) < 3) jsonError('Please provide a reason for the return', 422);

    $images = [];
    if (!empty($d['images']) && is_array($d['images'])) {
        foreach ($d['images'] as $img) {
            if (is_string($img) && !empty(trim($img))) {
                $images[] = trim($img);
            }
        }
    }

    $ret = $rm->create([
        'order_id'    => $orderId,
        'user_id'     => $uid,
        'reason'      => $reason,
        'description' => $d['description'] ?? null,
        'images'      => $images,
    ]);

    notifyAdminNewReturn($ret);

    $user    = getCurrentUser();
    $name    = $user['name'] ?? 'Customer';
    $email   = $user['email'] ?? null;
    $subject = APP_NAME . ' — Return Request Received (' . $order['order_number'] . ')';
    $line    = 'We have received your return request for order #' . $order['order_number'] . '. Our team will review it and respond within 24–48 hours.';
    if ($email) sendOrderEmail($email, $name, $subject, buildOrderStatusEmailBody($order, $name, $line));

    jsonSuccess($ret, 'Return request submitted successfully', 201);
}

jsonError('Method not allowed', 405);
