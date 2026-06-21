<?php declare(strict_types=1);
$cm = new CouponModel();
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) jsonError('Invalid coupon ID', 400);

if (isGet()) {
    requireAdmin();
    $c = $cm->findById($id);
    if (!$c) jsonError('Not found', 404);
    jsonSuccess($c);
}

if (isPatch()) {
    requireAdmin();
    $d = getJsonBody();
    $updated = $cm->update($id, $d);
    if (!$updated) jsonError('Coupon not found', 404);
    jsonSuccess($updated, 'Updated');
}

if (isDelete()) {
    requireAdmin();
    $cm->delete($id);
    jsonSuccess(null, 'Deleted');
}

jsonError('Method not allowed', 405);
