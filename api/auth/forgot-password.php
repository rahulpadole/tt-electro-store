<?php
declare(strict_types=1);

if ($method !== 'POST') jsonError('Method not allowed', 405);

$d     = getJsonBody();
$email = strtolower(trim($d['email'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Enter a valid email address', 422);
}

$um   = new UserModel();
$user = $um->findByEmail($email);

if (!$user || $user['role'] === 'guest') {
    jsonSuccess([
        'sent'    => true,
        'masked'  => null,
        'dev_mode'=> false,
    ], 'If this email is registered, an OTP has been sent to the linked phone number.');
}

$phone = preg_replace('/[^0-9]/', '', $user['phone'] ?? '');
$phone = preg_replace('/^(91|0)/', '', $phone);

if (strlen($phone) !== 10) {
    jsonError('No verified phone number linked to this account. Please contact support at +91 7721892429.', 422);
}

$om = new OtpModel();

if ($om->isRecentlySent($phone, 'forgot_password', 30)) {
    jsonError('Please wait 30 seconds before requesting another OTP', 429);
}

$otp = $om->generate($phone, 'forgot_password');

$apiKey  = getenv('FAST2SMS_KEY') ?: '';
$devMode = empty($apiKey);
$smsSent = false;

if (!$devMode) {
    $payload = http_build_query([
        'variables_values' => $otp,
        'route'            => 'otp',
        'numbers'          => $phone,
    ]);
    $ctx = stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => "authorization: {$apiKey}\r\nContent-Type: application/x-www-form-urlencoded\r\n",
        'content' => $payload,
        'timeout' => 8,
        'ignore_errors' => true,
    ]]);
    $res     = @file_get_contents('https://www.fast2sms.com/dev/bulkV2', false, $ctx);
    $result  = json_decode((string)$res, true);
    $smsSent = ($result['return'] ?? false) === true;
}

$masked = substr($phone, 0, 2) . '****' . substr($phone, -4);

$response = [
    'sent'    => true,
    'masked'  => $masked,
    'dev_mode'=> $devMode,
];

if ($devMode) {
    $response['otp'] = $otp;
}

jsonSuccess($response, $devMode
    ? 'OTP generated (dev mode – no SMS sent)'
    : 'OTP sent to +91 ' . $masked);
