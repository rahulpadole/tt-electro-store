-- ============================================================
-- TT Electro Store — MySQL Schema
-- Compatible with MySQL 5.7+ / MariaDB 10.3+
-- Run once on a fresh database (or use schema.sql on Hostinger)
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+05:30';

-- ----------------------------------------------------------
-- 1. users
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(255)    NOT NULL,
  `email`          VARCHAR(255)    NOT NULL,
  `password`       VARCHAR(255)    NOT NULL,
  `phone`          VARCHAR(20)     DEFAULT NULL,
  `avatar`         TEXT            DEFAULT NULL,
  `role`           ENUM('user','admin','guest') NOT NULL DEFAULT 'user',
  `loyalty_points` INT             NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Guest user (id=1) — used for unauthenticated cart/wishlist/orders
INSERT INTO `users` (`id`,`name`,`email`,`password`,`role`,`is_active`) VALUES
  (1,'Guest','guest@ttelectro.in','','guest',0)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Default admin user: admin@ttelectro.in / Admin@123
-- Password hash generated with password_hash('Admin@123', PASSWORD_BCRYPT)
-- CHANGE THIS PASSWORD immediately after first login.
INSERT INTO `users` (`id`,`name`,`email`,`password`,`role`,`is_active`) VALUES
  (2,'Admin','admin@ttelectro.in','$2y$12$Xb/teBJPROG/1ApylHtS/u./DC6KhKmaqRj0dsfZmfbKTlQ5MdeoC','admin',1)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- ----------------------------------------------------------
-- 2. categories
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255)  NOT NULL,
  `slug`        VARCHAR(255)  NOT NULL,
  `description` TEXT          DEFAULT NULL,
  `image`       TEXT          DEFAULT NULL,
  `icon`        VARCHAR(100)  DEFAULT NULL,
  `created_at`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`name`,`slug`,`icon`) VALUES
  ('Microcontrollers','microcontrollers','🔧'),
  ('Sensors','sensors','📡'),
  ('Displays','displays','🖥️'),
  ('Power Modules','power-modules','⚡'),
  ('Communication','communication','📶'),
  ('Robotics','robotics','🤖'),
  ('3D Printing','3d-printing','🖨️'),
  ('Tools','tools','🔩'),
  ('Cables & Connectors','cables-connectors','🔌'),
  ('Kits','kits','📦')
ON DUPLICATE KEY UPDATE `name`=`name`;

