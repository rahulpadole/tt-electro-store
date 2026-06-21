<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/bootstrap.php';

requireLogin();
if (!isPost()) jsonError('Method not allowed', 405);

$d = getJsonBody();

$total = (float)($d['total'] ?? 0);
if ($total <= 0) jsonError('Invalid order amount', 422);

$keyId     = getenv('RAZORPAY_KEY_ID');
$keySecret = getenv('RAZORPAY_KEY_SECRET');

if (!$keyId || !$keySecret) {
    jsonError('Payment gateway not configured', 500);
}

// Amount in paise (multiply by 100)
$amountPaise = (int)round($total * 100);

$receiptId = 'rcpt_' . uniqid();

$payload = json_encode([
    'amount'   => $amountPaise,
    'currency' => 'INR',
    'receipt'  => $receiptId,
    'notes'    => [
        'user_id' => getCurrentUserId(),
        'site'    => 'TT Electro Store',
    ],
]);

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_USERPWD        => "{$keyId}:{$keySecret}",
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT        => 15,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    error_log('[Razorpay] cURL error: ' . $curlErr);
    jsonError('Failed to connect to payment gateway', 502);
}

$result = json_decode($response, true);

if ($httpCode !== 200 || empty($result['id'])) {
    error_log('[Razorpay] API error: ' . $response);
    jsonError($result['error']['description'] ?? 'Failed to create payment order', 502);
}

jsonSuccess([
    'razorpay_order_id' => $result['id'],
    'amount'            => $amountPaise,
    'currency'          => 'INR',
    'key_id'            => $keyId,
]);
