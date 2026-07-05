<?php declare(strict_types=1);
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
if (isDelete()) {
    if ($id <= 0) jsonError('Invalid notification id', 422);
    (new AdminNotificationModel())->delete($id);
    jsonSuccess(null, 'Deleted');
}
jsonError('Method not allowed', 405);
