-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 21, 2025 at 12:50 AM
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
-- Database: `pharmanest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(256) NOT NULL,
  `date_received` date NOT NULL DEFAULT current_timestamp(),
  `expiration_date` date NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `received_quantity` int(11) NOT NULL,
  `date_disposed` date DEFAULT NULL,
  `disposed_quantity` int(11) DEFAULT NULL,
  `batch_cost` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`id`, `reference_number`, `date_received`, `expiration_date`, `supplier_id`, `product_id`, `employee_id`, `received_quantity`, `date_disposed`, `disposed_quantity`, `batch_cost`) VALUES
(1, 'B20250101-001', '2025-01-21', '2027-01-01', 1, 1, 3, 200, NULL, NULL, 4),
(2, 'B20250101-002', '2025-01-21', '2027-01-01', 1, 3, 3, 100, NULL, NULL, 11),
(3, 'B20250101-003', '2025-01-21', '2026-01-01', 1, 2, 3, 100, NULL, NULL, 9);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'For Kids'),
(2, 'Antimotility'),
(3, 'Paracetamol'),
(4, 'Pain Reliever'),
(5, 'Tablet'),
(6, 'Capsule'),
(7, 'Syrup'),
(8, 'Antibacterial');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `contact_number` varchar(25) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `sex` varchar(7) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `first_name`, `last_name`, `contact_number`, `user_id`, `address`, `date_of_birth`, `sex`, `is_active`) VALUES
(1, 'Customer A', 'Last Name A', '09123456789', 2, 'Address A', '1991-01-01', 'Female', 1),
(2, 'Customer B', 'Last Name B', '09193152214', 5, 'Address B', '1992-02-02', 'Male', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_cart`
--

CREATE TABLE `customer_cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `selected_discount` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_cart`
--

INSERT INTO `customer_cart` (`id`, `customer_id`, `selected_discount`) VALUES
(1, 2, 'Senior Citizen'),
(2, 1, 'Person With Disabilities');

-- --------------------------------------------------------

--
-- Table structure for table `customer_order`
--

CREATE TABLE `customer_order` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_ordered` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(25) NOT NULL,
  `reference_number` varchar(256) NOT NULL,
  `selected_discount` varchar(128) NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_prescription`
--

CREATE TABLE `customer_prescription` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `reference_name` varchar(256) DEFAULT NULL,
  `prescription_photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `contact_number` varchar(25) NOT NULL,
  `address` text NOT NULL,
  `date_of_birth` date NOT NULL,
  `job_title` varchar(128) NOT NULL,
  `employment_date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `first_name`, `last_name`, `contact_number`, `address`, `date_of_birth`, `job_title`, `employment_date`, `user_id`) VALUES
(1, 'Super', 'Admin', 'Not Applicable', 'Not Applicable', '2025-01-16', 'Not Applicable', '2025-01-16', 1),
(2, 'Pharmacist A', 'Last Name A', '09123456788', 'Address A', '2002-02-02', 'Pharmacist', '2025-02-02', 3),
(3, 'Manager A', 'Last Name A', '09123456787', 'Address A', '2003-03-03', 'Manager', '2024-03-03', 4),
(4, 'Pharmacist B', 'Last Name B', '09193152216', 'Address B', '1992-02-02', 'Pharmacist', '2002-02-02', 6);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `object_type` varchar(128) NOT NULL,
  `object_id` int(11) NOT NULL,
  `remarks` varchar(256) NOT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `object_type`, `object_id`, `remarks`, `date_recorded`, `user_id`) VALUES
(1, 'product', 1, 'Add Stock: 200 quantity B20250101-001', '2025-01-20 22:44:03', 4),
(2, 'product', 3, 'Add Stock: 100 quantity B20250101-002', '2025-01-20 22:52:21', 4),
(3, 'product', 2, 'Add Stock: 100 quantity B20250101-003', '2025-01-20 22:53:13', 4);

-- --------------------------------------------------------

--
-- Table structure for table `pos_cart`
--

CREATE TABLE `pos_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selected_discount` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_cart`
--

INSERT INTO `pos_cart` (`id`, `user_id`, `selected_discount`) VALUES
(1, 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescription_history`
--

CREATE TABLE `prescription_history` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Biogesic', 5, 200, 'Both', 0, 'http://localhost/pharmanest/assets/photos/biogesic.png', 'Location 1', 50, 4),
(2, 'Loperamide', 10, 100, 'Both', 0, 'http://localhost/pharmanest/assets/photos/loperamide.png', 'Location 1', 50, 9),
(3, 'RiteMed Amoxicillin', 12, 100, 'Both', 1, 'http://localhost/pharmanest/assets/photos/amoxicillin.jpg', 'Location 1', 50, 11),
(4, 'Tempra for Kids', 120, 0, 'Person With Disabilities', 0, 'http://localhost/pharmanest/assets/photos/tempra_for_kids.png', 'Location 2', 50, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_ids` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_ids`) VALUES
(1, 1, '3,4,5'),
(2, 2, '2,6'),
(3, 3, '6,8'),
(4, 4, '1,3,4');

-- --------------------------------------------------------

--
-- Table structure for table `product_line`
--

CREATE TABLE `product_line` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `for_checkout` tinyint(1) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `line_type` varchar(128) NOT NULL,
  `line_price` double DEFAULT NULL,
  `pos_cart_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_line`
--

