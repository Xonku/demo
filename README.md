CREATE DATABASE IF NOT EXISTS `comfy_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `comfy_shop`;

-- Пользователи
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Товары (Сувениры)
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `image` VARCHAR(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Заказы
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `order_number` VARCHAR(20) NOT NULL,
  `order_date` DATE NOT NULL,
  `delivery_date` DATE NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `status` ENUM('open', 'closed') DEFAULT 'open',
  `total_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Элементы заказов
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Наполнение товарами из макета
INSERT INTO `products` (`name`, `category`, `price`, `image`) VALUES
('Народные сувениры', 'народные', 500.00, 'default.jpg'),
('Статуэтки', 'статуэтки', 300.00, 'default.jpg'),
('Авторские сувениры', 'авторские', 1000.00, 'default.jpg'),
('Декоративные сувениры', 'декоративные', 700.00, 'default.jpg'),
('Оригинальные сувениры', 'оригинальные', 800.00, 'default.jpg'),
('Брелки', 'брелки', 480.00, 'default.jpg'),
('Сувенир 1', 'народные', 500.00, 'default.jpg'),
('Сувенир 7', 'народные', 450.00, 'default.jpg');
