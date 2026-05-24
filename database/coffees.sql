-- Cafe Espresso Database Export
-- Reorganized, modernized, and prepared for XAMPP / phpMyAdmin

CREATE DATABASE IF NOT EXISTS `coffees` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `coffees`;

-- ==========================================
-- 1. Table Structures
-- ==========================================

-- Table: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `role` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL UNIQUE,
  `role_level` VARCHAR(50) NOT NULL DEFAULT 'Super Admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: products
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `price` DECIMAL(10, 2) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `total_amount` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` ENUM('pending', 'paid', 'refunded') NOT NULL DEFAULT 'pending',
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'Cash on Delivery',
  `shipping_address` TEXT NOT NULL,
  `contact_number` VARCHAR(20) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: order_items
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `price` DECIMAL(10, 2) NOT NULL,
  `sugar_level` VARCHAR(50) NOT NULL DEFAULT '100% (Normal)',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: cart_items
DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `sugar_level` VARCHAR(50) NOT NULL DEFAULT '100% (Normal)',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: inventory
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL UNIQUE,
  `stock_quantity` INT NOT NULL DEFAULT 0,
  `low_stock_threshold` INT NOT NULL DEFAULT 10,
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: analytics
DROP TABLE IF EXISTS `analytics`;
CREATE TABLE `analytics` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_type` VARCHAR(100) NOT NULL,
  `event_data` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: reviews
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `comment` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- 2. Seed Sample Data
-- ==========================================

-- Seed: users (passwords: adminpassword123 and customerpassword123)
INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$B/oG7Fe80wRmpC/3LgUrF.VoTRgXPyGeDwIxRQLhY5H8VlJ7csX2i', 'admin@cafeespresso.com', 'admin', NOW() - INTERVAL 30 DAY),
(2, 'customer', '$2y$10$1j7QdC.Vx5IoAdInvQCo1.kUS.jSQDqHiB0NwFN3ozobfPOPYtLN2', 'customer@gmail.com', 'customer', NOW() - INTERVAL 25 DAY),
(3, 'sarah_patron', '$2y$10$1j7QdC.Vx5IoAdInvQCo1.kUS.jSQDqHiB0NwFN3ozobfPOPYtLN2', 'sarah@example.com', 'customer', NOW() - INTERVAL 15 DAY),
(4, 'michael_brew', '$2y$10$1j7QdC.Vx5IoAdInvQCo1.kUS.jSQDqHiB0NwFN3ozobfPOPYtLN2', 'michael@example.com', 'customer', NOW() - INTERVAL 10 DAY);

-- Seed: admins
INSERT INTO `admins` (`id`, `user_id`, `role_level`, `created_at`) VALUES
(1, 1, 'General Manager', NOW() - INTERVAL 30 DAY);

-- Seed: categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Espresso Craft', 'espresso-craft', 'Pure, concentrated coffee extracted under pressure for optimal crema and aromatics.', NOW() - INTERVAL 30 DAY),
(2, 'Velvety Milk', 'velvety-milk', 'Perfectly textured steamed milk poured over premium espresso shots.', NOW() - INTERVAL 30 DAY),
(3, 'Specialty Brews', 'specialty-brews', 'Artisanal brewing styles and cold extractions celebrating single-origin complexities.', NOW() - INTERVAL 30 DAY),
(4, 'Frappes & Ice', 'frappes-and-ice', 'Decadent blended ice coffee crafts and refreshing cold-shaken remedies.', NOW() - INTERVAL 30 DAY),
(5, 'Fresh Pastries', 'fresh-pastries', 'Handcrafted, oven-fresh baked goods prepared daily to complement your roast.', NOW() - INTERVAL 30 DAY);

