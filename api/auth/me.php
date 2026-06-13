<?php
declare(strict_types=1);
requireLogin();
$user = (new UserModel())->findById(getCurrentUserId());
if(!$user) jsonError('User not found',404);
jsonSuccess($user);