INSERT INTO `product_line` (`id`, `product_id`, `cart_id`, `order_id`, `qty`, `for_checkout`, `transaction_id`, `line_type`, `line_price`, `pos_cart_id`) VALUES
(1, 1, 1, NULL, 5, 1, NULL, 'cart', NULL, NULL),
(2, 3, 2, NULL, 1, 1, NULL, 'cart', NULL, NULL),
(3, 2, 2, NULL, 1, 1, NULL, 'cart', NULL, NULL),
(4, 3, 1, NULL, 1, 1, NULL, 'cart', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_prescription`
--

CREATE TABLE `product_prescription` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`) VALUES
(1, 'Supplier 1');

-- --------------------------------------------------------

--
-- Table structure for table `temporary_record`
--

CREATE TABLE `temporary_record` (
  `id` int(11) NOT NULL,
  `reference_key` varchar(128) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `deletion_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `employee_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `receipt_reference` text NOT NULL,
  `selected_discount` varchar(128) NOT NULL,
  `reference_number` varchar(128) NOT NULL,
  `amount_paid` double NOT NULL,
  `total` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  `role` varchar(25) NOT NULL,
  `password_length` int(11) NOT NULL DEFAULT 8,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`, `password_length`, `is_active`) VALUES
(1, 'admin', 'pharmanest123@gmail.com', '$2y$10$glqipyCUEAiVZFA5DgQ4Kelr/27n/9XmNNAOp/JAOqomJgbVda2PO', 'super admin', 15, 1),
(2, 'Customer A_Last Name A_20250120234138', 'customer.a@pharmanest.com', '$2y$10$AafyPtEIFkuJSMQvNlT8I.Q8AdVtLt3b5c7wtR1qDkruzY88FtRW.', 'customer', 8, 1),
(3, 'Pharmacist A_Last Name A_20250120234250', 'pharmacist.a@pharmanest.com', '$2y$10$oA43cvtryEtuGoemFPbdVO0NvZiiEbJMACPN65NYQIy6FjuQUg.MO', 'pharmacist', 8, 1),
(4, 'Manager A_Last Name A_20250118130018', 'manager@pharmanest.com', '$2y$10$bYWVrQzaFcA..xenMAwKpOJWiPzD/Fq/wyQnXPiEg9ft9F3c5e7gG', 'admin', 8, 1),
(5, 'Customer B_Last Name B_20250120234127', 'customer.b@pharmanest.com', '$2y$10$Kofnpq3JCeHr4qvFrRL8Jed5f6aUUCL7ckMEbfWu7ZGMAYeuU7.y.', 'customer', 8, 1),
(6, 'Pharmacist B_Last Name B_20250120234240', 'pharmacist.b@pharmanest.com', '$2y$10$yIptwLYlodFh7QPWQ3/gW.QkflUOp.BQx1Xd1UOSeiIQRFCAK.jmO', 'pharmacist', 8, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_supplier` (`supplier_id`) USING BTREE,
  ADD KEY `product` (`product_id`),
  ADD KEY `recorded_by` (`employee_id`) USING BTREE;

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_login` (`user_id`) USING BTREE;

--
-- Indexes for table `customer_cart`
--
ALTER TABLE `customer_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_owner` (`customer_id`);

--
-- Indexes for table `customer_order`
--
ALTER TABLE `customer_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordered_by` (`customer_id`);

--
-- Indexes for table `customer_prescription`
--
ALTER TABLE `customer_prescription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient` (`customer_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_login` (`user_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recorded_by` (`user_id`);

--
-- Indexes for table `pos_cart`
--
ALTER TABLE `pos_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_cart` (`user_id`);

--
-- Indexes for table `prescription_history`
--
ALTER TABLE `prescription_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescribed_product` (`product_id`),
  ADD KEY `prescription` (`prescription_id`),
  ADD KEY `transaction` (`transaction_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product` (`product_id`),
  ADD KEY `category` (`category_ids`(768));

--
-- Indexes for table `product_line`
--
ALTER TABLE `product_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product` (`product_id`),
  ADD KEY `cart` (`cart_id`),
  ADD KEY `order` (`order_id`),
  ADD KEY `transaction` (`transaction_id`);

--
-- Indexes for table `product_prescription`
--
ALTER TABLE `product_prescription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product` (`product_id`),
  ADD KEY `prescription` (`prescription_id`),
  ADD KEY `customer` (`customer_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temporary_record`
--
ALTER TABLE `temporary_record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transacted_by` (`employee_id`),
  ADD KEY `order_reference` (`order_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_cart`
--
ALTER TABLE `customer_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_order`
--
ALTER TABLE `customer_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_prescription`
--
ALTER TABLE `customer_prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_cart`
--
ALTER TABLE `pos_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescription_history`
--
ALTER TABLE `prescription_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_line`
--
ALTER TABLE `product_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_prescription`
--
ALTER TABLE `product_prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temporary_record`
--
ALTER TABLE `temporary_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batch`
--
ALTER TABLE `batch`
  ADD CONSTRAINT `batch_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `batch_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `batch_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_cart`
--
ALTER TABLE `customer_cart`
  ADD CONSTRAINT `customer_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_order`
--
ALTER TABLE `customer_order`
  ADD CONSTRAINT `customer_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `customer_prescription`
--
ALTER TABLE `customer_prescription`
  ADD CONSTRAINT `customer_prescription_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `prescription_history`
--
ALTER TABLE `prescription_history`
  ADD CONSTRAINT `prescription_history_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_history_ibfk_3` FOREIGN KEY (`prescription_id`) REFERENCES `customer_prescription` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `prescription_history_ibfk_4` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_line`
--
ALTER TABLE `product_line`
  ADD CONSTRAINT `product_line_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_line_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `customer_order` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_line_ibfk_4` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_prescription`
--
ALTER TABLE `product_prescription`
  ADD CONSTRAINT `product_prescription_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `customer_prescription` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_prescription_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_prescription_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
