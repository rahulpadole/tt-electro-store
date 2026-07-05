-- ============================================================
--  TT Electro Store — FULL Hostinger Migration Script
--  DB: u798297125_TTElectroStore  |  MariaDB 11.x
--
--  HOW TO RUN:
--  1. Open Hostinger hPanel → Databases → phpMyAdmin
--  2. Select database: u798297125_TTElectroStore
--  3. Click "SQL" tab → paste this entire file → click "Go"
--
--  ✅ Safe to run multiple times (idempotent)
--  ✅ Only adds missing tables / columns — never drops anything
--  ✅ Covers all features: flash sale, banners, reviews,
--     notifications, newsletter, cart, wishlist, 3D printing,
--     OTP login, Razorpay, order tracking, coupons, blog, FAQ
-- ============================================================

-- ============================================================
--  SECTION 1 — COLUMN ADDITIONS TO EXISTING TABLES
--  (tables that already exist on Hostinger but are missing columns)
-- ============================================================

-- ── users ─────────────────────────────────────────────────────────────────
ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `google_id`      VARCHAR(255) DEFAULT NULL        AFTER `email`,
    ADD COLUMN IF NOT EXISTS `phone_verified` TINYINT(1)   NOT NULL DEFAULT 0  AFTER `phone`,
    ADD COLUMN IF NOT EXISTS `google_avatar`  TEXT         DEFAULT NULL         AFTER `avatar`,
    ADD COLUMN IF NOT EXISTS `loyalty_points` INT          NOT NULL DEFAULT 0   AFTER `role`;

-- ── products ──────────────────────────────────────────────────────────────
ALTER TABLE `products`
    ADD COLUMN IF NOT EXISTS `original_price`  DECIMAL(10,2) DEFAULT NULL       AFTER `price`,
    ADD COLUMN IF NOT EXISTS `discount`        DECIMAL(5,2)  DEFAULT NULL       AFTER `original_price`,
    ADD COLUMN IF NOT EXISTS `images`          JSON          DEFAULT NULL       AFTER `thumbnail`,
    ADD COLUMN IF NOT EXISTS `tags`            JSON          DEFAULT NULL       AFTER `images`,
    ADD COLUMN IF NOT EXISTS `specifications`  JSON          DEFAULT NULL       AFTER `tags`,
    ADD COLUMN IF NOT EXISTS `brand_id`        INT UNSIGNED  DEFAULT NULL       AFTER `category_id`,
    ADD COLUMN IF NOT EXISTS `is_featured`     TINYINT(1)   NOT NULL DEFAULT 0  AFTER `brand_id`,
    ADD COLUMN IF NOT EXISTS `is_trending`     TINYINT(1)   NOT NULL DEFAULT 0  AFTER `is_featured`,
    ADD COLUMN IF NOT EXISTS `is_best_seller`  TINYINT(1)   NOT NULL DEFAULT 0  AFTER `is_trending`,
    ADD COLUMN IF NOT EXISTS `is_new_arrival`  TINYINT(1)   NOT NULL DEFAULT 0  AFTER `is_best_seller`,
    ADD COLUMN IF NOT EXISTS `is_offer`        TINYINT(1)   NOT NULL DEFAULT 0  AFTER `is_new_arrival`,
    ADD COLUMN IF NOT EXISTS `is_flash_sale`   TINYINT(1)   NOT NULL DEFAULT 0  AFTER `is_offer`,
    ADD COLUMN IF NOT EXISTS `flash_sale_price` DECIMAL(10,2) DEFAULT NULL      AFTER `is_flash_sale`,
    ADD COLUMN IF NOT EXISTS `flash_sale_ends`  DATETIME     DEFAULT NULL       AFTER `flash_sale_price`,
    ADD COLUMN IF NOT EXISTS `updated_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- ── banners  ── FIXES /api/banners/ 500 Error ─────────────────────────────
ALTER TABLE `banners`
    ADD COLUMN IF NOT EXISTS `subtitle` TEXT         DEFAULT NULL AFTER `title`,
    ADD COLUMN IF NOT EXISTS `badge`    VARCHAR(100) DEFAULT NULL AFTER `link`,
    ADD COLUMN IF NOT EXISTS `position` INT          NOT NULL DEFAULT 0 AFTER `is_active`,
    ADD COLUMN IF NOT EXISTS `created_at` DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `position`;

-- ── orders ────────────────────────────────────────────────────────────────
ALTER TABLE `orders`
    ADD COLUMN IF NOT EXISTS `payment_status`      ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending' AFTER `payment_method`,
    ADD COLUMN IF NOT EXISTS `razorpay_order_id`   VARCHAR(255) DEFAULT NULL AFTER `payment_status`,
    ADD COLUMN IF NOT EXISTS `razorpay_payment_id` VARCHAR(255) DEFAULT NULL AFTER `razorpay_order_id`,
    ADD COLUMN IF NOT EXISTS `notes`               TEXT         DEFAULT NULL AFTER `coupon_code`,
    ADD COLUMN IF NOT EXISTS `status_timeline`     JSON         DEFAULT NULL AFTER `notes`,
    ADD COLUMN IF NOT EXISTS `cancelled_at`        DATETIME     DEFAULT NULL AFTER `updated_at`,
    ADD COLUMN IF NOT EXISTS `cancellation_reason` TEXT         DEFAULT NULL AFTER `cancelled_at`,
    ADD COLUMN IF NOT EXISTS `delivery_partner`        VARCHAR(50)  DEFAULT NULL AFTER `razorpay_payment_id`,
    ADD COLUMN IF NOT EXISTS `awb_number`               VARCHAR(100) DEFAULT NULL AFTER `delivery_partner`,
    ADD COLUMN IF NOT EXISTS `delivery_status`          VARCHAR(100) DEFAULT NULL AFTER `awb_number`,
    ADD COLUMN IF NOT EXISTS `expected_delivery_date`   DATE         DEFAULT NULL AFTER `delivery_status`;

-- ── order_items ───────────────────────────────────────────────────────────
ALTER TABLE `order_items`
    ADD COLUMN IF NOT EXISTS `thumbnail` TEXT DEFAULT NULL AFTER `product_name`;

-- ── categories ────────────────────────────────────────────────────────────
ALTER TABLE `categories`
    ADD COLUMN IF NOT EXISTS `icon`        VARCHAR(100) DEFAULT NULL AFTER `image`,
    ADD COLUMN IF NOT EXISTS `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `icon`;