-- Seed: products
INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_featured`, `created_at`) VALUES
(1, 1, 'Espresso', 'A single shot of our signature house blend espresso, featuring rich golden-brown crema and deep chocolate notes.', 100.00, 'espresso.jpg', 0, NOW() - INTERVAL 30 DAY),
(2, 1, 'Americano', 'Two shots of rich espresso poured gracefully over filtered hot water, creating a bold yet smooth drinking experience.', 120.00, 'americano.jpg', 0, NOW() - INTERVAL 30 DAY),
(3, 1, 'Double Espresso', 'Two concentrated shots of our premium espresso blend for an intense kick of caffeine and full-bodied aromatics.', 140.00, 'double_espresso.jpg', 0, NOW() - INTERVAL 30 DAY),
(4, 1, 'Macchiato', 'A shot of espresso marked beautifully with a dollop of warm, textured velvet milk foam.', 135.00, 'macchiato.jpg', 0, NOW() - INTERVAL 30 DAY),
(5, 2, 'Cappuccino', 'Equal parts espresso, steamed milk, and heavy textured milk foam, dusted with fine cacao powder.', 150.00, 'cappuccino.jpg', 1, NOW() - INTERVAL 30 DAY),
(6, 2, 'Cafe Latte', 'A velvet-smooth extraction of espresso combined with steamed microfoam milk, finished with unique barista art.', 150.00, 'latte.jpg', 1, NOW() - INTERVAL 30 DAY),
(7, 2, 'Caffe Mocha', 'Rich espresso combined with decadent artisanal dark chocolate sauce and smooth textured steamed milk.', 155.00, 'mocha.jpg', 0, NOW() - INTERVAL 30 DAY),
(8, 3, 'Affogato', 'A double shot of espresso poured over a generous scoop of premium Madagascar vanilla bean ice cream.', 160.00, 'affogato.jpg', 1, NOW() - INTERVAL 30 DAY),
(9, 3, 'Cold Brew', 'Signature single-origin coffee grounds steeped in ice-cold filtered water for 18 hours, resulting in low acidity and natural sweetness.', 145.00, 'cold_brew.jpg', 1, NOW() - INTERVAL 30 DAY),
(10, 4, 'Frappe', 'Our blended espresso shake topped with fresh whipped cream and dynamic chocolate drizzle.', 165.00, 'frappe.jpg', 0, NOW() - INTERVAL 30 DAY),
(11, 5, 'Butter Croissant', 'Flaky, buttery, and multi-layered French pastry baked to a perfect golden brown.', 95.00, 'croissant.jpg', 0, NOW() - INTERVAL 30 DAY),
(12, 5, 'Chocolate Fudge Cake', 'Rich, layered chocolate sponge cake covered in velvety fudge frosting, served warm.', 180.00, 'chocolate_cake.jpg', 0, NOW() - INTERVAL 30 DAY);

-- Seed: inventory
INSERT INTO `inventory` (`product_id`, `stock_quantity`, `low_stock_threshold`) VALUES
(1, 999, 10), -- Espresso (essentially unlimited because of beans stock)
(2, 999, 10),
(3, 999, 10),
(4, 999, 10),
(5, 999, 10),
(6, 999, 10),
(7, 999, 10),
(8, 150, 15),
(9, 80, 10),
(10, 120, 15),
(11, 8, 10), -- Low Stock Item for testing alerts!
(12, 12, 10);

-- Seed: settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('store_name', 'Cafe Espresso'),
('store_email', 'hello@cafeespresso.com'),
('store_phone', '+63 912 3456 789'),
('store_address', '123 Brew Street, Espresso District, Manila'),
('store_hours', 'Daily: 7:00 AM - 10:00 PM'),
('tax_rate', '12.00'),
('shipping_fee', '50.00'),
('currency', '₱');

-- Seed: reviews
INSERT INTO `reviews` (`user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(2, 6, 5, 'Best latte in town! The milk texture is unbelievably smooth, and the design on top was gorgeous.', NOW() - INTERVAL 12 DAY),
(3, 8, 5, 'Perfect balance of hot bitter espresso and sweet cold vanilla ice cream. An absolute must-try!', NOW() - INTERVAL 8 DAY),
(4, 9, 4, 'Very smooth and low acid cold brew. Super refreshing on a hot day.', NOW() - INTERVAL 5 DAY),
(2, 11, 4, 'Very flaky and buttery croissant. Pairs perfectly with an Americano.', NOW() - INTERVAL 3 DAY);

