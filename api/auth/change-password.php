<?php
declare(strict_types=1);

requireLogin();
if ($method !== 'POST' && $method !== 'PATCH') jsonError('Method not allowed', 405);

$d           = getJsonBody();
$currentPw   = $d['current_password'] ?? '';
$newPw       = $d['new_password']     ?? '';
$confirmPw   = $d['confirm_password'] ?? '';

if (strlen($newPw) < 8)    jsonError('New password must be at least 8 characters', 422);
if ($newPw !== $confirmPw) jsonError('Passwords do not match', 422);

$userId = getCurrentUserId();
$db     = Database::getConnection();

$st = $db->prepare('SELECT password FROM users WHERE id=?');
$st->execute([$userId]);
$row = $st->fetch();

$hasPassword = !empty($row['password']);

if ($hasPassword) {
    if (empty($currentPw)) {
        jsonError('Current password is required', 422);
    }
    if (!password_verify($currentPw, $row['password'])) {
        jsonError('Current password is incorrect', 401);
    }
    if (password_verify($newPw, $row['password'])) {
        jsonError('New password must be different from current password', 422);
    }
}

$hash = password_hash($newPw, PASSWORD_BCRYPT, ['cost' => 12]);
$db->prepare('UPDATE users SET password=?, updated_at=NOW() WHERE id=?')
   ->execute([$hash, $userId]);

session_regenerate_id(true);

jsonSuccess([], 'Password changed successfully');