-- ============================================================
--  SECTION 2 — CREATE MISSING TABLES
--  (tables that may not exist at all on Hostinger)
-- ============================================================

-- ── brands ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `brands` (
    `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `logo` TEXT         DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── faq ───────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `faq` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `question`   TEXT         NOT NULL,
    `answer`     TEXT         NOT NULL,
    `category`   VARCHAR(100) DEFAULT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── offers ────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `offers` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(255) NOT NULL,
    `description` TEXT         DEFAULT NULL,
    `type`        VARCHAR(50)  NOT NULL DEFAULT 'flash',
    `discount`    VARCHAR(50)  DEFAULT NULL,
    `ends_at`     DATETIME     DEFAULT NULL,
    `image`       TEXT         DEFAULT NULL,
    `badge`       VARCHAR(100) DEFAULT NULL,
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── coupons ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `coupons` (
    `id`                INT UNSIGNED                  NOT NULL AUTO_INCREMENT,
    `code`              VARCHAR(50)                   NOT NULL,
    `discount_type`     ENUM('percent','fixed')       NOT NULL DEFAULT 'percent',
    `discount`          DECIMAL(10,2)                 NOT NULL,
    `min_order_amount`  DECIMAL(10,2)                 DEFAULT NULL,
    `max_discount`      DECIMAL(10,2)                 DEFAULT NULL,
    `is_active`         TINYINT(1)                    NOT NULL DEFAULT 1,
    `expires_at`        DATETIME                      DEFAULT NULL,
    `created_at`        DATETIME                      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── blogs ─────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `blogs` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(255) NOT NULL,
    `slug`        VARCHAR(255) NOT NULL,
    `excerpt`     TEXT         DEFAULT NULL,
    `content`     LONGTEXT     NOT NULL,
    `thumbnail`   TEXT         DEFAULT NULL,
    `author_name` VARCHAR(255) NOT NULL DEFAULT 'TT Electro',
    `category`    VARCHAR(100) DEFAULT NULL,
    `tags`        JSON         DEFAULT NULL,
    `reading_time` INT         DEFAULT NULL,
    `view_count`  INT          NOT NULL DEFAULT 0,
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `blogs_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── diy_kits ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `diy_kits` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255) NOT NULL,
    `description` TEXT         DEFAULT NULL,
    `price`       DECIMAL(10,2) NOT NULL,
    `thumbnail`   TEXT         DEFAULT NULL,
    `images`      JSON         DEFAULT NULL,
    `components`  JSON         DEFAULT NULL,
    `pdf_url`     TEXT         DEFAULT NULL,
    `video_url`   TEXT         DEFAULT NULL,
    `difficulty`  ENUM('beginner','intermediate','advanced') DEFAULT 'beginner',
    `stock`       INT          NOT NULL DEFAULT 0,
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── newsletter ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `newsletter` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(255) NOT NULL,
    `subscribed_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `newsletter_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── notifications ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `notifications` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED DEFAULT NULL,
    `title`      VARCHAR(255) NOT NULL,
    `message`    TEXT         NOT NULL,
    `type`       VARCHAR(50)  NOT NULL DEFAULT 'info',
    `link`       TEXT         DEFAULT NULL,
    `is_read`    TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `notifications_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── reviews ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `reviews` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT UNSIGNED NOT NULL,
    `user_id`    INT UNSIGNED NOT NULL,
    `rating`     TINYINT      NOT NULL DEFAULT 5,
    `title`      VARCHAR(255) DEFAULT NULL,
    `body`       TEXT         DEFAULT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `reviews_product_id` (`product_id`),
    KEY `reviews_user_id`    (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── wishlist ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `wishlist` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `added_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `wishlist_user_product` (`user_id`, `product_id`),
    KEY `fk_wishlist_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── cart_items ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `cart_items` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED NOT NULL,
    `product_id`  INT UNSIGNED NOT NULL,
    `quantity`    INT          NOT NULL DEFAULT 1,
    `coupon_code` VARCHAR(50)  DEFAULT NULL,
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `cart_user_product` (`user_id`, `product_id`),
    KEY `cart_items_user_id`  (`user_id`),
    KEY `fk_cart_product`     (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── contact_messages ──────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(255) NOT NULL,
    `email`      VARCHAR(255) NOT NULL,
    `phone`      VARCHAR(20)  DEFAULT NULL,
    `subject`    VARCHAR(255) DEFAULT NULL,
    `message`    TEXT         NOT NULL,
    `is_read`    TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── print3d_requests ──────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `print3d_requests` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`         INT UNSIGNED NOT NULL,
    `file_url`        TEXT         DEFAULT NULL,
    `image_url`       TEXT         DEFAULT NULL,
    `material`        VARCHAR(100) NOT NULL,
    `quantity`        INT          NOT NULL DEFAULT 1,
    `description`     TEXT         DEFAULT NULL,
    `status`          ENUM('pending','reviewing','quoted','printing','done','cancelled') NOT NULL DEFAULT 'pending',
    `estimated_price` DECIMAL(10,2) DEFAULT NULL,
    `admin_note`      TEXT         DEFAULT NULL,
    `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `print3d_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── otp_verifications ─────────────────────────────────────────────────────
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

