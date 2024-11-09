-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 09, 2024 at 06:07 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finalproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `password`, `phone_number`, `address`, `image`, `username`, `fullname`) VALUES
('admin@gmail.com', '$2y$10$y8D.bJRHKf4SRIznHvGDueRUhTxiHvzMnjfiZzxXzF5GJi.G.Ts.i', '09225049004', 'Liciada Bustos', 'img/wallpaper.jpeg', 'mikeyswifedjk', 'Maika Ordonez');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `variant` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_price` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `product_name`, `product_image`, `variant`, `quantity`, `price`, `created_at`, `updated_at`, `total_price`) VALUES
(301, 51, 157, 'IPhone Phone Case', '656d67641ec81.jpeg', '11 pro max', 1, '220', '2024-05-22 05:09:23', '2024-05-22 05:09:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `product_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`, `image`, `product_count`) VALUES
(43, 'WOMEN', '6562be09b9093.png', 6),
(44, 'SHOES', '6562be182e391.png', 5),
(45, 'BEAUTY', '6562be2a32588.png', 5),
(46, 'GROCERY', '6562be783fa49.png', 5),
(48, 'APPLIANCE', '6562bea4780cc.png', 5),
(51, 'MEN', '656d53044bf2e.png', 1),
(52, 'TOYS', '656d5315a8d52.png', 5),
(53, 'GADGETS', '656d534bca0d8.png', 6);

-- --------------------------------------------------------

--
-- Table structure for table `design_settings`
--

CREATE TABLE `design_settings` (
  `id` int(11) NOT NULL,
  `background_color` varchar(255) DEFAULT NULL,
  `font_color` varchar(255) DEFAULT NULL,
  `shop_name` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `image_one_path` varchar(255) DEFAULT NULL,
  `image_two_path` varchar(255) DEFAULT NULL,
  `image_three_path` varchar(255) DEFAULT NULL,
  `banner_one_path` varchar(255) DEFAULT NULL,
  `banner_two_path` varchar(255) DEFAULT NULL,
  `endorse_one_path` varchar(255) DEFAULT NULL,
  `endorse_two_path` varchar(255) DEFAULT NULL,
  `endorse_three_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `design_settings`
--

