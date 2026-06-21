-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 12, 2026 at 04:37 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u798297125_TTElectroStore`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `badge`, `is_active`, `position`, `created_at`) VALUES
(1, 'Summer Sale', 'Premium electronics for makers and engineers in India', 'banner1.jpg', NULL, 'New Arrivals', 1, 1, '2026-06-11 06:47:43'),
(2, 'DIY Vision Kits', 'Everything you need to build amazing projects', NULL, NULL, 'Featured', 1, 2, '2026-06-11 06:47:43'),
(3, '3D Printing Service', 'Upload your design, choose your material, we print & deliver', NULL, NULL, 'Get a Quote', 1, 3, '2026-06-11 06:47:43'),
(4, 'Power Your Next Idea', 'Premium electronics for makers and engineers in India', NULL, NULL, 'New Arrivals', 1, 1, '2026-06-12 01:00:08'),
(5, 'DIY Vision Kits', 'Everything you need to build amazing projects', NULL, NULL, 'Featured', 1, 2, '2026-06-12 01:00:08'),
(6, '3D Printing Service', 'Upload your design, choose your material, we print & deliver', NULL, NULL, 'Get a Quote', 1, 3, '2026-06-12 01:00:08'),
(7, 'Power Your Next Idea', 'Premium electronics for makers and engineers in India', NULL, NULL, 'New Arrivals', 1, 1, '2026-06-12 01:23:08'),
(8, 'DIY Vision Kits', 'Everything you need to build amazing projects', NULL, NULL, 'Featured', 1, 2, '2026-06-12 01:23:08'),
(9, '3D Printing Service', 'Upload your design, choose your material, we print & deliver', NULL, NULL, 'Get a Quote', 1, 3, '2026-06-12 01:23:08'),
(10, 'Power Your Next Idea', 'Premium electronics for makers and engineers in India', NULL, NULL, 'New Arrivals', 1, 1, '2026-06-12 01:23:28'),
(11, 'DIY Vision Kits', 'Everything you need to build amazing projects', NULL, NULL, 'Featured', 1, 2, '2026-06-12 01:23:28'),
(12, '3D Printing Service', 'Upload your design, choose your material, we print & deliver', NULL, NULL, 'Get a Quote', 1, 3, '2026-06-12 01:23:28');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `thumbnail` text DEFAULT NULL,
  `author_name` varchar(255) NOT NULL DEFAULT 'TT Electro',
  `category` varchar(100) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `reading_time` int(11) DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `excerpt`, `content`, `thumbnail`, `author_name`, `category`, `tags`, `reading_time`, `view_count`, `created_at`, `updated_at`) VALUES
(1, 'Getting Started', 'arduino-guide', 'Beginner Arduino guide', 'Full content here', 'https://picsum.photos/seed/b1/600/400', 'Ravi Menon', 'Tutorials', NULL, 8, 1200, '2026-06-11 04:04:55', '2026-06-11 19:47:05'),
(2, 'Top 5 Sensors Every Maker Should Own in 2026', 'top-5-sensors-every-maker-should-own-2026', 'The right sensor makes all the difference. We picked the 5 most versatile, affordable sensors available in India — with code examples and project ideas for each.', '<h2>1. DHT22 — Temperature & Humidity</h2><p>More accurate than DHT11 (±0.5°C vs ±2°C), calibrated from factory, single GPIO pin. At ₹199 it is the best climate sensor per rupee. Use in weather stations, plant monitors, and HVAC control.</p><h2>2. HC-SR04 — Ultrasonic Distance</h2><p>2 cm to 4 m range, 3 mm accuracy. Trigger a 10 µs pulse, divide echo time by 58 for centimetres. Used in parking sensors, bin-level monitors, and robot obstacle detection. Under ₹100.</p><h2>3. MPU-6050 — 6-Axis IMU</h2><p>Accelerometer + gyroscope in one chip with built-in DMP for pitch/roll/yaw. Essential for drones, balancing robots, and motion-controlled displays.</p><h2>4. MQ-135 — Air Quality</h2><p>Detects CO2, NH3, benzene, and smoke via an analog output. Combine with an ESP32 and 0.96\" OLED for a pocket air quality badge.</p><h2>5. Soil Moisture Sensor</h2><p>Resistive probes measure conductivity of soil. Pair with a relay and solenoid valve for an automatic irrigation system. Perfect for balconies and greenhouses.</p><h2>Where to Buy</h2><p>All five are available individually or in our <strong>Arduino Ultimate Starter Kit</strong> with free shipping above ₹499.</p>', 'https://picsum.photos/seed/sensors-blog-post/1200/630', 'Priya Nair', 'Buying Guide', '[\"sensors\",\"dht22\",\"hcsr04\",\"mpu6050\",\"mq135\",\"maker\",\"buying-guide\"]', 5, 487, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(3, 'How to Design and Print Your First 3D Part: A Beginner Guide', 'design-print-first-3d-part-beginner-guide', 'Going from idea to physical object in a few hours is one of the most satisfying experiences in making. This guide covers the full workflow — FreeCAD to Cura to our 3D printing service.', '<h2>Step 1 — Design Your Part</h2><p>Use <strong>FreeCAD</strong> (free) or <strong>Fusion 360</strong> (free for hobbyists). Model your part and export as .STL. Key FDM rules: minimum wall 1.2 mm, avoid overhangs beyond 45° without supports, add 0.2 mm clearance between mating parts.</p><h2>Step 2 — Choose Material</h2><p><strong>PLA</strong> — easiest to print, ideal for enclosures indoors below 50°C. <strong>PETG</strong> — moisture and chemical resistant, good for outdoor use. <strong>ABS</strong> — high-temperature tolerance but warps easily, needs an enclosure.</p><h2>Step 3 — Slice in Cura</h2><p>Open your STL in Ultimaker Cura. Recommended PLA settings: layer height 0.2 mm, infill 15% (display) or 40% (structural), print speed 50 mm/s, supports touching build plate only.</p><h2>Step 4 — Use Our Service</h2><p>No printer? Upload your STL on TT Electro\'s 3D Printing page, choose material, quantity, and colour. Quote within 4 hours. Lead time 2–3 business days.</p>', 'https://picsum.photos/seed/3dprinting-blog-post/1200/630', 'Rohan Mehta', 'Tutorial', '[\"3d-printing\",\"freecad\",\"cura\",\"pla\",\"petg\",\"beginner\",\"guide\"]', 8, 218, '2026-06-12 01:31:24', '2026-06-12 01:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`) VALUES
(1, 'Arduino', NULL),
(2, 'Raspberry Pi', NULL),
(3, 'ESP32', NULL),
(4, 'Adafruit', NULL),
(5, 'STMicroelectronics', NULL),
(6, 'Generic', NULL),
(7, 'Arduino', 'https://picsum.photos/seed/arduino/120/40'),
(8, 'Raspberry Pi', 'https://picsum.photos/seed/rpi/120/40'),
(9, 'Espressif', 'https://picsum.photos/seed/esp/120/40'),
(10, 'DF Robot', 'https://picsum.photos/seed/df/120/40'),
(11, 'Adafruit', 'https://picsum.photos/seed/ada/120/40'),
(12, 'Bosch', 'https://picsum.photos/seed/bosch/120/40'),
(13, 'Texas Instruments', 'https://picsum.photos/seed/ti/120/40'),
(14, 'TT Electro', 'https://picsum.photos/seed/tt/120/40'),
(15, 'Arduino', 'https://picsum.photos/seed/arduino/120/40'),
(16, 'Raspberry Pi', 'https://picsum.photos/seed/rpi/120/40'),
(17, 'Espressif', 'https://picsum.photos/seed/esp/120/40'),
(18, 'Arduino', NULL),
(19, 'Raspberry Pi', NULL),
(20, 'ESP32', NULL),
(21, 'Adafruit', NULL),
(22, 'STMicroelectronics', NULL),
(23, 'Generic', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `coupon_code` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `icon`, `created_at`) VALUES
(1, 'Microcontrollers', 'arduino', 'Arduino boards', 'https://picsum.photos/seed/mcu/400/300', 'Cpu', '2026-06-11 06:47:43'),
(2, 'Sensors', 'sensors', NULL, NULL, '📡', '2026-06-11 06:47:43'),
(3, 'Displays', 'displays', NULL, NULL, '🖥️', '2026-06-11 06:47:43'),
(4, 'Power Modules', 'power-modules', NULL, NULL, '⚡', '2026-06-11 06:47:43'),
(5, 'Communication', 'communication', NULL, NULL, '📶', '2026-06-11 06:47:43'),
(6, 'Robotics', 'robotics', NULL, NULL, '🤖', '2026-06-11 06:47:43'),
(7, '3D Printing', '3d-printing', NULL, NULL, '🖨️', '2026-06-11 06:47:43'),
(8, 'Tools', 'tools', NULL, NULL, '🔩', '2026-06-11 06:47:43'),
(9, 'Cables & Connectors', 'cables-connectors', NULL, NULL, '🔌', '2026-06-11 06:47:43'),
(10, 'Kits', 'kits', NULL, NULL, '📦', '2026-06-11 06:47:43'),
(11, 'Sensors', 'sensors-modules', 'Sensors & modules', 'https://picsum.photos/seed/sensor/400/300', 'Thermometer', '2026-06-11 04:04:55'),
(12, 'Development Boards', 'development-boards', 'Raspberry Pi etc', 'https://picsum.photos/seed/dev/400/300', 'Monitor', '2026-06-11 04:04:55'),
(14, 'Microcontrollers', 'microcontrollers', NULL, NULL, '🔧', '2026-06-12 01:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `discount` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount`, `min_order_amount`, `max_discount`, `is_active`, `expires_at`, `created_at`) VALUES
(1, 'TTFIRST', 'percent', 10.00, 0.00, 200.00, 1, '2027-06-11 04:04:55', '2026-06-11 06:47:43'),
(2, 'MAKER20', 'percent', 20.00, 999.00, 500.00, 1, '2026-09-09 04:04:55', '2026-06-11 06:47:43'),
(3, 'FLAT150', 'fixed', 150.00, 599.00, NULL, 1, NULL, '2026-06-11 06:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `diy_kits`
--

CREATE TABLE `diy_kits` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `thumbnail` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `components` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`components`)),
  `pdf_url` text DEFAULT NULL,
  `video_url` text DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diy_kits`
--

INSERT INTO `diy_kits` (`id`, `name`, `description`, `price`, `thumbnail`, `images`, `components`, `pdf_url`, `video_url`, `difficulty`, `stock`, `created_at`) VALUES
(1, 'Arduino IoT Weather Station Kit', 'Build a cloud-connected IoT weather station that logs temperature, humidity, and air pressure. Displays live data on an OLED screen and sends WhatsApp alerts. Step-by-step video tutorial and custom PCB included.', 1299.00, 'https://picsum.photos/seed/iot-weather-kit/600/600', '[\"https://picsum.photos/seed/iot-weather-kit/600/600\",\"https://picsum.photos/seed/iot-weather-kit-b/600/600\"]', '[{\"name\":\"ESP32 DevKit V1\",\"qty\":1},{\"name\":\"DHT22 Sensor\",\"qty\":1},{\"name\":\"BMP280 Pressure Sensor\",\"qty\":1},{\"name\":\"0.96 inch OLED Display\",\"qty\":1},{\"name\":\"830-pt Breadboard\",\"qty\":1},{\"name\":\"Jumper Wires 40pcs\",\"qty\":1},{\"name\":\"USB-C Cable\",\"qty\":1},{\"name\":\"Custom PCB\",\"qty\":1},{\"name\":\"3D Printed Enclosure\",\"qty\":1},{\"name\":\"Project PDF Guide\",\"qty\":1}]', NULL, NULL, 'beginner', 40, '2026-06-12 01:31:24'),
(2, 'Raspberry Pi Security Camera Kit', 'Build a smart security camera with Pi Pico W + OV2640. Streams live video over WiFi. Motion detection sends a Telegram alert with snapshot. Includes 3D-printed dome and complete Python source code.', 2499.00, 'https://picsum.photos/seed/security-camera-kit/600/600', '[\"https://picsum.photos/seed/security-camera-kit/600/600\",\"https://picsum.photos/seed/security-camera-kit-b/600/600\"]', '[{\"name\":\"Raspberry Pi Pico W\",\"qty\":1},{\"name\":\"OV2640 Camera Module\",\"qty\":1},{\"name\":\"PIR Motion Sensor\",\"qty\":1},{\"name\":\"IR LED Board 850nm\",\"qty\":1},{\"name\":\"5V 3A USB-C PSU\",\"qty\":1},{\"name\":\"MicroSD Card 16GB\",\"qty\":1},{\"name\":\"3D Printed Dome Enclosure\",\"qty\":1},{\"name\":\"M2 Mounting Hardware\",\"qty\":1},{\"name\":\"Python Source Code USB\",\"qty\":1}]', NULL, NULL, 'intermediate', 25, '2026-06-12 01:31:24'),
(3, 'Line-Following Robot Car Kit', 'Build a robot car that autonomously follows a black line using 5-sensor IR array + PID control. Includes assembled chassis, motor driver, OLED, and pre-written Arduino code. Expand with Bluetooth control.', 1799.00, 'https://picsum.photos/seed/line-follower-robot-kit/600/600', '[\"https://picsum.photos/seed/line-follower-robot-kit/600/600\",\"https://picsum.photos/seed/line-follower-robot-kit-b/600/600\"]', '[{\"name\":\"Arduino Nano Every\",\"qty\":1},{\"name\":\"L298N Motor Driver\",\"qty\":1},{\"name\":\"2WD Robot Chassis\",\"qty\":1},{\"name\":\"5-Channel IR Sensor Array\",\"qty\":1},{\"name\":\"HC-05 Bluetooth Module\",\"qty\":1},{\"name\":\"0.96 inch OLED Display\",\"qty\":1},{\"name\":\"18650 Battery Holder 2-cell\",\"qty\":1},{\"name\":\"18650 Li-Ion Batteries\",\"qty\":2},{\"name\":\"Track Sheet A0\",\"qty\":1},{\"name\":\"Code + App APK\",\"qty\":1}]', NULL, NULL, 'intermediate', 30, '2026-06-12 01:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(10) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `category`, `created_at`) VALUES
(1, 'Warranty?', '1 year warranty', 'shipping', '2026-06-11 06:47:43'),
(2, 'How long does delivery take?', 'Standard delivery takes 5-7 business days. Express delivery (2-3 days) is available for select pin codes.', 'shipping', '2026-06-11 06:47:43'),
(3, 'Is Cash on Delivery available?', 'Yes, COD is available for orders up to ₹10,000. UPI, Net Banking, and Card payments are also accepted.', 'payment', '2026-06-11 06:47:43'),
(4, 'How do I track my order?', 'You can track your order from the \"Track Order\" page using your order number or registered email.', 'orders', '2026-06-11 06:47:43'),
(5, 'What is the return policy?', 'We accept returns within 7 days of delivery for defective or damaged products. Contact support to initiate a return.', 'returns', '2026-06-11 06:47:43'),
(6, 'Do you sell genuine products?', 'Absolutely! We source directly from authorized distributors and official brand channels only.', 'products', '2026-06-11 06:47:43'),
(7, 'Do you ship all over India?', 'Yes we ship across India in 2-5 days', 'Shipping', '2026-06-11 04:04:55'),
(8, 'Is COD available?', 'Yes COD available up to Rs.5000', 'Payment', '2026-06-11 04:04:55'),
(9, 'Do you ship across India?', 'Yes! We deliver to all major cities and towns across India via trusted courier partners.', 'shipping', '2026-06-12 01:23:08'),
(10, 'How long does delivery take?', 'Standard delivery takes 5-7 business days. Express delivery (2-3 days) is available for select pin codes.', 'shipping', '2026-06-12 01:23:08'),
(11, 'Is Cash on Delivery available?', 'Yes, COD is available for orders up to ₹10,000. UPI, Net Banking, and Card payments are also accepted.', 'payment', '2026-06-12 01:23:08'),
(12, 'How do I track my order?', 'You can track your order from the \"Track Order\" page using your order number or registered email.', 'orders', '2026-06-12 01:23:08'),
(13, 'What is the return policy?', 'We accept returns within 7 days of delivery for defective or damaged products. Contact support to initiate a return.', 'returns', '2026-06-12 01:23:08'),
(14, 'Do you sell genuine products?', 'Absolutely! We source directly from authorized distributors and official brand channels only.', 'products', '2026-06-12 01:23:08'),
(15, 'Do you ship across India?', 'Yes! We deliver to all major cities and towns across India via trusted courier partners like DTDC, BlueDart, and Ekart.', 'Shipping', '2026-06-12 01:31:24'),
(16, 'How long does delivery take?', 'Standard delivery takes 5–7 business days. Express delivery (2–3 days) is available for select pin codes. You will receive a tracking link once your order is dispatched.', 'Shipping', '2026-06-12 01:31:24'),
(17, 'Is free shipping available?', 'Yes! Orders above ₹499 qualify for free standard shipping anywhere in India.', 'Shipping', '2026-06-12 01:31:24'),
(18, 'Is Cash on Delivery available?', 'Yes, COD is available for orders up to ₹10,000. UPI, Net Banking, and Card payments are also accepted at checkout.', 'Payment', '2026-06-12 01:31:24'),
(19, 'Which UPI apps do you accept?', 'We accept all major UPI apps — Google Pay, PhonePe, Paytm, BHIM, and any bank UPI ID.', 'Payment', '2026-06-12 01:31:24'),
(20, 'How do I track my order?', 'Go to the \"Track Order\" page and enter your order number (starts with TTE-). You can also track from your account dashboard when logged in.', 'Orders', '2026-06-12 01:31:24'),
(21, 'Can I cancel or modify my order?', 'Orders can be cancelled within 2 hours of placing them. After dispatch, cancellation is not possible, but you may initiate a return after delivery.', 'Orders', '2026-06-12 01:31:24'),
(22, 'What is the return policy?', 'We accept returns within 7 days of delivery for defective or damaged products. Contact support with your order number and photos of the issue.', 'Returns', '2026-06-12 01:31:24'),
(23, 'How long do refunds take?', 'Refunds are processed within 5–7 business days after we receive and verify the returned item.', 'Returns', '2026-06-12 01:31:24'),
(24, 'Do you sell genuine products?', 'Absolutely! We source directly from authorised distributors and official brand channels. Every product is quality-checked before dispatch.', 'Products', '2026-06-12 01:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `link` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'flash',
  `discount` varchar(50) DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `image` text DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `description`, `type`, `discount`, `ends_at`, `image`, `badge`, `created_at`) VALUES
(1, 'Festival Offer', NULL, 'flash', '20', NULL, NULL, NULL, '2026-06-11 19:47:06'),
(2, 'First Order 10% Off with TTFIRST', 'New to TT Electro? Use code TTFIRST at checkout for 10% off your first order (up to ₹500). Valid on orders above ₹299. One use per customer.', 'coupon', '10%', NULL, NULL, 'New Customer', '2026-06-12 01:31:24'),
(3, 'Maker Bundle Deal — Spend ₹999+ Save 20%', 'Use code MAKER20 on orders above ₹999 to save 20% (up to ₹800). Perfect when stocking up on sensors, modules, and that starter kit you have been eyeing.', 'coupon', '20%', NULL, NULL, 'Maker Deal', '2026-06-12 01:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shipping_address`)),
  `payment_method` varchar(50) NOT NULL DEFAULT 'cod',
  `notes` text DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `status_timeline` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`status_timeline`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `thumbnail` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `print3d_requests`
