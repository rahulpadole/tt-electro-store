<?php
declare(strict_types=1);

$cm  = new CartModel();
$uid = getCurrentUserId();

if (isGet()) {
    jsonSuccess($cm->getItems($uid));
}

if (isPost()) {
    $d = getJsonBody();

    $v = Validator::make($d)
        ->required('product_id')
        ->integer('product_id');

    if ($v->fails()) {
        jsonError('Validation failed', 422, $v->errors());
    }

    $item = $cm->addItem(
        $uid,
        (int)$d['product_id'],
        (int)($d['quantity'] ?? 1)
    );

    jsonSuccess($item, 'Added to cart', 201);
}

if (isDelete()) {
    $cm->clearCart($uid);
    jsonSuccess(null, 'Cart cleared');
}

jsonError('Method not allowed', 405);
