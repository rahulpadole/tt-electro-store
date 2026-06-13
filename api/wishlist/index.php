<?php declare(strict_types=1);
requireLogin();
$wm  = new WishlistModel();
$uid = getCurrentUserId();

if (isGet()) {
    jsonSuccess($wm->getForUser($uid));
}

if (isPost()) {
    $d = getJsonBody();
    $v = Validator::make($d)->required('product_id');
    if ($v->fails()) jsonError('Validation failed', 422, $v->errors());

    $result = $wm->add($uid, (int)$d['product_id']);
    if (!empty($result['exists'])) {
        jsonSuccess($result, 'Already in wishlist', 200);
    }
    jsonSuccess($result, 'Added to wishlist', 201);
}

if (isDelete()) {
    $d = getJsonBody();
    $productId = (int)($d['product_id'] ?? 0);
    if (!$productId) jsonError('product_id required', 422);
    $wm->remove($uid, $productId);
    jsonSuccess(null, 'Removed from wishlist');
}

jsonError('Method not allowed', 405);
