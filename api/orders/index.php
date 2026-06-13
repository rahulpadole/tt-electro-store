<?php declare(strict_types=1);
$om  = new OrderModel();
$uid = getCurrentUserId();

if (isGet()) {
    requireLogin();
    jsonSuccess($om->getForUser($uid));
}

if (isPost()) {
    requireLogin();
    $d = getJsonBody();

    $v = Validator::make($d)
        ->required('items')
        ->required('subtotal')
        ->required('total')
        ->required('shipping_address');

    if ($v->fails()) jsonError('Validation failed', 422, $v->errors());

    if (!is_array($d['items']) || empty($d['items'])) {
        jsonError('Order must contain at least one item', 422);
    }

    $d['user_id'] = $uid;

    try {
        $order = $om->create($d);
    } catch (\RuntimeException $e) {
        jsonError($e->getMessage(), 422);
    }

    (new CartModel())->clearCart($uid);

    jsonSuccess($order, 'Order placed', 201);
}

jsonError('Method not allowed', 405);
