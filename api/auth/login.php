<?php
declare(strict_types=1);

if ($method !== 'POST') jsonError('Method not allowed', 405);

$d = getJsonBody();
$v = Validator::make($d)->required('email')->email('email')->required('password');
if ($v->fails()) jsonError('Validation failed', 422, $v->errors());

$um   = new UserModel();
$user = $um->findByEmail($d['email']);

if (!$user || !password_verify($d['password'], $user['password'])) {
    jsonError('Invalid email or password', 401);
}

if (!$user['is_active']) jsonError('Account is deactivated', 403);

unset($user['password']);
setCurrentUser($user);
jsonSuccess($user, 'Login successful');
