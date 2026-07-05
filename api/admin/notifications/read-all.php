<?php declare(strict_types=1);
requireAdmin();
if (isPatch()) {
    (new AdminNotificationModel())->markAllRead();
    jsonSuccess(null, 'All marked read');
}
jsonError('Method not allowed', 405);
