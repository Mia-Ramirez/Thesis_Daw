-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 18, 2025 at 01:11 AM
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
(1, 'B20250110S001', '2025-01-10', '2027-01-10', 1, 1, 2, 100, NULL, NULL, 4),
(2, 'B20250110S002', '2025-01-10', '2027-01-08', 1, 2, 2, 50, NULL, NULL, 8),
(3, 'B1', '2025-01-13', '2025-01-15', 1, 2, 2, 15, '2025-01-13', 15, 8),
(4, 'B2', '2025-01-13', '2026-01-15', 1, 2, 2, 10, NULL, NULL, 8);

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
(1, 'Paracetamol'),
(2, 'Pain Reliever'),
(3, 'Tablet'),
(4, 'Antimotility'),
(5, 'Capsule');

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
(1, 'First Name A', 'Last Name A', '09196152311', 2, 'Address A', '1991-01-11', 'Male', 1);

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
(1, 1, 'Person With Disabilities');

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

--
-- Dumping data for table `customer_order`
--

INSERT INTO `customer_order` (`id`, `customer_id`, `date_ordered`, `status`, `reference_number`, `selected_discount`, `remarks`) VALUES
(1, 1, '2025-01-11 11:41:54', 'cancelled', '20250111044154-1', '', 'wrong quantity (Cancelled by Customer)'),
(2, 1, '2025-01-11 11:42:18', 'picked_up', '20250111044218-1', '', NULL),
(3, 1, '2025-01-11 11:42:41', 'picked_up', '20250111044241-1', '', NULL),
(4, 1, '2025-01-11 11:55:15', 'picked_up', '20250111045515-1', 'Person With Disabilities', NULL);

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
(1, 'Pharmacist A', 'Last Name A', '09133136217', 'Address A', '1990-01-11', 'Pharmacist', '2002-01-08', 3),
(2, 'Manager A', 'Last Name A', '09133136213', 'Address A', '1986-01-11', 'Manager', '2000-02-06', 4),
(3, 'Super', 'Admin', 'Not Applicable', 'Not Applicable', '2025-01-16', 'Not Applicable', '2025-01-16', 1);

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
(1, 'order', 1, 'Order Placed', '2025-01-11 03:41:54', 2),
(2, 'order', 2, 'Order Placed', '2025-01-11 03:42:18', 2),
(3, 'order', 3, 'Order Placed', '2025-01-11 03:42:41', 2),
(4, 'order', 1, 'Cancelled by Customer: wrong quantity', '2025-01-11 03:44:23', 2),
(5, 'order', 2, 'Moved to \"Preparing\"', '2025-01-11 03:45:20', 3),
(6, 'order', 2, 'Moved to \"For Pickup\"', '2025-01-11 03:45:27', 3),
(7, 'order', 3, 'Moved to \"Preparing\"', '2025-01-11 03:45:37', 3),
(8, 'order', 3, 'Moved to \"For Pickup\"', '2025-01-11 03:45:38', 3),
(9, 'order', 4, 'Order Placed', '2025-01-11 03:55:15', 2),
(10, 'order', 4, 'Moved to \"Preparing\"', '2025-01-11 03:55:56', 3),
(11, 'order', 4, 'Moved to \"For Pickup\"', '2025-01-11 03:55:57', 3),
(12, 'transaction', 1, 'Transacted: 1 item(s) TR#20250111-115901', '2025-01-13 15:16:21', 4),
(13, 'transaction', 2, 'Transacted: 1 item(s) TR#20250111-120301', '2025-01-13 15:16:34', 3),
(14, 'transaction', 3, 'Transacted: 1 item(s) TR#20250111-12070', '2025-01-13 15:16:48', 3),
(15, 'product', 1, 'Add Stock: 100 quantity B20250110S001', '2025-01-11 04:14:02', 3),
(16, 'product', 2, 'Add Stock: 50 quantity B20250110S001', '2025-01-11 04:15:28', 3),
(17, 'order', 3, 'Moved to \"Picked-up\"', '2025-01-11 04:49:01', 3),
(18, 'order', 4, 'Moved to \"Picked-up\"', '2025-01-11 04:49:31', 3),
(19, 'product', 1, 'Sold: 20 quantity SR#000001', '2025-01-13 15:17:04', 4),
(20, 'product', 2, 'Sold: 2 quantity SR#000002', '2025-01-13 15:17:17', 1),
(21, 'product', 2, 'Sold: 1 quantity SR#000003', '2025-01-13 15:17:30', 3),
(22, 'product', 2, 'Add Stock: 15 quantity B1', '2025-01-13 15:38:57', 1),
(23, 'product', 2, 'Add Stock: 10 quantity B2', '2025-01-13 15:39:33', 1),
(24, 'product', 2, 'Disposed Stock: 15 quantity B1', '2025-01-13 15:46:05', 1),
(25, 'transaction', 4, 'Transacted: 1 item(s) TR#20250113-164702', '2025-01-13 15:47:02', 3),
(26, 'order', 2, 'Moved to \"Picked-up\"', '2025-01-13 15:47:02', 3),
(27, 'transaction', 5, 'Transacted: 2 item(s) TR#20250114-014938', '2025-01-14 00:49:38', 3),
(28, 'transaction', 6, 'Transacted: 2 item(s) TR#20250114-140712', '2025-01-14 13:07:12', 3);

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
(1, 3, NULL),
(2, 1, NULL),
(3, 4, NULL);

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
(1, 'Biogesic', 5, 80, 'None', 0, 'http://localhost/pharmanest/assets/photos/biogesic.png', 'Location 1', 50, 4),
(2, 'Loperamide', 10, 57, 'Both', 0, 'http://localhost/pharmanest/assets/photos/loperamide.png', 'Location 2', 50, 8);

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
(1, 1, '1,2,3'),
(2, 2, '4,5');

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
(1, 1, NULL, 1, 1, 0, NULL, 'order', NULL, NULL),
(2, 1, NULL, 2, 2, 0, 4, 'transaction', 5, NULL),
(3, 1, NULL, 3, 20, 0, 1, 'transaction', 5, NULL),
(4, 2, NULL, 4, 2, 0, 2, 'transaction', 10, NULL),
(5, 2, NULL, NULL, 1, NULL, 3, 'transaction', 10, NULL),
(6, 1, NULL, NULL, 1, 0, 5, 'transaction', 5, NULL),
(7, 2, NULL, NULL, 1, 0, 5, 'transaction', 10, NULL),
(8, 2, NULL, NULL, 1, 0, 6, 'transaction', 10, NULL),
(9, 1, NULL, NULL, 1, 0, 6, 'transaction', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_prescription`
--

CREATE TABLE `product_prescription` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `cart_id` int(11) NOT NULL
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
  `amount_paid` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `transaction_date`, `employee_id`, `order_id`, `receipt_reference`, `selected_discount`, `reference_number`, `amount_paid`) VALUES
(1, '2025-01-11 11:59:48', 2, 3, 'SR#000001', '', 'TR#20250111-115901', 100),
(2, '2025-01-11 12:04:07', 1, 4, 'SR#000002', 'Person With Disabilities', 'TR#20250111-120301', 16),
(3, '2025-01-11 12:07:51', 1, NULL, 'SR#000003', 'Senior Citizen', 'TR#20250111-120701', 10),
(4, '2025-01-13 23:47:02', 1, 2, 'SR#000004', '', 'TR#20250113-164702', 10),
(5, '2025-01-14 08:49:38', 1, NULL, 'SR#000005', 'Person With Disabilities', 'TR#20250114-014938', 15),
(6, '2025-01-14 21:07:12', 1, NULL, 'SR#000006', '', 'TR#20250114-140712', 20);

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
(2, 'First Name A_Last Name A_20250111042941', 'customer@pharmanest.com', '$2y$10$df.vWieSmeR4IW8R7STIIeTWLGsfpMIxHeR.bMVIbeX0eY/BW8que', 'customer', 8, 1),
(3, 'Pharmacist A_Last Name A_20250111043038', 'pharmacist@pharmanest.com', '$2y$10$2MZs9H8CON6fLq8thlknAOwJUiN1PdqUay6CLWGrtUIE1iW29qX5K', 'pharmacist', 8, 1),
(4, 'Manager A_Last Name A_20250111043117', 'manager@pharmanest.com', '$2y$10$R.TCzFfQLeH2ogQFRn1IKuIOjvsN2FrYAjMLWfuiIl5FomSzWezkO', 'admin', 8, 1);

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
  ADD KEY `cart` (`cart_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_cart`
--
ALTER TABLE `customer_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_order`
--
ALTER TABLE `customer_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_prescription`
--
ALTER TABLE `customer_prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `pos_cart`
--
ALTER TABLE `pos_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prescription_history`
--
ALTER TABLE `prescription_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_line`
--
ALTER TABLE `product_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_prescription`
--
ALTER TABLE `product_prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temporary_record`
--
ALTER TABLE `temporary_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `product_prescription_ibfk_3` FOREIGN KEY (`cart_id`) REFERENCES `customer_cart` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION;
  
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
