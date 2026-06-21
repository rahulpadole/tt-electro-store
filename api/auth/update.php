<?php
declare(strict_types=1);
requireLogin();
if ($method !== 'POST' && $method !== 'PATCH') jsonError('Method not allowed', 405);
$d    = getJsonBody();
$user = (new UserModel())->update(getCurrentUserId(), $d);
if (!$user) jsonError('Update failed', 500);
setCurrentUser($user);
jsonSuccess($user, 'Profile updated');