-- ----------------------------------------------------------
-- 3. brands
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `brands` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`  VARCHAR(255) NOT NULL,
  `logo`  TEXT         DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `brands` (`name`) VALUES
  ('Arduino'),('Raspberry Pi'),('ESP32'),('Adafruit'),
  ('STMicroelectronics'),('Generic')
ON DUPLICATE KEY UPDATE `name`=`name`;

-- ----------------------------------------------------------
-- 4. products
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(255)    NOT NULL,
  `slug`             VARCHAR(255)    NOT NULL,
  `description`      TEXT            DEFAULT NULL,
  `price`            DECIMAL(10,2)   NOT NULL,
  `original_price`   DECIMAL(10,2)   DEFAULT NULL,
  `discount`         DECIMAL(5,2)    DEFAULT NULL,
  `stock`            INT             NOT NULL DEFAULT 0,
  `thumbnail`        TEXT            DEFAULT NULL,
  `images`           JSON            DEFAULT NULL,
  `tags`             JSON            DEFAULT NULL,
  `specifications`   JSON            DEFAULT NULL,
  `category_id`      INT UNSIGNED    DEFAULT NULL,
  `brand_id`         INT UNSIGNED    DEFAULT NULL,
  `is_featured`      TINYINT(1)      NOT NULL DEFAULT 0,
  `is_trending`      TINYINT(1)      NOT NULL DEFAULT 0,
  `is_best_seller`   TINYINT(1)      NOT NULL DEFAULT 0,
  `is_flash_sale`    TINYINT(1)      NOT NULL DEFAULT 0,
  `flash_sale_price` DECIMAL(10,2)   DEFAULT NULL,
  `flash_sale_ends`  DATETIME        DEFAULT NULL,
  `is_active`        TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id` (`category_id`),
  KEY `products_brand_id` (`brand_id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_products_brand`    FOREIGN KEY (`brand_id`)    REFERENCES `brands`(`id`)     ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 5. banners
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `banners` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`     VARCHAR(255) NOT NULL,
  `subtitle`  TEXT         DEFAULT NULL,
  `image`     TEXT         DEFAULT NULL,
  `link`      TEXT         DEFAULT NULL,
  `badge`     VARCHAR(100) DEFAULT NULL,
  `is_active` TINYINT(1)   NOT NULL DEFAULT 1,
  `position`  INT          NOT NULL DEFAULT 0,
  `created_at` DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `banners` (`title`,`subtitle`,`badge`,`is_active`,`position`) VALUES
  ('Power Your Next Idea','Premium electronics for makers and engineers in India','New Arrivals',1,1),
  ('DIY Vision Kits','Everything you need to build amazing projects','Featured',1,2),
  ('3D Printing Service','Upload your design, choose your material, we print & deliver','Get a Quote',1,3)
ON DUPLICATE KEY UPDATE `title`=`title`;

-- ----------------------------------------------------------
-- 6. reviews
-- ----------------------------------------------------------
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
  KEY `reviews_user_id` (`user_id`),
  CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 7. cart_items
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NOT NULL,
  `product_id`  INT UNSIGNED NOT NULL,
  `quantity`    INT          NOT NULL DEFAULT 1,
  `coupon_code` VARCHAR(50)  DEFAULT NULL,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_user_product` (`user_id`,`product_id`),
  KEY `cart_items_user_id` (`user_id`),
  CONSTRAINT `fk_cart_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 8. wishlist
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `added_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_user_product` (`user_id`,`product_id`),
  CONSTRAINT `fk_wishlist_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 9. orders
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id`               INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `user_id`          INT UNSIGNED   NOT NULL,
  `order_number`     VARCHAR(50)    NOT NULL,
  `status`           ENUM('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `subtotal`         DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  `discount`         DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  `shipping`         DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  `tax`              DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  `total`            DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  `shipping_address` JSON           DEFAULT NULL,
  `payment_method`   VARCHAR(50)    NOT NULL DEFAULT 'cod',
  `notes`            TEXT           DEFAULT NULL,
  `coupon_code`      VARCHAR(50)    DEFAULT NULL,
  `status_timeline`  JSON           DEFAULT NULL,
  `created_at`       DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id` (`user_id`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 10. order_items
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id`           INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `order_id`     INT UNSIGNED   NOT NULL,
  `product_id`   INT UNSIGNED   DEFAULT NULL,
  `product_name` VARCHAR(255)   NOT NULL,
  `thumbnail`    TEXT           DEFAULT NULL,
  `quantity`     INT            NOT NULL DEFAULT 1,
  `price`        DECIMAL(10,2)  NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id` (`order_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 11. coupons
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupons` (
  `id`               INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `code`             VARCHAR(50)    NOT NULL,
  `discount_type`    ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
  `discount`         DECIMAL(10,2)  NOT NULL,
  `min_order_amount` DECIMAL(10,2)  DEFAULT NULL,
  `max_discount`     DECIMAL(10,2)  DEFAULT NULL,
  `is_active`        TINYINT(1)     NOT NULL DEFAULT 1,
  `expires_at`       DATETIME       DEFAULT NULL,
  `created_at`       DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default coupons
INSERT INTO `coupons` (`code`,`discount_type`,`discount`,`min_order_amount`,`max_discount`,`is_active`) VALUES
  ('TTFIRST',  'percent', 10.00, 299.00,  500.00, 1),
  ('MAKER20',  'percent', 20.00, 999.00,  800.00, 1),
  ('FLAT150',  'fixed',  150.00, 599.00,    NULL, 1)
ON DUPLICATE KEY UPDATE `code`=`code`;

-- ----------------------------------------------------------
-- 12. blogs
-- ----------------------------------------------------------
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

-- ----------------------------------------------------------
-- 13. offers
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `offers` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)  NOT NULL,
  `description` TEXT          DEFAULT NULL,
  `type`        VARCHAR(50)   NOT NULL DEFAULT 'flash',
  `discount`    VARCHAR(50)   DEFAULT NULL,
  `ends_at`     DATETIME      DEFAULT NULL,
  `image`       TEXT          DEFAULT NULL,
  `badge`       VARCHAR(100)  DEFAULT NULL,
  `created_at`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 14. faq
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `faq` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question`   TEXT         NOT NULL,
  `answer`     TEXT         NOT NULL,
  `category`   VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `faq` (`question`,`answer`,`category`) VALUES
  ('Do you ship across India?','Yes! We deliver to all major cities and towns across India via trusted courier partners.','shipping'),
  ('How long does delivery take?','Standard delivery takes 5-7 business days. Express delivery (2-3 days) is available for select pin codes.','shipping'),
  ('Is Cash on Delivery available?','Yes, COD is available for orders up to ₹10,000. UPI, Net Banking, and Card payments are also accepted.','payment'),
  ('How do I track my order?','You can track your order from the "Track Order" page using your order number or registered email.','orders'),
  ('What is the return policy?','We accept returns within 7 days of delivery for defective or damaged products. Contact support to initiate a return.','returns'),
  ('Do you sell genuine products?','Absolutely! We source directly from authorized distributors and official brand channels only.','products')
ON DUPLICATE KEY UPDATE `question`=`question`;

-- ----------------------------------------------------------
-- 15. diy_kits
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `diy_kits` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255)  NOT NULL,
  `description` TEXT          DEFAULT NULL,
  `price`       DECIMAL(10,2) NOT NULL,
  `thumbnail`   TEXT          DEFAULT NULL,
  `images`      JSON          DEFAULT NULL,
  `components`  JSON          DEFAULT NULL,
  `pdf_url`     TEXT          DEFAULT NULL,
  `video_url`   TEXT          DEFAULT NULL,
  `difficulty`  ENUM('beginner','intermediate','advanced') DEFAULT 'beginner',
  `stock`       INT           NOT NULL DEFAULT 0,
  `created_at`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 16. print3d_requests
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `print3d_requests` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`         INT UNSIGNED  NOT NULL,
  `file_url`        TEXT          DEFAULT NULL,
  `image_url`       TEXT          DEFAULT NULL,
  `material`        VARCHAR(100)  NOT NULL,
  `quantity`        INT           NOT NULL DEFAULT 1,
  `description`     TEXT          DEFAULT NULL,
  `status`          ENUM('pending','reviewing','quoted','printing','done','cancelled') NOT NULL DEFAULT 'pending',
  `estimated_price` DECIMAL(10,2) DEFAULT NULL,
  `admin_note`      TEXT          DEFAULT NULL,
  `created_at`      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `print3d_user_id` (`user_id`),
  CONSTRAINT `fk_print3d_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 17. notifications
-- ----------------------------------------------------------
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

-- ----------------------------------------------------------
-- 18. newsletter
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(255) NOT NULL,
  `subscribed_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 19. contact_messages
-- ----------------------------------------------------------
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

-- ============================================================
-- Done! Tables + core seed data created.
-- Default admin login: admin@ttelectro.in / Admin@123
-- IMPORTANT: Change admin password after first login.
--
-- To load demo products, blogs, DIY kits and offers run:
--   database/seed.sql
-- ============================================================
