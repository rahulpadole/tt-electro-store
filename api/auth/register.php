<?php
declare(strict_types=1);

if ($method !== 'POST') jsonError('Method not allowed', 405);

$d = getJsonBody();
$v = Validator::make($d)
    ->required('name')
    ->required('email')
    ->email('email')
    ->required('password')
    ->min('password', 8);

if ($v->fails()) jsonError('Validation failed', 422, $v->errors());

$um = new UserModel();
if ($um->findByEmail($d['email'])) jsonError('Email already registered', 409);

startSession();
$phone       = $d['phone'] ?? null;
$otpVerified = $_SESSION['otp_verified']       ?? false;
$otpPhone    = $_SESSION['otp_verified_phone'] ?? '';
$otpFor      = $_SESSION['otp_verified_for']   ?? '';

$phoneVerified = 0;
if ($phone && $otpVerified && $otpFor === 'register') {
    $phoneLast10 = substr(preg_replace('/\D/', '', $phone), -10);
    $otpLast10   = substr(preg_replace('/\D/', '', $otpPhone), -10);
    if ($phoneLast10 === $otpLast10) {
        $phoneVerified = 1;
    }
}

$d['password']       = password_hash($d['password'], PASSWORD_BCRYPT, ['cost' => 12]);
$d['phone_verified'] = $phoneVerified;

$user = $um->create($d);

unset($_SESSION['otp_verified'], $_SESSION['otp_verified_phone'], $_SESSION['otp_verified_for']);

unset($user['password']);
setCurrentUser($user);
jsonSuccess($user, 'Account created successfully', 201);
