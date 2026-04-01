-- phpMyAdmin SQL Dump
-- Project: 7amo Bikers Showroom

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Database: `7amo_bikers_db`
-- --------------------------------------------------------

-- 1. جدول الإدمن (Admins)
CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'admin@7amo-bikers.com', '$2y$10$tXhn47/o9fH7ETUAQBOte.7esL1KGkjDPea91AZ66oRh/B4YWWlHi');

-- --------------------------------------------------------

-- 2. جدول الموتسيكلات (Products/Bikes)
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL, -- (Sport, Cruiser, Touring)
  `engine_capacity` varchar(50) DEFAULT NULL, -- سعة المحرك (مثلاً 1000cc)
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- بيانات تجريبية للموتسيكلات
INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category`, `engine_capacity`) VALUES
(1, 'Yamaha R1', 18000.00, 'Super Sport high performance bike', 'r1_2026.webp', 'Sport', '1000cc'),
(2, 'Honda CBR600RR', 12000.00, 'Reliable and fast sport bike', 'cbr600.webp', 'Sport', '600cc'),
(3, 'Harley Davidson Iron 883', 9500.00, 'Classic American Cruiser style', 'iron883.webp', 'Cruiser', '883cc'),
(4, 'Kawasaki Ninja H2', 35000.00, 'Supercharged hypersport motorcycle', 'h2_ninja.webp', 'Sport', '998cc');

-- --------------------------------------------------------

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'Cash',
  `status` varchar(50) NOT NULL DEFAULT 'Pending', -- (Pending, In Workshop, Delivered, Cancelled)
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 5. جدول الكوبونات (Coupons) - اختياري لخصومات المعرض
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- الفهارس (Indexes) والربط
ALTER TABLE `admins` ADD PRIMARY KEY (`id`);
ALTER TABLE `products` ADD PRIMARY KEY (`id`);
ALTER TABLE `orders` ADD PRIMARY KEY (`id`);
ALTER TABLE `order_items` ADD PRIMARY KEY (`id`);
ALTER TABLE `coupons` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`);

-- الترقيم التلقائي (AUTO_INCREMENT)
ALTER TABLE `admins` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `products` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `orders` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `order_items` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `coupons` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;