INSERT INTO `design_settings` (`id`, `background_color`, `font_color`, `shop_name`, `logo_path`, `image_one_path`, `image_two_path`, `image_three_path`, `banner_one_path`, `banner_two_path`, `endorse_one_path`, `endorse_two_path`, `endorse_three_path`) VALUES
(1, '#f5f5f5', '#000000', 'ShopBee', 'img/swarm.png', 'img/gadgets3-1.jpeg', 'img/grocery2-1.jpeg', 'img/appliances2-1.jpeg', 'img/bg2.jpeg', 'img/airplane.png', 'img/appliances1-1.jpeg', 'img/toy2-1.jpeg', 'img/gadgets3-4.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `product_variant` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_name`, `quantity`, `price`, `image`, `product_variant`, `product_id`, `order_date`, `total_amount`) VALUES
(252, 50, 'Focallure Tint', 1, 299.00, '656', '101', 147, '2023-12-06 08:24:54', 299),
(253, 50, 'Focallure Tint', 1, 399.00, '656', '102', 147, '2023-12-06 08:24:54', 399),
(254, 51, 'IPhone 15 Pro Max', 2, 130870.00, '656', 'white titanium', 127, '2023-12-09 07:54:35', 261740),
(255, 51, 'Kids Shoes ', 1, 1500.00, '656', '21', 144, '2023-12-09 08:20:47', 1500),
(258, 51, 'Kuromi', 1, 180.00, '656', 'blue/white', 141, '2023-12-10 05:22:43', 180),
(261, 51, 'Polish Me Nail', 1, 130.00, '65638253084', 'brown', 104, '2023-12-10 05:50:35', 130),
(262, 51, 'Japanese Spicy Strips ', 1, 350.00, '656', 'sweet ', 111, '2023-12-10 08:26:03', 350),
(263, 51, 'Pink Check', 1, 1000.00, '656', 'violet', 122, '2023-12-10 08:27:36', 1000),
(264, 51, 'LangTu Keyboard ', 1, 3500.00, '656', '104 keys', 128, '2023-12-10 08:27:36', 3500),
(265, 51, 'Coffee Mate Creamer', 1, 200.00, '656', '45g', 110, '2023-12-10 15:23:25', 200),
(266, 51, 'Kid Toy Pot', 1, 200.00, '656', 'rabbit', 156, '2023-12-10 15:25:10', 200),
(267, 51, 'Cart Holder ID', 1, 100.00, '656', 'yellow', 136, '2023-12-10 16:38:11', 100),
(268, 51, 'Kid Toy Pot', 1, 200.00, '656', 'rabbit', 156, '2023-12-11 08:45:42', 200),
(269, 51, 'Electric Fan', 1, 2000.00, '6562', 'big', 102, '2023-12-11 11:25:55', 2000),
(270, 51, 'LT 104', 1, 3900.00, '656', 'no screen', 158, '2023-12-11 11:29:47', 3900),
(271, 51, 'Addidas Pump Jump', 3, 3000.00, '656', '42', 116, '2023-12-11 14:05:18', 9000),
(272, 51, 'Addidas Sports Bra ', 1, 3000.00, '656', 'tulips', 123, '2023-12-11 17:44:28', 3000),
(273, 51, 'Royal Kludge', 1, 1200.00, '656', '90 keys', 143, '2023-12-11 17:46:18', 1200),
(274, 51, 'T shirt Polo Shirt ', 1, 150.00, '656', 'pink', 129, '2023-12-11 18:19:24', 150),
(275, 51, 'Addidas Pump Jump', 1, 3500.00, '656', '43', 116, '2023-12-11 18:19:24', 3500),
(276, 51, 'Magic Toy Umbrella', 7, 100.00, '656', 'kid', 155, '2023-12-12 04:38:32', 700),
(277, 51, 'Polish Me Nail', 1, 100.00, '65638253084', 'rose gold', 104, '2023-12-12 04:38:32', 100),
(278, 51, 'IPhone 15 Pro Max', 1, 130870.00, '656', 'white titanium', 127, '2023-12-12 04:38:32', 130870),
(279, 51, 'IPhone 15 Pro Max', 1, 130870.00, '656', 'natural titanium', 127, '2023-12-12 04:38:32', 130870),
(280, 51, 'Japanese Spicy Strips ', 1, 550.00, '656', 'super spicy', 111, '2023-12-12 04:40:51', 550),
(281, 51, 'IPhone Phone Case', 1, 100.00, '656', 'yellow', 126, '2023-12-12 04:40:51', 100),
(283, 51, 'Veggies', 1, 2000.00, '656', '40g', 152, '2023-12-12 04:40:51', 2000),
(284, 51, 'Wind Cat Breaker', 1, 1100.00, '656', 'small', 121, '2023-12-12 07:10:00', 1100),
(285, 51, 'T shirt Polo Shirt ', 1, 150.00, '656', 'blue', 129, '2023-12-12 07:10:00', 150),
(294, 50, 'Veggies', 16, 1000.00, '656', '20g', 152, '2023-12-12 08:01:13', 16000),
(295, 51, 'Miles Morales Figure', 2, 1000.00, '656', 'sad', 133, '2024-01-04 04:49:56', 2000),
(296, 51, 'Wind Cat Breaker', 1, 1800.00, '656', 'large', 121, '2024-01-04 06:19:51', 1800),
(297, 51, 'Wind Cat Breaker', 1, 1100.00, '656', 'small', 121, '2024-04-25 13:16:27', 1100),
(298, 51, 'Electric Fan', 1, 3000.00, '6562', 'extra big', 102, '2024-04-25 13:16:27', 3000),
(299, 51, 'Wind Cat Breaker', 11, 1900.00, '656', 'xlarge', 121, '2024-04-25 13:21:57', 20900),
(300, 51, 'Electric Fan', 2, 3000.00, '6562', 'extra big', 102, '2024-04-25 13:57:30', 6000),
(301, 51, 'Cart Holder ID', 1, 100.00, '656', 'yellow', 136, '2024-04-26 12:15:48', 100),
(302, 51, 'Focallure Tint', 1, 399.00, '656', '102', 147, '2024-04-26 13:35:43', 399),
(303, 51, 'Wind Cat Breaker', 2, 1200.00, '656', 'xsmall', 121, '2024-04-26 15:56:42', 2400),
(304, 51, 'Air Fryer ', 1, 1300.00, '656', '4.0L', 140, '2024-04-26 15:56:42', 1300),
(305, 51, 'Miles Morales Figure', 1, 1000.00, '656', 'angry', 133, '2024-04-27 07:36:29', 1000),
(306, 51, 'Japanese Spicy Strips ', 1, 450.00, '656', 'sour', 111, '2024-04-28 05:15:03', 450),
(307, 51, 'T shirt Polo Shirt ', 1, 150.00, '656', 'blue', 129, '2024-04-28 05:15:03', 150),
(308, 51, 'Japanese Spicy Strips ', 2, 350.00, '656', 'sweet', 111, '2024-05-20 03:56:55', 700),
(309, 51, 'Make Up Brush Set', 2, 2000.00, '656382', '12 set', 105, '2024-05-22 05:09:00', 4000);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `variant` text DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `image`, `category`, `qty`, `variant`, `price`, `category_id`) VALUES
(102, 'Electric Fan', '6562dffe027e7.jpeg', 'APPLIANCE', 21, 'small: 4 big: 10 extra big: 7 ', '1000-3000', 48),
(103, 'Focallure Lipstick', '65633a20ba5e0.jpeg', 'BEAUTY', 21, 'maroon: 3 red: 5 pink: 3 violet: 10 ', '130.00-200', 45),
(104, 'Polish Me Nail', '65638253084bf.jpeg', 'BEAUTY', 28, 'gold: 3 rose gold: 10 brown: 12 lime: 3 ', '100-140', 45),
(105, 'Make Up Brush Set', '656382ca92c9d.jpeg', 'BEAUTY', 30, '6 set: 5 12 set: 5 32 set: 20 ', '1000-3000', 45),
(109, 'Veggies Crisps ', '656ad3f9ee8e9.jpeg', 'GROCERY', 47, '30g: 5 40g: 10 60g: 12 70g: 20 ', '120-500', 46),
(110, 'Coffee Mate Creamer', '656ad462646ca.jpeg', 'GROCERY', 24, '12g: 5 45g: 10 60g: 3 90: 6 ', '100-400', 46),
(111, 'Japanese Spicy Strips ', '656ad4adda35b.jpeg', 'GROCERY', 50, 'sweet: 10 sweet & spicy: 10 sour: 10 spicy: 10 super spicy: 10 ', '350.00-550', 46),
(115, 'Pink Nike Pump', '656ad8eda30e9.jpeg', 'SHOES', 30, '36: 10 37: 5 38: 5 39: 5 40: 5 ', '1200-1600', 44),
(116, 'Addidas Pump Jump', '656ad98d50eb5.jpeg', 'SHOES', 110, '41: 10 42: 10 43: 10 44: 10 45: 10 46: 20 47: 20 48: 20 ', '2500-6000', 44),
(117, 'Orange Nike Pump', '656ada2b3f50c.jpeg', 'SHOES', 45, '31: 5 32: 5 33: 5 34: 10 35: 10 36: 10 ', '1000-6000', 44),
(121, 'Wind Cat Breaker', '656ae0fb87efe.jpeg', 'WOMEN', 200, 'xsmall: 10 small: 50 medium: 20 large: 70 xlarge: 50 ', '1000-1900', 43),
(122, 'Pink Check', '656ae3d72aad6.jpeg', 'WOMEN', 63, 'red: 10 pink: 10 violet: 10 orange: 10 green: 10 black: 10 white: 10 ', '1000-1200', 43),
(123, 'Addidas Sports Bra ', '656ae4bf1c5cb.jpeg', 'WOMEN', 113, 'floral: 10 daisy: 20 tulips: 30 plain black: 10 plain maroon: 20 plain white: 30 ', '1000-3000', 43),
(126, 'IPhone Phone Case', '656d5397b4c88.jpeg', 'GADGETS', 60, 'red - 10; blue - 10; green - 10; yellow - 10; violet - 10; orange - 10', '100-150', 53),
(127, 'IPhone 15 Pro Max', '656d541d3a8dc.jpeg', 'GADGETS', 60, 'natural titanium - 10; white titanium - 10; blue titanium - 10; titanium - 30', '120870-130870', 53),
(128, 'LangTu Keyboard ', '656d549e79b66.jpeg', 'GADGETS', 50, '60 keys: 10 80 keys: 10 104 keys: 10 120 keys: 10 180 keys: 10 ', '1200.00-5000', 53),
(129, 'T shirt Polo Shirt ', '656d55045e45a.jpeg', 'MEN', 60, 'red - 10; blue - 10; yellow - 10; pink - 10; white - 10; black - 10', '150-199', 51),
(133, 'Miles Morales Figure', '656d56db7a029.jpeg', 'TOYS', 40, 'lay - 10; sit - 10; happy - 10; sad - 5; angry - 5', '1000-1200', 52),
(134, 'Rubics Cube Toy ', '656d57309572d.jpeg', 'TOYS', 43, 'neon: 10 gold: 10 pastel: 10 normal: 5 silver: 5 small: 3 ', '100-450.00', 52),
(135, 'Home Wallpaper ', '656d58a9576df.jpeg', 'APPLIANCE', 43, 'natural - 17; glossy - 19; matte - 3; pastel - 4', '120-170', 48),
(136, 'Cart Holder ID', '656d617f9abf8.jpg', 'WOMEN', 20, 'red - 10; yellow - 10', '100-100', 43),
(137, 'Matte Coster Cup', '656d61b3e4232.jpg', 'APPLIANCE', 35, 'dog - 10; cat - 10; goat - 10; bear - 5', '100-120', 48),
(139, 'Straw Set ', '656d621b45646.jpg', 'GROCERY', 30, 'clear - 10; mermaid - 10; matte black - 10', '100-120', 46),
(140, 'Air Fryer ', '656d62415648a.jpg', 'WOMEN', 20, '4.0L - 10; 5.0L - 10', '1300-1700', 43),
(141, 'Kuromi', '656d62b8140d1.jpeg', 'TOYS', 20, 'white/blue - 10; blue/white - 10', '150-180', 52),
(143, 'Royal Kludge', '656d634740c6c.jpeg', 'GADGETS', 20, '82 keys - 10; 90 keys - 10', '1200-1200', 53),
(144, 'Kids Shoes ', '656d6376f2743.jpeg', 'SHOES', 20, '20 - 10; 21 - 10', '1500-1500', 44),
(147, 'Focallure Tint', '656d644e6f61c.jpeg', 'BEAUTY', 20, '101 - 10; 102 - 10', '299-399', 45),
(148, 'Glossy Nails', '656d646f7c8f9.jpeg', 'BEAUTY', 10, 'Glossy  - 5; matte - 5', '299-399', 45),
(149, 'Jordan Pink Flap', '656d64de5f92b.jpeg', 'SHOES', 16, '31 - 6; 32 - 10', '2500-3000', 44),
(151, 'Buy 1 Take 1', '656d65df616f2.jpeg', 'GROCERY', 30, '185g/400g: 10 250g/500g: 10 500g/700g: 10 ', '1500.00-3500', 43),
(152, 'Veggies', '656d660083b23.jpeg', 'GROCERY', 33, '20g - 16; 40g - 17', '1000-2000', 46),
(155, 'Magic Toy Umbrella', '656d66edcb04d.jpg', 'TOYS', 20, 'kid - 10; boy - 10', '100-199', 52),
(156, 'Kid Toy Pot', '656d6712e6b1c.jpg', 'TOYS', 20, 'bear - 10; rabbit - 10', '199-200', 52),
(157, 'IPhone Phone Case', '656d67641ec81.jpeg', 'GADGETS', 20, 'xs max - 10; 11 pro max - 10', '190-220', 53),
(158, 'LT 104', '656d67a406c2d.jpeg', 'GADGETS', 20, 'screen - 10; no screen - 10', '1899-3900', 53);

