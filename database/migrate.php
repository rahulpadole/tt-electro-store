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

echo "[migrate] ✅ All migrations complete.\n";
