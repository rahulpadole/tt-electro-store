<?php
declare(strict_types=1);
if ($method !== 'POST') jsonError('Method not allowed', 405);
$d = getJsonBody();
$email = strtolower(trim($d['email'] ?? ''));
if (!$email) jsonError('Email required', 422);
$um = new UserModel();
if ($um->findByEmail($email)) jsonError('Email already registered', 409);
jsonSuccess(['available' => true]);
