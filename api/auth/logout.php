<?php
declare(strict_types=1);
if ($method !== 'POST') jsonError('Method not allowed', 405);
logoutUser();
jsonSuccess(null, 'Logged out');
