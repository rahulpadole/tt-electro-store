<?php
declare(strict_types=1);

// Load .env file — only set vars not already in the process environment
$_envFile = dirname(__DIR__, 1) . '/.env';
if (file_exists($_envFile)) {
    foreach (file($_envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $_line) {
        if (str_starts_with(trim($_line), '#') || !str_contains($_line, '=')) continue;
        [$_k, $_v] = explode('=', $_line, 2);
        $_k = trim($_k);
        $_v = trim($_v);
        if (getenv($_k) === false) {
            putenv($_k . '=' . $_v);
            $_ENV[$_k] = $_v;
        }
    }
}

define('APP_NAME',        getenv('SITE_NAME') ?: 'TT Electro Store');
define('APP_VERSION',     '1.0.0');

// Auto-detect APP_URL if not set in .env
$appUrl = getenv('APP_URL') ?: getenv('SITE_URL');

if (!empty($appUrl)) {
    define('APP_URL', rtrim($appUrl, '/'));
} else {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('APP_URL', $scheme . '://' . $host);
}

define('GUEST_USER_ID',   1);
define('SESSION_NAME',    'tt_electro_session');
define('UPLOAD_DIR',      __DIR__ . '/../storage/uploads/');
define('UPLOAD_URL',      APP_URL . '/storage/uploads/');
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);
define('ALLOWED_IMG_TYPES', ['image/jpeg','image/png','image/webp','image/gif']);
define('ALLOWED_3D_TYPES',  ['application/octet-stream','model/stl','application/sla']);
define('ITEMS_PER_PAGE',  12);
define('ADMIN_EMAIL',     'admin@ttelectro.in');
define('DEFAULT_THEME',   'dark');
define('WHATSAPP_NUMBER', getenv('WHATSAPP_NUMBER') ?: '919876543210');
define('GST_RATE',        18);
define('SHIPPING_CHARGE', 49);
define('FREE_SHIPPING_ABOVE', 499);