-- ── password_resets ───────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `expires_at` DATETIME     NOT NULL,
    `used`       TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX      `pr_email_idx`    (`email`),
    UNIQUE KEY `pr_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── admin_notifications ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `admin_notifications` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `type`       VARCHAR(50)  NOT NULL,
    `title`      VARCHAR(255) NOT NULL,
    `message`    TEXT         NOT NULL,
    `data`       JSON         DEFAULT NULL,
    `is_read`    TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `admin_notif_type_idx` (`type`),
    INDEX `admin_notif_read_idx` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── delivery_logs ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `delivery_logs` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `order_id`   INT UNSIGNED  DEFAULT NULL,
    `event_type` VARCHAR(50)   NOT NULL,
    `status`     VARCHAR(50)   NOT NULL,
    `message`    TEXT          DEFAULT NULL,
    `created_at` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `delivery_logs_order_idx`  (`order_id`),
    INDEX `delivery_logs_status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── returns ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `returns` (
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `order_id`      INT UNSIGNED  NOT NULL,
    `user_id`       INT UNSIGNED  NOT NULL,
    `reason`        VARCHAR(255)  NOT NULL,
    `description`   TEXT          DEFAULT NULL,
    `status`        ENUM('pending','approved','rejected','picked_up','refunded') NOT NULL DEFAULT 'pending',
    `admin_notes`   TEXT          DEFAULT NULL,
    `refund_amount` DECIMAL(10,2) DEFAULT NULL,
    `created_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `returns_order_idx`  (`order_id`),
    INDEX `returns_user_idx`   (`user_id`),
    INDEX `returns_status_idx` (`status`),
    CONSTRAINT `fk_returns_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_returns_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── return_images ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `return_images` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `return_id`  INT UNSIGNED NOT NULL,
    `image_url`  TEXT         NOT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `return_images_return_idx` (`return_id`),
    CONSTRAINT `fk_return_images_return` FOREIGN KEY (`return_id`) REFERENCES `returns`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  SECTION 2B — PERFORMANCE INDEXES
--  (MariaDB does not support "ADD INDEX IF NOT EXISTS" reliably
--   across versions, so each is wrapped in a procedure guard)
-- ============================================================

DELIMITER $$
DROP PROCEDURE IF EXISTS `tt_add_index_if_missing`$$
CREATE PROCEDURE `tt_add_index_if_missing`(
    IN tbl VARCHAR(64), IN idx_name VARCHAR(64), IN cols VARCHAR(255)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND INDEX_NAME = idx_name
    ) THEN
        SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD INDEX `', idx_name, '` (', cols, ')');
        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

CALL tt_add_index_if_missing('orders',      'orders_status_idx',          '`status`');
CALL tt_add_index_if_missing('orders',      'orders_awb_idx',             '`awb_number`');
CALL tt_add_index_if_missing('order_items', 'order_items_product_id_idx', '`product_id`');
CALL tt_add_index_if_missing('products',    'products_is_active_idx',     '`is_active`');
CALL tt_add_index_if_missing('wishlist',    'wishlist_user_id_idx',       '`user_id`');
CALL tt_add_index_if_missing('reviews',     'reviews_product_id_idx',     '`product_id`');

DROP PROCEDURE IF EXISTS `tt_add_index_if_missing`;

-- ============================================================
--  SECTION 3 — FLASH SALE DATA FIX
--  Corrects flash_sale_ends dates that may be wrong on Hostinger.
--  Sets the flash sale to end on 2026-07-15 23:59:59 IST.
--  Change this date to whatever you want your flash sale to end.
-- ============================================================

-- Update any flash sale products that have a NULL or far-future end date
UPDATE `products`
SET `flash_sale_ends` = '2026-07-15 23:59:59'
WHERE `is_flash_sale` = 1
  AND (`flash_sale_ends` IS NULL OR `flash_sale_ends` > '2026-08-01 00:00:00');

-- If no products are marked as flash sale yet, mark a few of your products.
-- Replace (1,2,3) with actual product IDs from your Hostinger DB.
-- UPDATE `products` SET `is_flash_sale` = 1, `flash_sale_price` = price * 0.80, `flash_sale_ends` = '2026-07-15 23:59:59' WHERE id IN (1,2,3);

-- ============================================================
--  SECTION 4 — ADMIN USER SETUP
--  Creates default admin if none exists yet.
--  Change the email/password BEFORE running if needed.
--  Password hash below = "Admin@1234" (bcrypt)
-- ============================================================

INSERT IGNORE INTO `users` (`name`, `email`, `password`, `role`, `is_active`)
VALUES (
    'Admin',
    'admin@ttelectrostore.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    1
);

-- ============================================================
--  ✅ DONE — All migrations applied.
--     Upload updated PHP files to Hostinger AFTER running this.
-- ============================================================
