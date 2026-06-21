<?php
declare(strict_types=1);

if ($method !== 'POST') jsonError('Method not allowed', 405);

$d        = getJsonBody();
$email    = strtolower(trim($d['email']    ?? ''));
$otp      = trim($d['otp']                ?? '');
$password = trim($d['password']           ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonError('Invalid email address', 422);
if (strlen($otp) !== 6 || !ctype_digit($otp))   jsonError('Enter the valid 6-digit OTP', 422);
if (strlen($password) < 8)                       jsonError('Password must be at least 8 characters', 422);

$um   = new UserModel();
$user = $um->findByEmail($email);

if (!$user) jsonError('Account not found', 404);

$phone = preg_replace('/[^0-9]/', '', $user['phone'] ?? '');
$phone = preg_replace('/^(91|0)/', '', $phone);

if (strlen($phone) !== 10) jsonError('No phone linked to this account', 422);

$om = new OtpModel();

if (!$om->verify($phone, $otp, 'forgot_password')) {
    jsonError('Invalid or expired OTP. Please try again.', 401);
}

$um->updatePassword((int)$user['id'], password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]));

jsonSuccess(['reset' => true], 'Password reset successfully. You can now sign in.');
