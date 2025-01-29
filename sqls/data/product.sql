-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 11:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmanest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `price` double NOT NULL,
  `current_quantity` int(11) NOT NULL DEFAULT 0,
  `applicable_discounts` varchar(128) NOT NULL,
  `prescription_is_required` tinyint(1) NOT NULL,
  `photo` text NOT NULL,
  `rack_location` text NOT NULL,
  `maintaining_quantity` int(11) NOT NULL,
  `cost` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `current_quantity`, `applicable_discounts`, `prescription_is_required`, `photo`, `rack_location`, `maintaining_quantity`, `cost`) VALUES
(1, 'Biogesic', 5, 180, 'None', 0, 'http://localhost/pharmanest/assets/photos/biogesic.png', 'Location 1', 50, 4),
(2, 'Loperamide', 10, 60, 'Both', 0, 'http://localhost/pharmanest/assets/photos/loperamide.png', '2', 50, 8),
(3, 'Tissue Paper', 20, 100, 'Both', 0, 'http://localhost/pharmanest/assets/photos/678b073711d69-sg-11134201-23010-gbh1i5ksgrmva2.jpg', '7', 100, 15),
(4, 'Amoxiclav', 30, 100, 'Both', 1, 'http://localhost/pharmanest/assets/photos/678b080e6cc9a-amoxicillin-500-mg-clavulanic-125-mg-tablets.jpg', '5', 100, 25),
(5, 'Tempra for Kids', 150, 100, 'None', 0, 'http://localhost/pharmanest/assets/photos/6793c60627571-photo_6266810541808928232_y.jpg', '4', 100, 125);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
