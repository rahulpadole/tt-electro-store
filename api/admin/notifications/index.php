<?php declare(strict_types=1);
requireAdmin();
$m = new AdminNotificationModel();

if (isGet()) {
    $limit = (int)($_GET['limit'] ?? 20);
    $items = $m->recent($limit);
    foreach ($items as &$n) {
        $n['link'] = AdminNotificationModel::linkFor($n);
        $n['data'] = json_decode((string)($n['data'] ?? '{}'), true) ?: [];
    }
    unset($n);
    jsonSuccess([
        'items'        => $items,
        'unread_count' => $m->unreadCount(),
    ]);
}

jsonError('Method not allowed', 405);
