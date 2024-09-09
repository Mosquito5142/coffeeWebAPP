-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 09, 2024 at 04:29 AM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffeeapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '1234'),
(2, 'root', 'rootroot');

-- --------------------------------------------------------

--
-- Table structure for table `coffees`
--

CREATE TABLE `coffees` (
  `coffee_id` int(11) NOT NULL,
  `coffee_name` varchar(100) NOT NULL,
  `coffee_price` decimal(10,2) NOT NULL,
  `coffee_image` varchar(255) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `coffees`
--

INSERT INTO `coffees` (`coffee_id`, `coffee_name`, `coffee_price`, `coffee_image`, `type_id`) VALUES
(1, 'ลาเต้คาราเมล', '64.00', 'img/1.jpg', 1),
(2, 'เอสเปรสโซ่ดับเบิ้ล', '55.00', 'img/2.jpg', 1),
(3, 'มอคค่าช็อคโกแลต', '70.00', 'img/3.jpg', 1),
(5, 'ชานมเย็น', '50.00', 'img/5.jpg', 13),
(6, 'ขนม', '1.00', 'img/6.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `coffee_types`
--

CREATE TABLE `coffee_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `coffee_types`
--

INSERT INTO `coffee_types` (`type_id`, `type_name`) VALUES
(1, 'กาแฟ'),
(13, 'ชา');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `total_amount`, `tax_amount`, `discount_amount`) VALUES
(91, '2024-09-09 08:57:24', '280.00', '0.00', '0.00'),
(92, '2024-09-09 09:01:11', '119.00', '0.00', '0.00'),
(93, '2024-09-09 09:13:40', '65.00', '0.00', '0.00'),
(94, '2024-09-09 10:23:35', '20.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `coffee_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `coffee_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 91, 3, 4, '70.00', '280.00'),
(2, 92, 1, 1, '64.00', '64.00'),
(3, 92, 2, 1, '55.00', '55.00'),
(4, 93, 1, 1, '64.00', '64.00'),
(5, 93, 6, 1, '1.00', '1.00'),
(6, 94, 6, 20, '1.00', '20.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coffees`
--
ALTER TABLE `coffees`
  ADD PRIMARY KEY (`coffee_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `coffee_types`
--
ALTER TABLE `coffee_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `coffee_id` (`coffee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coffees`
--
ALTER TABLE `coffees`
  MODIFY `coffee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coffee_types`
--
ALTER TABLE `coffee_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coffees`
--
ALTER TABLE `coffees`
  ADD CONSTRAINT `coffees_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `coffee_types` (`type_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`coffee_id`) REFERENCES `coffees` (`coffee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
