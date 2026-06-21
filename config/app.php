<?php
declare(strict_types=1);

// Load .env file if present
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
        putenv(trim($key) . '=' . trim($value));
    }
}

define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'TT Electro Store');
define('SITE_URL', rtrim($_ENV['SITE_URL'] ?? 'http://localhost', '/'));
define('SITE_TAGLINE', 'Premium Electronics for Makers & Engineers');
define('WHATSAPP_NUMBER', '919876543210');
define('CURRENCY_SYMBOL', '₹');
define('GST_RATE', 18);
define('SHIPPING_FREE_ABOVE', 999);
define('SHIPPING_CHARGE', 99);
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads');
define('UPLOAD_URL', SITE_URL . '/assets/uploads');
define('MAX_UPLOAD_BYTES', (int)($_ENV['MAX_UPLOAD_MB'] ?? 10) * 1024 * 1024);

// Session config
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Lax');
if (!session_id()) {
    session_start();
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Error display (disable on production)
if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

function csrf_token(): string {
    return $_SESSION['csrf_token'] ?? '';
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

function verify_csrf(): void {
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        die(json_encode(['success' => false, 'error' => 'Invalid CSRF token']));
    }
}

function e(mixed $val): string {
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function format_price(mixed $amount): string {
    return CURRENCY_SYMBOL . number_format((float)$amount, 2);
}

function time_ago(string $datetime): string {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
    if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
    return date('d M Y', $time);
}

function redirect(string $path): void {
    header('Location: ' . SITE_URL . $path);
    exit;
}

function asset(string $path): string {
    return SITE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string {
    return SITE_URL . '/' . ltrim($path, '/');
}

function flash(string $key, string $message = ''): string {
    if ($message !== '') {
        $_SESSION['flash'][$key] = $message;
        return '';
    }
    $msg = $_SESSION['flash'][$key] ?? '';
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function generate_order_number(): string {
    return 'TT' . strtoupper(substr(md5(uniqid()), 0, 8));
}