-- Seed: orders (Creating analytical data history)
-- Order 1: Completed Order (sarah)
INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `payment_status`, `payment_method`, `shipping_address`, `contact_number`, `created_at`) VALUES
(1, 3, 395.00, 'completed', 'paid', 'Cash on Delivery', 'Unit 402, Oakwood Condominiums, Quezon City', '09171234567', NOW() - INTERVAL 12 DAY);

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `sugar_level`) VALUES
(1, 6, 2, 150.00, '50% (Less)'),  -- 2 Lattes = 300
(1, 11, 1, 95.00, '100% (Normal)'); -- 1 Croissant = 95 (Total 395)

-- Order 2: Completed Order (michael)
INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `payment_status`, `payment_method`, `shipping_address`, `contact_number`, `created_at`) VALUES
(2, 4, 305.00, 'completed', 'paid', 'Bank Transfer', 'Apt 12B, Emerald Towers, Makati City', '09187654321', NOW() - INTERVAL 8 DAY);

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `sugar_level`) VALUES
(2, 2, 1, 120.00, '0% (None)'), -- 1 Americano = 120
(2, 12, 1, 180.00, '100% (Normal)'); -- 1 Chocolate Cake = 180 (Wait, 120+180=300 + simple delivery fee? Wait, total amount is 305? Oh, let's just make it 300 exact, or let's make it 300).
-- Let's update total_amount to 300.00
UPDATE `orders` SET `total_amount` = 300.00 WHERE `id` = 2;

-- Order 3: Processing Order (customer)
INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `payment_status`, `payment_method`, `shipping_address`, `contact_number`, `created_at`) VALUES
(3, 2, 425.00, 'processing', 'paid', 'Credit Card', '55 Orchard Rd, Ortigas Center, Pasig', '09228889999', NOW() - INTERVAL 1 DAY);

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `sugar_level`) VALUES
(3, 8, 1, 160.00, '100% (Normal)'), -- 1 Affogato = 160
(3, 10, 1, 165.00, '25% (Low)'), -- 1 Frappe = 165
(3, 12, 1, 180.00, '100% (Normal)'); -- 1 Cake = 180 (Total = 505)
-- Let's update total_amount to 505.00
UPDATE `orders` SET `total_amount` = 505.00 WHERE `id` = 3;

-- Order 4: Pending Order (sarah)
INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `payment_status`, `payment_method`, `shipping_address`, `contact_number`, `created_at`) VALUES
(4, 3, 245.00, 'pending', 'pending', 'Cash on Delivery', 'Unit 402, Oakwood Condominiums, Quezon City', '09171234567', NOW() - INTERVAL 2 HOUR);

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`, `sugar_level`) VALUES
(4, 9, 1, 145.00, '0% (None)'),  -- 1 Cold Brew = 145
(4, 1, 1, 100.00, '100% (Normal)'); -- 1 Espresso = 100 (Total = 245)

-- Seed: analytics
INSERT INTO `analytics` (`event_type`, `event_data`, `created_at`) VALUES
('page_view', '{"page":"home"}', NOW() - INTERVAL 10 DAY),
('page_view', '{"page":"menu"}', NOW() - INTERVAL 10 DAY),
('cart_add', '{"product_id":6}', NOW() - INTERVAL 9 DAY),
('purchase', '{"order_id":1,"total":395}', NOW() - INTERVAL 12 DAY),
('purchase', '{"order_id":2,"total":300}', NOW() - INTERVAL 8 DAY),
('purchase', '{"order_id":3,"total":505}', NOW() - INTERVAL 1 DAY);