--

CREATE TABLE `print3d_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `file_url` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `material` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `status` enum('pending','reviewing','quoted','printing','done','cancelled') NOT NULL DEFAULT 'pending',
  `estimated_price` decimal(10,2) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `thumbnail` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `is_flash_sale` tinyint(1) NOT NULL DEFAULT 0,
  `flash_sale_price` decimal(10,2) DEFAULT NULL,
  `flash_sale_ends` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `price`, `original_price`, `discount`, `stock`, `thumbnail`, `images`, `tags`, `specifications`, `category_id`, `brand_id`, `is_featured`, `is_trending`, `is_best_seller`, `is_flash_sale`, `flash_sale_price`, `flash_sale_ends`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Arduino Uno R3', 'arduino-uno-r3', 'The iconic Arduino Uno R3 based on the ATmega328P microcontroller.', 599.00, 799.00, 25.03, 100, 'https://picsum.photos/seed/arduino-uno-r3/600/600', '[\"https://picsum.photos/seed/arduino-uno-r3/600/600\"]', '[\"arduino\",\"microcontroller\"]', '{\"Microcontroller\":\"ATmega328P\"}', 1, 1, 1, 0, 1, 0, NULL, NULL, 1, '2026-06-11 04:04:55', '2026-06-11 19:47:05'),
(2, 'ESP32 DevKit', 'esp32-dev', 'WiFi + Bluetooth IoT', 499.00, 499.00, 30.00, 150, 'https://picsum.photos/seed/p2/400/400', NULL, NULL, NULL, 1, 2, 1, 1, 1, 1, 279.00, NULL, 1, '2026-06-11 04:04:55', '2026-06-11 19:47:05'),
(3, 'DHT22 Sensor', 'dht22', 'Temperature & humidity sensor', 129.00, 169.00, 23.00, 310, 'https://picsum.photos/seed/p3/400/400', NULL, NULL, NULL, 2, 3, 0, 1, 1, 0, NULL, NULL, 1, '2026-06-11 04:04:55', '2026-06-11 04:04:55'),
(80, 'Arduino Nano Every', 'arduino-nano-every', 'Compact Arduino Nano Every based on ATMega4809. More memory and faster than classic Nano. Perfect for space-constrained projects.', 349.00, 499.00, 30.06, 200, 'https://picsum.photos/seed/arduino-nano-every/600/600', '[\"https://picsum.photos/seed/arduino-nano-every/600/600\"]', '[\"arduino\",\"nano\",\"compact\",\"atmega4809\",\"breadboard\"]', '[{\"key\":\"Microcontroller\",\"value\":\"ATMega4809\"},{\"key\":\"Operating Voltage\",\"value\":\"5V\"},{\"key\":\"Digital I/O\",\"value\":\"14\"},{\"key\":\"Analog Inputs\",\"value\":\"8\"},{\"key\":\"Flash\",\"value\":\"48 KB\"},{\"key\":\"SRAM\",\"value\":\"6 KB\"},{\"key\":\"Clock\",\"value\":\"20 MHz\"}]', 1, 1, 0, 1, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(81, 'ESP32 DevKit V1', 'esp32-devkit-v1', 'Feature-rich WiFi + Bluetooth development board. Dual-core 240 MHz Xtensa LX6, 38 GPIO pins, built-in BLE 4.2. Best value IoT board in 2026.', 449.00, 599.00, 25.04, 300, 'https://picsum.photos/seed/esp32-devkit/600/600', '[\"https://picsum.photos/seed/esp32-devkit/600/600\",\"https://picsum.photos/seed/esp32-devkit-b/600/600\"]', '[\"esp32\",\"wifi\",\"bluetooth\",\"iot\",\"dual-core\"]', '[{\"key\":\"Processor\",\"value\":\"Xtensa Dual-Core 32-bit LX6\"},{\"key\":\"Clock\",\"value\":\"240 MHz\"},{\"key\":\"Flash\",\"value\":\"4 MB\"},{\"key\":\"SRAM\",\"value\":\"520 KB\"},{\"key\":\"WiFi\",\"value\":\"802.11 b/g/n 2.4 GHz\"},{\"key\":\"Bluetooth\",\"value\":\"BLE 4.2 + Classic\"},{\"key\":\"GPIO\",\"value\":\"38\"},{\"key\":\"Voltage\",\"value\":\"3.3V\"}]', 1, 3, 1, 1, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(82, 'Raspberry Pi Pico W', 'raspberry-pi-pico-w', 'RP2040-based Pico with on-board WiFi 802.11n. Programme in MicroPython or C/C++. Dual-core ARM Cortex-M0+ at 133 MHz, 264 KB SRAM, 26 GPIO.', 749.00, 999.00, 25.02, 120, 'https://picsum.photos/seed/rpi-pico-w/600/600', '[\"https://picsum.photos/seed/rpi-pico-w/600/600\"]', '[\"raspberry-pi\",\"pico\",\"micropython\",\"rp2040\",\"wifi\"]', '[{\"key\":\"Processor\",\"value\":\"RP2040 Dual-Core Cortex-M0+\"},{\"key\":\"Clock\",\"value\":\"133 MHz\"},{\"key\":\"SRAM\",\"value\":\"264 KB\"},{\"key\":\"Flash\",\"value\":\"2 MB\"},{\"key\":\"WiFi\",\"value\":\"802.11n 2.4 GHz\"},{\"key\":\"GPIO\",\"value\":\"26\"},{\"key\":\"ADC\",\"value\":\"3× 12-bit\"}]', 1, 2, 1, 1, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(83, 'DHT22 Temperature & Humidity Sensor', 'dht22-temp-humidity-sensor', 'Calibrated digital temperature and humidity sensor. Wider range and higher accuracy than DHT11. Single-wire interface, compatible with Arduino, ESP32, and Raspberry Pi.', 199.00, 299.00, 33.44, 500, 'https://picsum.photos/seed/dht22-sensor/600/600', '[\"https://picsum.photos/seed/dht22-sensor/600/600\"]', '[\"sensor\",\"temperature\",\"humidity\",\"dht22\",\"iot\",\"climate\"]', '[{\"key\":\"Temp Range\",\"value\":\"-40°C to +80°C\"},{\"key\":\"Temp Accuracy\",\"value\":\"±0.5°C\"},{\"key\":\"Humidity\",\"value\":\"0–100% RH\"},{\"key\":\"Humidity Accuracy\",\"value\":\"±2–5% RH\"},{\"key\":\"Sample Rate\",\"value\":\"0.5 Hz\"},{\"key\":\"Voltage\",\"value\":\"3.3–6V\"},{\"key\":\"Interface\",\"value\":\"Single-wire\"}]', 2, 6, 0, 1, 0, 1, 149.00, '2026-08-31 23:59:59', 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(84, 'HC-SR04 Ultrasonic Distance Sensor', 'hc-sr04-ultrasonic-sensor', 'Measures distance 2 cm to 400 cm using ultrasonic pulses. 3 mm accuracy, 15° measuring angle. Used in robotics, obstacle detection, and level sensing.', 99.00, 149.00, 33.56, 800, 'https://picsum.photos/seed/hcsr04-sensor/600/600', '[\"https://picsum.photos/seed/hcsr04-sensor/600/600\"]', '[\"sensor\",\"ultrasonic\",\"distance\",\"hc-sr04\",\"robotics\"]', '[{\"key\":\"Range\",\"value\":\"2 cm – 400 cm\"},{\"key\":\"Accuracy\",\"value\":\"3 mm\"},{\"key\":\"Angle\",\"value\":\"15°\"},{\"key\":\"Voltage\",\"value\":\"5V DC\"},{\"key\":\"Current\",\"value\":\"15 mA\"},{\"key\":\"Trigger\",\"value\":\"10µs TTL pulse\"}]', 2, 6, 0, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(85, 'MPU-6050 6-Axis Accelerometer & Gyroscope', 'mpu6050-accelerometer-gyroscope', 'Combined 3-axis accelerometer + 3-axis gyroscope on one chip. I2C interface, 16-bit ADC, built-in DMP. Used in drones, balancing robots, and wearables.', 149.00, 249.00, 40.16, 600, 'https://picsum.photos/seed/mpu6050/600/600', '[\"https://picsum.photos/seed/mpu6050/600/600\"]', '[\"sensor\",\"imu\",\"gyroscope\",\"accelerometer\",\"mpu6050\",\"drone\"]', '[{\"key\":\"Accel Range\",\"value\":\"±2g/4g/8g/16g\"},{\"key\":\"Gyro Range\",\"value\":\"±250/500/1000/2000 °/s\"},{\"key\":\"Interface\",\"value\":\"I2C (400kHz)\"},{\"key\":\"Voltage\",\"value\":\"3.3–5V\"},{\"key\":\"ADC\",\"value\":\"16-bit\"},{\"key\":\"Extra\",\"value\":\"DMP + Temperature sensor\"}]', 2, 6, 0, 0, 0, 1, 99.00, '2026-08-31 23:59:59', 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(86, 'MQ-135 Air Quality Gas Sensor', 'mq135-air-quality-sensor', 'Detects NH3, NOx, alcohol, benzene, smoke, and CO2. Analog + digital output. Widely used in air quality monitors, ventilation systems, and smart home projects.', 129.00, 199.00, 35.17, 400, 'https://picsum.photos/seed/mq135-sensor/600/600', '[\"https://picsum.photos/seed/mq135-sensor/600/600\"]', '[\"sensor\",\"gas\",\"air-quality\",\"mq135\",\"smoke\",\"co2\"]', '[{\"key\":\"Detects\",\"value\":\"NH3, NOx, Alcohol, CO2, Benzene\"},{\"key\":\"Output\",\"value\":\"Analog + Digital TTL\"},{\"key\":\"Heater\",\"value\":\"5V AC/DC\"},{\"key\":\"Voltage\",\"value\":\"5V\"},{\"key\":\"Preheat\",\"value\":\"20 seconds\"}]', 2, 6, 0, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(87, '0.96\" OLED Display I2C (SSD1306)', 'oled-096-display-i2c', 'Crisp 0.96\" OLED using SSD1306 driver over I2C. No backlight — emissive pixels give excellent contrast. Just 2 wires (SDA+SCL). Works with all Arduino, ESP32 and STM32 boards.', 249.00, 349.00, 28.65, 350, 'https://picsum.photos/seed/oled-display-ssd1306/600/600', '[\"https://picsum.photos/seed/oled-display-ssd1306/600/600\"]', '[\"display\",\"oled\",\"ssd1306\",\"i2c\",\"128x64\",\"screen\"]', '[{\"key\":\"Size\",\"value\":\"0.96 inch\"},{\"key\":\"Resolution\",\"value\":\"128×64 px\"},{\"key\":\"Driver\",\"value\":\"SSD1306\"},{\"key\":\"Interface\",\"value\":\"I2C (0x3C)\"},{\"key\":\"Voltage\",\"value\":\"3.3–5V\"},{\"key\":\"Colour\",\"value\":\"Monochrome\"},{\"key\":\"View Angle\",\"value\":\"160°\"}]', 3, 6, 1, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(88, '16x2 LCD Display with I2C Backpack', '16x2-lcd-i2c-display', '16×2 LCD with soldered PCF8574 I2C backpack — only 2 wires needed. Blue backlight, contrast pot. Plug-and-play with LiquidCrystal_I2C library.', 149.00, 229.00, 34.93, 400, 'https://picsum.photos/seed/lcd-16x2-i2c/600/600', '[\"https://picsum.photos/seed/lcd-16x2-i2c/600/600\"]', '[\"display\",\"lcd\",\"16x2\",\"i2c\",\"backlight\",\"pcf8574\"]', '[{\"key\":\"Size\",\"value\":\"16 × 2\"},{\"key\":\"Backlight\",\"value\":\"Blue LED\"},{\"key\":\"Interface\",\"value\":\"I2C via PCF8574\"},{\"key\":\"I2C Address\",\"value\":\"0x27\"},{\"key\":\"Voltage\",\"value\":\"5V\"},{\"key\":\"Contrast\",\"value\":\"Adjustable pot\"}]', 3, 6, 0, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(89, '2.4\" TFT LCD Touchscreen Shield (ILI9341)', '2-4-tft-lcd-touchscreen-ili9341', 'Full-colour 2.4\" TFT with resistive touchscreen and SD card slot. ILI9341 SPI driver. Plugs on top of Arduino Uno/Mega. 65,536 colours at 240×320 px.', 499.00, 699.00, 28.61, 180, 'https://picsum.photos/seed/tft-240x320/600/600', '[\"https://picsum.photos/seed/tft-240x320/600/600\"]', '[\"display\",\"tft\",\"touchscreen\",\"ili9341\",\"spi\",\"colour\"]', '[{\"key\":\"Size\",\"value\":\"2.4 inch\"},{\"key\":\"Resolution\",\"value\":\"240×320 px\"},{\"key\":\"Driver\",\"value\":\"ILI9341\"},{\"key\":\"Interface\",\"value\":\"SPI\"},{\"key\":\"Colours\",\"value\":\"65,536 (16-bit)\"},{\"key\":\"Touch\",\"value\":\"Resistive XPT2046\"},{\"key\":\"SD Card\",\"value\":\"Yes\"}]', 3, 4, 0, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(90, 'LM2596 DC-DC Buck Converter Module', 'lm2596-buck-converter', 'Adjustable step-down buck converter. Input 4–40V, output 1.25–37V. Up to 3A. On-board LED voltmeter. 92% efficiency. Ideal for powering 3.3V/5V circuits.', 119.00, 189.00, 37.03, 600, 'https://picsum.photos/seed/lm2596-buck/600/600', '[\"https://picsum.photos/seed/lm2596-buck/600/600\"]', '[\"power\",\"buck-converter\",\"lm2596\",\"step-down\",\"dc-dc\"]', '[{\"key\":\"Input\",\"value\":\"4–40V DC\"},{\"key\":\"Output\",\"value\":\"1.25–37V adjustable\"},{\"key\":\"Max Current\",\"value\":\"3A\"},{\"key\":\"Efficiency\",\"value\":\"Up to 92%\"},{\"key\":\"Frequency\",\"value\":\"150 kHz\"},{\"key\":\"Display\",\"value\":\"LED voltmeter\"}]', 4, 6, 0, 1, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(91, 'TP4056 Li-Ion Battery Charger Module (USB-C)', 'tp4056-battery-charger-usb-c', 'Single-cell Li-Ion/LiPo charger + protection via USB-C. Charges up to 1A CC/CV. Built-in overcharge, over-discharge and short-circuit protection. Dual-colour LED indicator.', 59.00, 99.00, 40.40, 1000, 'https://picsum.photos/seed/tp4056-charger/600/600', '[\"https://picsum.photos/seed/tp4056-charger/600/600\"]', '[\"power\",\"battery-charger\",\"tp4056\",\"lipo\",\"lithium\",\"usb-c\"]', '[{\"key\":\"Input\",\"value\":\"4.5–5.5V USB-C\"},{\"key\":\"Charge\",\"value\":\"Up to 1A\"},{\"key\":\"Voltage\",\"value\":\"4.2V ±1%\"},{\"key\":\"Protection\",\"value\":\"Overcharge + Overdischarge + Short\"},{\"key\":\"LED\",\"value\":\"Red=charging, Blue=full\"},{\"key\":\"Cell\",\"value\":\"3.7V Li-Ion / LiPo\"}]', 4, 6, 0, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(92, 'HC-05 Bluetooth Serial Module', 'hc05-bluetooth-module', 'Bluetooth 2.0 SPP module with AT command support. Master and slave modes. Range up to 10 m. Pairs easily with smartphones and PCs. Configurable baud rate.', 299.00, 449.00, 33.40, 250, 'https://picsum.photos/seed/hc05-bluetooth/600/600', '[\"https://picsum.photos/seed/hc05-bluetooth/600/600\"]', '[\"bluetooth\",\"serial\",\"hc-05\",\"wireless\",\"uart\",\"at-commands\"]', '[{\"key\":\"Version\",\"value\":\"Bluetooth 2.0 SPP\"},{\"key\":\"Frequency\",\"value\":\"2.4 GHz ISM\"},{\"key\":\"Range\",\"value\":\"Up to 10 m\"},{\"key\":\"Voltage\",\"value\":\"3.3V (5V tolerant)\"},{\"key\":\"Interface\",\"value\":\"UART TX/RX\"},{\"key\":\"Baud\",\"value\":\"9600 bps default\"},{\"key\":\"Mode\",\"value\":\"Master / Slave\"}]', 5, 6, 0, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(93, 'NRF24L01+ 2.4GHz RF Transceiver Module', 'nrf24l01-rf-transceiver', 'Single-chip 2.4 GHz wireless transceiver with SPI interface. 1 Mbps / 2 Mbps data rates. Up to 100 m range. Low power — ideal for wireless sensor networks.', 199.00, 299.00, 33.44, 400, 'https://picsum.photos/seed/nrf24l01-rf/600/600', '[\"https://picsum.photos/seed/nrf24l01-rf/600/600\"]', '[\"rf\",\"wireless\",\"nrf24l01\",\"2.4ghz\",\"spi\",\"transceiver\"]', '[{\"key\":\"Frequency\",\"value\":\"2.4–2.5 GHz ISM\"},{\"key\":\"Data Rate\",\"value\":\"250k / 1M / 2M bps\"},{\"key\":\"Range\",\"value\":\"Up to 100 m\"},{\"key\":\"Voltage\",\"value\":\"1.9–3.6V\"},{\"key\":\"Interface\",\"value\":\"SPI 10 Mbps\"},{\"key\":\"Channels\",\"value\":\"126\"}]', 5, 6, 0, 0, 0, 1, 149.00, '2026-08-31 23:59:59', 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(94, 'SIM800L GSM GPRS Module', 'sim800l-gsm-gprs-module', 'Quad-band GSM/GPRS module for SMS, voice calls, and GPRS data. AT command control. Includes SIM slot. Perfect for IoT projects needing cellular connectivity.', 599.00, 849.00, 29.44, 100, 'https://picsum.photos/seed/sim800l-gsm/600/600', '[\"https://picsum.photos/seed/sim800l-gsm/600/600\"]', '[\"gsm\",\"gprs\",\"sim800l\",\"cellular\",\"iot\",\"sms\"]', '[{\"key\":\"Bands\",\"value\":\"850/900/1800/1900 MHz\"},{\"key\":\"GPRS\",\"value\":\"Class 10 (85.6 kbps)\"},{\"key\":\"SMS\",\"value\":\"MT/MO/CB/Text/PDU\"},{\"key\":\"Voice\",\"value\":\"Yes\"},{\"key\":\"SIM\",\"value\":\"1.8V / 3V\"},{\"key\":\"Voltage\",\"value\":\"3.4–4.4V\"}]', 5, 6, 0, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(95, 'L298N Dual H-Bridge Motor Driver', 'l298n-motor-driver', 'Controls 2 DC motors or 1 stepper. 2A per channel, 46V supply. Built-in 5V regulator powers your board. Logic-level inputs compatible with Arduino and ESP32.', 179.00, 269.00, 33.46, 450, 'https://picsum.photos/seed/l298n-motor-driver/600/600', '[\"https://picsum.photos/seed/l298n-motor-driver/600/600\"]', '[\"motor-driver\",\"l298n\",\"dc-motor\",\"stepper\",\"robotics\",\"h-bridge\"]', '[{\"key\":\"Channels\",\"value\":\"2 DC or 1 stepper\"},{\"key\":\"Max Voltage\",\"value\":\"46V\"},{\"key\":\"Max Current\",\"value\":\"2A/channel\"},{\"key\":\"Peak\",\"value\":\"3A\"},{\"key\":\"Logic\",\"value\":\"2.5–5V TTL\"},{\"key\":\"On-board\",\"value\":\"5V reg + LED + heat sink\"}]', 6, 6, 0, 1, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(96, 'SG90 Micro Servo Motor 9g', 'sg90-micro-servo-motor', '9g micro servo with 180° rotation and plastic gears. Includes 3 horns and 2 mounting screws. PWM control (50 Hz, 1–2 ms). Works with Arduino, ESP32, and Raspberry Pi.', 149.00, 229.00, 34.93, 700, 'https://picsum.photos/seed/sg90-servo/600/600', '[\"https://picsum.photos/seed/sg90-servo/600/600\"]', '[\"servo\",\"motor\",\"sg90\",\"pwm\",\"robotics\",\"rc\"]', '[{\"key\":\"Weight\",\"value\":\"9 g\"},{\"key\":\"Torque\",\"value\":\"1.8 kg·cm at 4.8V\"},{\"key\":\"Speed\",\"value\":\"0.1 s/60°\"},{\"key\":\"Rotation\",\"value\":\"0–180°\"},{\"key\":\"Control\",\"value\":\"PWM 50 Hz\"},{\"key\":\"Voltage\",\"value\":\"4.8–6V\"}]', 6, 6, 0, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(97, '2WD Robot Car Chassis Kit', '2wd-robot-car-chassis', 'Complete 2WD robot platform — acrylic base, 2 DC gear motors (1:48), 2 wheels, caster wheel, brackets, battery holder and all hardware. Perfect for line-follower and obstacle-avoidance robots.', 549.00, 799.00, 31.28, 80, 'https://picsum.photos/seed/robot-car-chassis/600/600', '[\"https://picsum.photos/seed/robot-car-chassis/600/600\"]', '[\"robot\",\"chassis\",\"2wd\",\"dc-motor\",\"kit\",\"diy\"]', '[{\"key\":\"Drive\",\"value\":\"2WD\"},{\"key\":\"Gear Ratio\",\"value\":\"1:48\"},{\"key\":\"Motor\",\"value\":\"3–6V DC\"},{\"key\":\"Speed\",\"value\":\"~0.5 m/s at 5V\"},{\"key\":\"Plate\",\"value\":\"Acrylic\"},{\"key\":\"Wheel\",\"value\":\"65 mm diameter\"}]', 6, 6, 0, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(98, 'PLA Filament 1kg Spool (White, 1.75mm)', 'pla-filament-1kg-white-1-75mm', 'Premium PLA filament, 1.75 mm ±0.02 mm, 1 kg spool. Low warping, biodegradable. Suitable for Ender 3, Prusa, and Bambu Lab. Consistent colour throughout.', 799.00, 999.00, 20.02, 60, 'https://picsum.photos/seed/pla-filament-white/600/600', '[\"https://picsum.photos/seed/pla-filament-white/600/600\"]', '[\"3d-printing\",\"pla\",\"filament\",\"1.75mm\",\"fdm\",\"ender3\"]', '[{\"key\":\"Material\",\"value\":\"PLA\"},{\"key\":\"Diameter\",\"value\":\"1.75 mm ±0.02 mm\"},{\"key\":\"Weight\",\"value\":\"1 kg\"},{\"key\":\"Print Temp\",\"value\":\"180–220°C\"},{\"key\":\"Bed Temp\",\"value\":\"20–60°C (optional)\"},{\"key\":\"Colour\",\"value\":\"White\"}]', 7, 6, 0, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(99, 'Digital Multimeter DT9208A', 'digital-multimeter-dt9208a', 'Professional 3.5-digit LCD multimeter with auto-range and data hold. Measures DC/AC voltage, current, resistance, capacitance, frequency, temperature (K-type), hFE and diode.', 899.00, 1299.00, 30.79, 75, 'https://picsum.photos/seed/multimeter-dt9208a/600/600', '[\"https://picsum.photos/seed/multimeter-dt9208a/600/600\"]', '[\"multimeter\",\"tools\",\"voltage\",\"current\",\"resistance\",\"measurement\"]', '[{\"key\":\"Display\",\"value\":\"3.5-digit LCD\"},{\"key\":\"DC Voltage\",\"value\":\"200mV–1000V\"},{\"key\":\"AC Voltage\",\"value\":\"200V–750V\"},{\"key\":\"DC Current\",\"value\":\"2mA–10A\"},{\"key\":\"Resistance\",\"value\":\"200Ω–200MΩ\"},{\"key\":\"Temperature\",\"value\":\"-40°C to +1000°C\"}]', 8, 6, 1, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(100, '60W Adjustable Soldering Iron Station', '60w-soldering-iron-station', '60W temperature-controlled soldering station with LCD. Range 200–480°C, warm-up ~30 s. Includes iron holder, brass-wool cleaner, 5 replacement tips, solder, and tip-tinner.', 699.00, 999.00, 30.03, 50, 'https://picsum.photos/seed/soldering-iron-station/600/600', '[\"https://picsum.photos/seed/soldering-iron-station/600/600\"]', '[\"soldering\",\"tools\",\"iron\",\"station\",\"smd\",\"electronics\"]', '[{\"key\":\"Power\",\"value\":\"60W\"},{\"key\":\"Temp Range\",\"value\":\"200–480°C\"},{\"key\":\"Display\",\"value\":\"LCD\"},{\"key\":\"Warm-up\",\"value\":\"~30 s to 300°C\"},{\"key\":\"Tip\",\"value\":\"900M series\"},{\"key\":\"Includes\",\"value\":\"5 tips, holder, cleaner, solder\"}]', 8, 6, 0, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(101, 'Jumper Wire Kit — 120 Pieces (M-M/M-F/F-F)', 'jumper-wire-kit-120pcs', '40 M-M + 40 M-F + 40 F-F jumper wires, 20 cm each. Colour-coded, breadboard-friendly, 26 AWG flexible. Essential for prototyping with Arduino, ESP32, and Raspberry Pi.', 149.00, 199.00, 25.12, 2000, 'https://picsum.photos/seed/jumper-wires-kit/600/600', '[\"https://picsum.photos/seed/jumper-wires-kit/600/600\"]', '[\"jumper-wires\",\"breadboard\",\"connector\",\"prototyping\",\"kit\"]', '[{\"key\":\"Total\",\"value\":\"120 pcs (40×M-M, 40×M-F, 40×F-F)\"},{\"key\":\"Length\",\"value\":\"20 cm\"},{\"key\":\"Wire\",\"value\":\"26 AWG flexible\"},{\"key\":\"Colours\",\"value\":\"10 colours\"},{\"key\":\"Pitch\",\"value\":\"2.54 mm\"},{\"key\":\"Max Voltage\",\"value\":\"300V\"}]', 9, 6, 0, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(102, 'USB Type-C Cable Pack — 1m, 3 Cables', 'usb-type-c-cable-pack-3pcs', 'Pack of 3 USB-A to USB-C braided nylon cables, 1 m each. 480 Mbps data + 3A fast charging. Compatible with ESP32-S3, Arduino Nano 33, Raspberry Pi 5 and any USB-C device.', 299.00, 449.00, 33.40, 500, 'https://picsum.photos/seed/usb-c-cable-pack/600/600', '[\"https://picsum.photos/seed/usb-c-cable-pack/600/600\"]', '[\"usb-c\",\"cable\",\"charging\",\"data\",\"braided\",\"3-pack\"]', '[{\"key\":\"Length\",\"value\":\"1 m\"},{\"key\":\"Qty\",\"value\":\"3 cables\"},{\"key\":\"Connector A\",\"value\":\"USB-A 2.0\"},{\"key\":\"Connector B\",\"value\":\"USB-C\"},{\"key\":\"Speed\",\"value\":\"480 Mbps\"},{\"key\":\"Charging\",\"value\":\"3A max\"},{\"key\":\"Jacket\",\"value\":\"Braided nylon\"}]', 9, 6, 0, 0, 0, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24'),
(103, 'Arduino Ultimate Starter Kit — 32 Components', 'arduino-ultimate-starter-kit', 'Everything to start with Arduino — Uno R3 clone, 830-point breadboard, 32 component types (LEDs, resistors, sensors, servo, LCD, buzzer, relay, motors) and 30+ project PDFs.', 1499.00, 2299.00, 34.80, 60, 'https://picsum.photos/seed/arduino-starter-kit/600/600', '[\"https://picsum.photos/seed/arduino-starter-kit/600/600\",\"https://picsum.photos/seed/arduino-starter-kit-b/600/600\"]', '[\"kit\",\"arduino\",\"starter\",\"beginner\",\"learning\",\"diy\"]', '[{\"key\":\"Board\",\"value\":\"Arduino Uno R3 compatible\"},{\"key\":\"Breadboard\",\"value\":\"830-point\"},{\"key\":\"Components\",\"value\":\"32 types\"},{\"key\":\"Jumper Wires\",\"value\":\"65 pcs\"},{\"key\":\"USB Cable\",\"value\":\"Included\"},{\"key\":\"Projects\",\"value\":\"30+ PDF tutorials\"}]', 10, 1, 1, 0, 1, 0, NULL, NULL, 1, '2026-06-12 01:31:24', '2026-06-12 01:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `title` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `role` enum('user','admin','guest') NOT NULL DEFAULT 'user',
  `loyalty_points` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `avatar`, `role`, `loyalty_points`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Admin', 'admin@ttelectro.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+91 9876543210', NULL, 'admin', 0, 1, '2026-06-11 20:25:40', '2026-06-11 20:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_user_product` (`user_id`,`product_id`),
  ADD KEY `cart_items_user_id` (`user_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `slug_2` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `diy_kits`
--
ALTER TABLE `diy_kits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsletter_email_unique` (`email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id` (`user_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id` (`order_id`);

--
-- Indexes for table `print3d_requests`
--
ALTER TABLE `print3d_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `print3d_user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id` (`category_id`),
  ADD KEY `products_brand_id` (`brand_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id` (`product_id`),
  ADD KEY `reviews_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlist_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_wishlist_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `diy_kits`
--
ALTER TABLE `diy_kits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `print3d_requests`
--
ALTER TABLE `print3d_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `print3d_requests`
--
ALTER TABLE `print3d_requests`
  ADD CONSTRAINT `fk_print3d_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