-- --------------------------------------------------------

--
-- Table structure for table `product_variant`
--

CREATE TABLE `product_variant` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variant`
--

INSERT INTO `product_variant` (`id`, `product_id`, `variant`, `qty`, `price`) VALUES
(280, 123, 'floral', 10, 1000.00),
(281, 123, 'daisy', 20, 2000.00),
(282, 123, 'tulips', 30, 3000.00),
(283, 123, 'plain black', 10, 1000.00),
(284, 123, 'plain maroon', 20, 2000.00),
(285, 123, 'plain white', 30, 3000.00),
(286, 122, 'red', 10, 1000.00),
(287, 122, 'pink', 10, 1000.00),
(288, 122, 'violet', 10, 1000.00),
(289, 122, 'orange', 10, 1000.00),
(290, 122, 'green', 10, 1000.00),
(291, 122, 'black', 10, 1200.00),
(292, 122, 'white', 10, 1200.00),
(293, 121, 'xsmall', 10, 1000.00),
(294, 121, 'small', 50, 1100.00),
(295, 121, 'medium', 20, 1200.00),
(296, 121, 'large', 70, 1800.00),
(297, 121, 'xlarge', 50, 1900.00),
(298, 120, 'neon', 10, 130.00),
(299, 120, 'pastel', 20, 120.00),
(300, 118, 'kuromi', 20, 135.00),
(301, 118, 'cinnamoroll', 10, 100.00),
(302, 118, 'hello kitty', 20, 200.00),
(303, 118, 'melody', 30, 150.00),
(304, 118, 'pororo', 10, 170.00),
(305, 120, 'gold', 10, 250.00),
(306, 120, 'natural', 10, 100.00),
(307, 119, 'lay', 10, 1000.00),
(308, 119, 'sad', 10, 2000.00),
(309, 119, 'angry', 10, 3000.00),
(310, 119, 'happy', 10, 4000.00),
(311, 119, 'action', 10, 5000.00),
(312, 117, '31', 5, 1000.00),
(313, 117, '32', 5, 2000.00),
(314, 117, '33', 5, 3000.00),
(315, 117, '34', 10, 4000.00),
(316, 117, '35', 10, 5000.00),
(317, 117, '36', 10, 6000.00),
(318, 116, '41', 10, 2500.00),
(319, 116, '42', 10, 3000.00),
(320, 116, '43', 10, 3500.00),
(321, 116, '44', 10, 4000.00),
(322, 116, '45', 10, 4500.00),
(323, 116, '46', 20, 5000.00),
(324, 116, '47', 20, 5500.00),
(325, 116, '48', 20, 6000.00),
(326, 115, '36', 10, 1200.00),
(327, 115, '37', 5, 1300.00),
(328, 115, '38', 5, 1400.00),
(329, 115, '39', 5, 1500.00),
(330, 115, '40', 5, 1600.00),
(331, 114, 'xsmall', 5, 100.00),
(332, 114, 'small', 5, 200.00),
(333, 114, 'medium', 5, 200.00),
(334, 114, 'large', 5, 100.00),
(335, 114, 'xlarge', 5, 100.00),
(336, 114, 'xxlarge', 5, 300.00),
(337, 113, 'red', 10, 150.00),
(338, 113, 'blue', 5, 150.00),
(339, 113, 'violet', 5, 150.00),
(340, 113, 'green', 5, 150.00),
(341, 113, 'black', 5, 200.00),
(342, 113, 'white', 10, 200.00),
(343, 113, 'brown', 10, 200.00),
(344, 113, 'yellow', 10, 150.00),
(345, 112, 'xs', 10, 180.00),
(346, 112, 's', 5, 180.00),
(347, 112, 'm', 5, 180.00),
(348, 112, 'l', 5, 199.00),
(349, 112, 'xl', 5, 199.00),
(350, 112, 'xxl', 10, 199.00),
(351, 111, 'sweet', 10, 350.00),
(352, 111, 'sweet & spicy', 10, 400.00),
(353, 111, 'sour', 10, 450.00),
(354, 111, 'spicy', 10, 500.00),
(355, 111, 'super spicy', 10, 550.00),
(356, 110, '12g', 5, 100.00),
(357, 110, '45g', 10, 200.00),
(358, 110, '60g', 3, 300.00),
(359, 110, '90', 6, 400.00),
(360, 109, '30g', 5, 120.00),
(361, 109, '40g', 10, 300.00),
(362, 109, '60g', 12, 400.00),
(363, 109, '70g', 20, 500.00),
(364, 108, 'blue', 20, 1000.00),
(365, 108, 'red', 5, 1000.00),
(366, 108, 'yellow', 10, 2000.00),
(367, 108, 'brown', 4, 1000.00),
(368, 107, 'natural titanium', 30, 200000.00),
(369, 107, 'blue titanium', 3, 130870.00),
(370, 107, 'white titanium', 10, 130789.00),
(371, 107, 'titanium', 3, 300000.00),
(372, 106, 'red', 4, 100.00),
(373, 106, 'green', 10, 200.00),
(374, 106, 'blue', 3, 150.00),
(375, 106, 'yellow', 12, 200.00),
(376, 105, '6 set', 5, 1000.00),
(377, 105, '12 set', 5, 2000.00),
(378, 105, '32 set', 20, 3000.00),
(379, 104, 'gold', 3, 100.00),
(380, 104, 'rose gold', 10, 100.00),
(381, 104, 'brown', 12, 130.00),
(382, 104, 'lime', 3, 140.00),
(383, 103, 'maroon', 3, 150.00),
(384, 103, 'red', 5, 130.00),
(385, 103, 'pink', 3, 130.00),
(386, 102, 'small', 4, 1000.00),
(387, 102, 'big', 10, 2000.00),
(388, 102, 'extra big', 7, 3000.00),
(397, 126, 'red', 10, 150.00),
(398, 126, 'blue', 10, 150.00),
(399, 126, 'green', 10, 150.00),
(400, 126, 'yellow', 10, 100.00),
(401, 126, 'violet', 10, 100.00),
(402, 126, 'orange', 10, 100.00),
(403, 127, 'natural titanium', 10, 130870.00),
(404, 127, 'white titanium', 10, 130870.00),
(405, 127, 'blue titanium', 10, 130870.00),
(406, 127, 'titanium', 30, 120870.00),
(407, 128, '60 keys', 10, 1200.00),
(408, 128, '80 keys', 10, 2500.00),
(409, 128, '104 keys', 10, 3500.00),
(410, 128, '120 keys', 10, 4000.00),
(411, 129, 'red', 10, 150.00),
(412, 129, 'blue', 10, 150.00),
(413, 129, 'yellow', 10, 150.00),
(414, 129, 'pink', 10, 150.00),
(415, 129, 'white', 10, 199.00),
(416, 129, 'black', 10, 199.00),
(434, 133, 'lay', 10, 1200.00),
(435, 133, 'sit', 10, 1200.00),
(436, 133, 'happy', 10, 1000.00),
(437, 133, 'sad', 5, 1000.00),
(438, 133, 'angry', 5, 1000.00),
(439, 134, 'neon', 10, 350.00),
(440, 134, 'gold', 10, 450.00),
(441, 134, 'pastel', 10, 250.00),
(442, 134, 'normal', 5, 199.00),
(443, 134, 'silver', 5, 379.00),
(444, 128, '180 keys', 10, 5000.00),
(445, 134, 'small', 3, 100.00),
(447, 135, 'natural', 17, 120.00),
(448, 135, 'glossy', 19, 150.00),
(449, 135, 'matte', 3, 170.00),
(450, 135, 'pastel', 4, 130.00),
(451, 136, 'red', 10, 100.00),
(452, 136, 'yellow', 10, 100.00),
(453, 137, 'dog', 10, 100.00),
(454, 137, 'cat', 10, 120.00),
(455, 137, 'goat', 10, 120.00),
(456, 137, 'bear', 5, 100.00),
(457, 138, 'red', 6, 100.00),
(458, 138, 'yellow', 7, 120.00),
(459, 139, 'clear', 10, 100.00),
(460, 139, 'mermaid', 10, 100.00),
(461, 139, 'matte black', 10, 120.00),
(462, 140, '4.0L', 10, 1300.00),
(463, 140, '5.0L', 10, 1700.00),
(464, 141, 'white/blue', 10, 150.00),
(465, 141, 'blue/white', 10, 180.00),
(468, 143, '82 keys', 10, 1200.00),
(469, 143, '90 keys', 10, 1200.00),
(470, 144, '20', 10, 1500.00),
(471, 144, '21', 10, 1500.00),
(476, 147, '101', 10, 299.00),
(477, 147, '102', 10, 399.00),
(478, 148, 'Glossy ', 5, 299.00),
(479, 148, 'matte', 5, 399.00),
(480, 149, '31', 6, 2500.00),
(481, 149, '32', 10, 3000.00),
(484, 151, '185g/400g', 10, 1500.00),
(485, 151, '250g/500g', 10, 2500.00),
(486, 152, '20g', 16, 1000.00),
(487, 152, '40g', 17, 2000.00),
(488, 151, '500g/700g', 10, 3500.00),
(493, 155, 'kid', 10, 100.00),
(494, 155, 'boy', 10, 199.00),
(495, 156, 'bear', 10, 199.00),
(496, 156, 'rabbit', 10, 200.00),
(497, 157, 'xs max', 10, 190.00),
(498, 157, '11 pro max', 10, 220.00),
(499, 158, 'screen', 10, 1899.00),
(500, 158, 'no screen', 10, 3900.00),
(501, 159, 'CD', 90, 1900.00),
(502, 159, 'Vinyl', 15, 2000.00),
(505, 159, 'merch', 40, 400.00),
(524, 103, 'violet', 10, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `email_verified_at` datetime(6) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` varchar(255) DEFAULT NULL,
  `last_attempt` timestamp NOT NULL DEFAULT current_timestamp(),
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `contact_number` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `verification_code`, `email_verified_at`, `reset_token`, `reset_token_expiration`, `last_attempt`, `blocked`, `attempts`, `contact_number`, `address`, `image_path`, `first_name`, `middle_name`, `last_name`) VALUES
(50, 'ajrys', 'albertjasonreyes1996@gmail.com', '$2y$10$s3MHDlj1EtBRnkPtgc4ut.CZh05wX6c4V7Tme.T1GvkQVODopEDlW', 107425, '2023-12-06 16:18:55.000000', '', '', '2023-12-06 08:18:14', 0, 0, '09496563656', 'Plaridel Bulacan', 'img/657035e6ae941.jpg', 'Albert Jason Reyes', 'Ignacio', 'Reyes'),
(51, 'itsmy', 'Ybiza2018@gmail.com', '$2y$10$5Vi8ZjpRpNZ7nCnQkpOWdOStcG9iuK.ItS0HULNcHoG9t2AgWQkuu', 316542, '2023-12-07 22:59:40.000000', '', '', '2023-12-07 14:59:29', 0, 0, '09551041534', 'Liciada Bustos Bulacan', 'img/664d73cebde02.jpeg', 'Maika', 'Ybiza', 'Simbulan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `design_settings`
--
ALTER TABLE `design_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `design_settings`
--
ALTER TABLE `design_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=525;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
