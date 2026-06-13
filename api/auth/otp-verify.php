<?php
declare(strict_types=1);

if ($method !== 'POST') jsonError('Method not allowed', 405);

$d       = getJsonBody();
$phone   = trim($d['phone']   ?? '');
$otp     = trim($d['otp']     ?? '');
$purpose = trim($d['purpose'] ?? 'register');

$phone = preg_replace('/^\+91|^91/', '', preg_replace('/\D/', '', $phone));

if (strlen($phone) !== 10) jsonError('Invalid phone number', 422);
if (strlen($otp) !== 6)    jsonError('Enter the 6-digit OTP', 422);
if (!ctype_digit($otp))    jsonError('OTP must contain digits only', 422);

if (!in_array($purpose, ['register', 'verify_phone', 'forgot_password'], true)) {
    jsonError('Invalid purpose', 422);
}

$om = new OtpModel();

if (!$om->verify($phone, $otp, $purpose)) {
    jsonError('Invalid or expired OTP. Please try again.', 401);
}

startSession();
$_SESSION['otp_verified']       = true;
$_SESSION['otp_verified_phone'] = $phone;
$_SESSION['otp_verified_for']   = $purpose;

if ($purpose === 'verify_phone' && isLoggedIn()) {
    $uid = getCurrentUserId();
    $db  = Database::getConnection();
    $db->prepare('UPDATE users SET phone=?, phone_verified=1, updated_at=NOW() WHERE id=?')
       ->execute(['+91' . $phone, $uid]);

    $um   = new UserModel();
    $user = $um->findById($uid);
    unset($user['password']);
    $_SESSION['user'] = $user;
}

jsonSuccess(['verified' => true, 'phone' => $phone], 'OTP verified successfully');
