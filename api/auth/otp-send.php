<?php
declare(strict_types=1);

if ($method !== 'POST') jsonError('Method not allowed', 405);

$d = getJsonBody();
$phone   = trim($d['phone']   ?? '');
$purpose = trim($d['purpose'] ?? 'register');

// Normalize phone: strip +91 / 91 prefix, keep 10 digits
$phone = preg_replace('/^\+91|^91/', '', preg_replace('/\D/', '', $phone));

if (strlen($phone) !== 10) {
    jsonError('Enter a valid 10-digit Indian mobile number', 422);
}
if (!in_array($purpose, ['register','verify_phone','forgot_password'], true)) {
    jsonError('Invalid purpose', 422);
}

$om = new OtpModel();

// Cooldown check — prevent spam
if ($om->isRecentlySent($phone, $purpose, 30)) {
    jsonError('Please wait 30 seconds before requesting another OTP', 429);
}

$otp = $om->generate($phone, $purpose);

// ── Send SMS via Fast2SMS (set FAST2SMS_KEY in .env) ──────────────────────
$apiKey  = getenv('FAST2SMS_KEY') ?: '';
$smsSent = false;
$devMode = empty($apiKey);

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

$responseData = [
    'message'  => $devMode
        ? 'OTP generated (dev mode — no SMS sent)'
        : ($smsSent ? 'OTP sent to +91 ' . $phone : 'OTP generated'),
    'expires_in' => 600,
];

// In dev mode (no API key) return OTP so devs can test without SMS
if ($devMode) {
    $responseData['otp']      = $otp;
    $responseData['dev_mode'] = true;
}

jsonSuccess($responseData);
