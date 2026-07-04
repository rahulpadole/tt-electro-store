#!/usr/bin/env php
<?php
declare(strict_types=1);

// ── Bootstrap database connection ─────────────────────────────────────────
// Load .env if it exists
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        // Only set from .env if not already set in the process environment
        if (getenv($k) === false) {
            putenv($k . '=' . trim($v));
        }
    }
}

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'tt_electro_store';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

// On Replit dev, use socket if available
$socket = '/tmp/mysql.sock';

try {
    if (file_exists($socket)) {
        $dsn = "mysql:unix_socket={$socket};dbname={$name};charset=utf8mb4";
        $db  = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    } else {
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        $db  = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }
} catch (PDOException $e) {
    echo "[migrate] ✗ DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "[migrate] Connected to database '{$name}'\n";

// ── Helper: add column if it doesn't exist ────────────────────────────────
function addColumnIfMissing(PDO $db, string $table, string $column, string $definition): void {
    $st = $db->prepare(
        'SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
    );
    $st->execute([$table, $column]);
    if ((int)$st->fetchColumn() === 0) {
        $db->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
        echo "[migrate] + Added `{$table}`.`{$column}`\n";
    } else {
        echo "[migrate] ✓ `{$table}`.`{$column}` already exists\n";
    }
}

// ── Column migrations ─────────────────────────────────────────────────────
addColumnIfMissing($db, 'users', 'google_id',      "VARCHAR(255) DEFAULT NULL AFTER `email`");
addColumnIfMissing($db, 'users', 'phone_verified',  "TINYINT(1) NOT NULL DEFAULT 0 AFTER `phone`");
addColumnIfMissing($db, 'users', 'google_avatar',   "TEXT DEFAULT NULL AFTER `avatar`");

// ── Table migrations ──────────────────────────────────────────────────────
$db->exec("CREATE TABLE IF NOT EXISTS `otp_verifications` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `phone`       VARCHAR(20)      NOT NULL,
  `otp`         VARCHAR(10)      NOT NULL,
  `purpose`     ENUM('register','verify_phone','forgot_password') NOT NULL DEFAULT 'register',
  `is_verified` TINYINT(1)       NOT NULL DEFAULT 0,
  `attempts`    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `expires_at`  DATETIME         NOT NULL,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `otp_phone_idx`   (`phone`),
  INDEX `otp_expires_idx` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "[migrate] ✓ Table `otp_verifications` ready\n";

$db->exec("CREATE TABLE IF NOT EXISTS `password_resets` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `expires_at` DATETIME     NOT NULL,
  `used`       TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX       `pr_email_idx`    (`email`),
  UNIQUE KEY  `pr_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "[migrate] ✓ Table `password_resets` ready\n";

// ── Product extra flags ───────────────────────────────────────────────────
addColumnIfMissing($db, 'products', 'is_new_arrival', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_best_seller`");
addColumnIfMissing($db, 'products', 'is_offer',       "TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_new_arrival`");

// ── Order cancellation columns ────────────────────────────────────────────
addColumnIfMissing($db, 'orders', 'cancelled_at',         "DATETIME DEFAULT NULL AFTER `updated_at`");
addColumnIfMissing($db, 'orders', 'cancellation_reason',  "TEXT DEFAULT NULL AFTER `cancelled_at`");

// ── Razorpay columns ──────────────────────────────────────────────────────
addColumnIfMissing($db, 'orders', 'payment_status',      "ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending' AFTER `payment_method`");
addColumnIfMissing($db, 'orders', 'razorpay_order_id',   "VARCHAR(255) DEFAULT NULL AFTER `payment_status`");
addColumnIfMissing($db, 'orders', 'razorpay_payment_id', "VARCHAR(255) DEFAULT NULL AFTER `razorpay_order_id`");

// ── Delhivery shipping integration columns ────────────────────────────────
addColumnIfMissing($db, 'orders', 'delivery_partner',        "VARCHAR(50) DEFAULT NULL AFTER `razorpay_payment_id`");
addColumnIfMissing($db, 'orders', 'awb_number',               "VARCHAR(100) DEFAULT NULL AFTER `delivery_partner`");
addColumnIfMissing($db, 'orders', 'delivery_status',          "VARCHAR(100) DEFAULT NULL AFTER `awb_number`");
addColumnIfMissing($db, 'orders', 'expected_delivery_date',   "DATE DEFAULT NULL AFTER `delivery_status`");

// ── Helper: add index if it doesn't exist ─────────────────────────────────
function addIndexIfMissing(PDO $db, string $table, string $indexName, string $columns): void {
    $st = $db->prepare(
        'SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?'
    );
    $st->execute([$table, $indexName]);
    if ((int)$st->fetchColumn() === 0) {
        $db->exec("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` ({$columns})");
        echo "[migrate] + Added index `{$indexName}` on `{$table}`({$columns})\n";
    } else {
        echo "[migrate] ✓ Index `{$indexName}` on `{$table}` already exists\n";
    }
}

// ── Performance indexes (only ones not already covered by schema.sql) ─────
addIndexIfMissing($db, 'orders',       'orders_status_idx',         '`status`');
addIndexIfMissing($db, 'orders',       'orders_awb_idx',            '`awb_number`');
addIndexIfMissing($db, 'order_items',  'order_items_product_id_idx','`product_id`');
addIndexIfMissing($db, 'products',     'products_is_active_idx',    '`is_active`');
addIndexIfMissing($db, 'wishlist',     'wishlist_user_id_idx',      '`user_id`');
addIndexIfMissing($db, 'reviews',      'reviews_product_id_idx',    '`product_id`');

echo "[migrate] ✅ All migrations complete.\n";
