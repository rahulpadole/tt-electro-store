<?php declare(strict_types=1);
requireAdmin();
if (isPatch()) {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) jsonError('Invalid notification id', 422);
    (new AdminNotificationModel())->markRead($id);
    jsonSuccess(null, 'Marked read');
}
jsonError('Method not allowed', 405);
