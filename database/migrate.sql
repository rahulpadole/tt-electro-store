-- ============================================================
-- TT Electro Store — Migration v2 (idempotent)
-- Adds Google OAuth, phone verification, OTP, password-reset
-- Each ALTER is separate so IF NOT EXISTS works correctly
-- ============================================================
SET NAMES utf8mb4;

-- Add columns to users (one per statement for IF NOT EXISTS support)
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `google_id`      VARCHAR(255) DEFAULT NULL AFTER `email`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone_verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `phone`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `google_avatar`  TEXT DEFAULT NULL AFTER `avatar`;

-- OTP verifications table
CREATE TABLE IF NOT EXISTS `otp_verifications` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password resets table
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `expires_at` DATETIME     NOT NULL,
  `used`       TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX       `pr_email_idx`    (`email`),
  UNIQUE KEY  `pr_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
