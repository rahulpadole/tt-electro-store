<?php declare(strict_types=1);
requireLogin();
$wm  = new WishlistModel();
$uid = getCurrentUserId();
$pid = (int)($_GET['product_id'] ?? 0);

if (isDelete()) {
    if (!$pid) jsonError('product_id required', 422);
    $wm->remove($uid, $pid);
    jsonSuccess(null, 'Removed from wishlist');
}

jsonError('Method not allowed', 405);
