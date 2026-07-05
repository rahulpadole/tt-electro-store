<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/bootstrap.php';

requireLogin();
if (!isPost()) jsonError('Method not allowed', 405);

$d = getJsonBody();

$razorpayOrderId   = trim($d['razorpay_order_id']   ?? '');
$razorpayPaymentId = trim($d['razorpay_payment_id']  ?? '');
$razorpaySignature = trim($d['razorpay_signature']   ?? '');

if (!$razorpayOrderId || !$razorpayPaymentId || !$razorpaySignature) {
    jsonError('Missing payment verification data', 422);
}

$keySecret = getenv('RAZORPAY_KEY_SECRET');
if (!$keySecret) jsonError('Payment gateway not configured', 500);

$expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $keySecret);

if (!hash_equals($expectedSignature, $razorpaySignature)) {
    error_log('[Razorpay] Signature mismatch for payment: ' . $razorpayPaymentId);
    jsonError('Payment verification failed. Invalid signature.', 400);
}

$orderData = $d['order_data'] ?? [];
if (empty($orderData)) jsonError('Order data missing', 422);

$uid = getCurrentUserId();
$orderData['user_id']             = $uid;
$orderData['razorpay_order_id']   = $razorpayOrderId;
$orderData['razorpay_payment_id'] = $razorpayPaymentId;
$orderData['payment_status']      = 'paid';

$v = Validator::make($orderData)
    ->required('items')
    ->required('subtotal')
    ->required('total')
    ->required('shipping_address');

if ($v->fails()) jsonError('Validation failed', 422, $v->errors());
if (!is_array($orderData['items']) || empty($orderData['items'])) {
    jsonError('Order must contain at least one item', 422);
}

$om = new OrderModel();
try {
    $order = $om->create($orderData);
} catch (\RuntimeException $e) {
    jsonError($e->getMessage(), 422);
}

(new CartModel())->clearCart($uid);

$user = getCurrentUser();
notifyOrderStatusChange($order, $user, 'Payment confirmed via Razorpay. Order ID: ' . $razorpayPaymentId);
notifyAdminNewOrder($order);
triggerAutoShipment($order);

jsonSuccess($order, 'Payment successful! Order placed.', 201);
