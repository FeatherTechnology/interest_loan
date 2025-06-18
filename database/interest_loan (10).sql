-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 12:20 PM
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
-- Database: `interest_loan`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_collect_entry`
--

CREATE TABLE `accounts_collect_entry` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `line` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `collection_mode` int(11) NOT NULL,
  `bank_id` varchar(50) DEFAULT NULL,
  `no_of_bills` int(11) NOT NULL,
  `collection_amnt` varchar(150) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts_collect_entry`
--

INSERT INTO `accounts_collect_entry` (`id`, `user_id`, `line`, `branch`, `collection_mode`, `bank_id`, `no_of_bills`, `collection_amnt`, `insert_login_id`, `created_on`) VALUES
(1, 1, 'L1', 'Villianur', 1, '', 4, '225000', 1, '2025-06-17 11:23:03');

-- --------------------------------------------------------

--
-- Table structure for table `agent_creation`
--

CREATE TABLE `agent_creation` (
  `id` int(11) NOT NULL,
  `agent_code` varchar(100) NOT NULL,
  `agent_name` varchar(100) NOT NULL,
  `mobile1` varchar(100) NOT NULL,
  `mobile2` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `insert_login_id` varchar(100) NOT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent_creation`
--

INSERT INTO `agent_creation` (`id`, `agent_code`, `agent_name`, `mobile1`, `mobile2`, `area`, `occupation`, `insert_login_id`, `update_login_id`, `created_date`, `updated_date`) VALUES
(6, 'AG-101', 'vasanth', '9678768678', '', '', '', '1', NULL, '2025-05-22 15:40:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `area_creation`
--

CREATE TABLE `area_creation` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `update_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area_creation`
--

INSERT INTO `area_creation` (`id`, `branch_id`, `line_id`, `status`, `insert_login_id`, `update_login_id`, `created_on`, `update_on`) VALUES
(4, 7, 15, 1, 1, 1, '2025-05-23 16:49:15', '2025-05-23'),
(5, 8, 17, 1, 1, 1, '2025-05-23 17:59:58', '2025-05-23');

-- --------------------------------------------------------

--
-- Table structure for table `area_creation_area_name`
--

CREATE TABLE `area_creation_area_name` (
  `id` int(11) NOT NULL,
  `area_creation_id` int(25) NOT NULL,
  `area_id` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area_creation_area_name`
--

INSERT INTO `area_creation_area_name` (`id`, `area_creation_id`, `area_id`) VALUES
(18, 4, 14),
(21, 4, 16),
(22, 4, 17),
(23, 4, 15),
(24, 5, 19),
(25, 5, 18),
(26, 5, 20);

-- --------------------------------------------------------

--
-- Table structure for table `area_name_creation`
--

CREATE TABLE `area_name_creation` (
  `id` int(11) NOT NULL,
  `areaname` varchar(200) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area_name_creation`
--

INSERT INTO `area_name_creation` (`id`, `areaname`, `branch_id`, `status`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(14, 'Chennai', 7, 1, 1, NULL, '2025-05-23 16:48:25', NULL),
(15, 'Trichy', 7, 1, 1, NULL, '2025-05-23 16:48:32', NULL),
(16, 'Combiatore', 7, 1, 1, NULL, '2025-05-23 16:48:39', NULL),
(17, 'Salem', 7, 1, 1, NULL, '2025-05-23 16:57:22', NULL),
(18, 'Velachery', 8, 1, 1, NULL, '2025-05-23 17:59:00', NULL),
(19, 'Vandalur', 8, 1, 1, NULL, '2025-05-23 17:59:20', NULL),
(20, 'Thiruvanamalai', 8, 1, 1, NULL, '2025-05-23 18:04:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bank_creation`
--

CREATE TABLE `bank_creation` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_short_name` varchar(100) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `ifsc_code` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `qr_code` varchar(100) DEFAULT NULL,
  `gpay` varchar(100) DEFAULT NULL,
  `under_branch` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT '1',
  `insert_login_id` varchar(100) NOT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `delete_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_creation`
--

INSERT INTO `bank_creation` (`id`, `bank_name`, `bank_short_name`, `account_number`, `ifsc_code`, `branch_name`, `qr_code`, `gpay`, `under_branch`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 'State Bank of India', 'SBI', '6567567', '575676', 'Feather Technology', '', '9767567567', '8,7', '1', '1', NULL, NULL, '2025-08-10 17:12:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bank_info`
--

CREATE TABLE `bank_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `acc_holder_name` varchar(100) NOT NULL,
  `acc_number` varchar(100) NOT NULL,
  `ifsc_code` varchar(100) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_info`
--

INSERT INTO `bank_info` (`id`, `cus_id`, `bank_name`, `branch_name`, `acc_holder_name`, `acc_number`, `ifsc_code`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-101', 'State Bank of India', 'Villianur', 'vasanth', '967878', '6788788', 1, NULL, '2025-06-10', NULL),
(2, 'C-102', 'Indian Bank', 'Pondicherry', 'Vijay', '9886787', '867868', 1, NULL, '2025-06-10', NULL),
(3, 'C-103', 'Axis Bank', 'Madurai', 'Ajith', '8567767', '67567', 1, NULL, '2025-10-11', NULL),
(4, 'C-104', 'Indian Bank', 'Villianur', 'Vikaram', '979787', '7897898', 1, NULL, '2025-06-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_creation`
--

CREATE TABLE `branch_creation` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `branch_code` varchar(50) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `district` int(11) NOT NULL,
  `taluk` int(11) NOT NULL,
  `place` varchar(100) NOT NULL,
  `pincode` varchar(100) NOT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(100) DEFAULT NULL,
  `landline_code` varchar(50) DEFAULT NULL,
  `landline` varchar(100) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_creation`
--

INSERT INTO `branch_creation` (`id`, `company_name`, `branch_code`, `branch_name`, `address`, `state`, `district`, `taluk`, `place`, `pincode`, `email_id`, `mobile_number`, `whatsapp`, `landline_code`, `landline`, `insert_login_id`, `update_login_id`, `created_date`, `updated_date`) VALUES
(7, 'Feather Technology', 'F-101', 'Villianur', 'No.8 Mullakulam', 2, 39, 317, 'Arumathupuram', '605110', 'vasanth123@gmail.com', '9867867867', '9687885675', '43354', '54645645', 1, NULL, '2025-05-23 09:56:22', NULL),
(8, 'Feather Technology', 'F-102', 'Pondicherry', 'Bussy Street', 1, 1, 2, 'Vandavasi', '605110', '', '', '', '', '', 1, NULL, '2025-05-23 15:45:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cheque_info`
--

CREATE TABLE `cheque_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `cus_profile_id` int(11) NOT NULL,
  `holder_type` int(11) NOT NULL,
  `holder_name` varchar(150) NOT NULL,
  `holder_id` varchar(25) DEFAULT NULL,
  `relationship` varchar(50) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `cheque_cnt` int(11) NOT NULL,
  `upload` varchar(255) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cheque_info`
--

INSERT INTO `cheque_info` (`id`, `cus_id`, `cus_profile_id`, `holder_type`, `holder_name`, `holder_id`, `relationship`, `bank_name`, `cheque_cnt`, `upload`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-102', 3, 1, 'Testing Two', '', 'Customer', 'SBI', 1, NULL, 1, NULL, '2025-06-16', NULL),
(2, 'C-102', 4, 1, 'Testing Two', '', 'Customer', 'Indian Bank', 1, NULL, 1, NULL, '2025-06-16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cheque_no_list`
--

CREATE TABLE `cheque_no_list` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `cus_profile_id` int(11) DEFAULT NULL,
  `cheque_info_id` int(11) DEFAULT NULL,
  `cheque_no` varchar(200) DEFAULT NULL,
  `used_status` int(11) NOT NULL DEFAULT 0,
  `noc_status` int(11) NOT NULL DEFAULT 0,
  `date_of_noc` date DEFAULT NULL,
  `noc_member` varchar(150) DEFAULT NULL,
  `noc_relationship` varchar(150) DEFAULT NULL,
  `insert_login_id` int(11) DEFAULT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cheque_no_list`
--

INSERT INTO `cheque_no_list` (`id`, `cus_id`, `cus_profile_id`, `cheque_info_id`, `cheque_no`, `used_status`, `noc_status`, `date_of_noc`, `noc_member`, `noc_relationship`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-102', 3, 1, '1000', 0, 1, '2025-06-16', '2', 'Brother', 1, 1, '2025-06-16', '2025-06-16'),
(2, 'C-102', 4, 2, '1000', 0, 1, '2025-06-16', 'Testing Two', 'Customer', 1, 1, '2025-06-16', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `cheque_upd`
--

CREATE TABLE `cheque_upd` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `cus_profile_id` int(11) DEFAULT NULL,
  `cheque_info_id` int(11) DEFAULT NULL,
  `uploads` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `loan_entry_id` int(11) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `collection_status` varchar(100) DEFAULT NULL,
  `coll_sub_status` varchar(255) DEFAULT NULL,
  `loan_amount` varchar(100) DEFAULT NULL,
  `paid_amount` varchar(255) DEFAULT NULL,
  `balance_amount` varchar(100) DEFAULT NULL,
  `interest_amount` varchar(100) DEFAULT NULL,
  `pending_amount` varchar(100) DEFAULT NULL,
  `payable_amount` varchar(100) DEFAULT NULL,
  `penalty` varchar(255) DEFAULT NULL,
  `fine_charge` varchar(255) DEFAULT NULL,
  `till_now_payable` varchar(100) DEFAULT NULL,
  `interest_amount_track` varchar(100) NOT NULL DEFAULT '0',
  `penalty_track` varchar(100) NOT NULL DEFAULT '0',
  `fine_charge_track` varchar(100) NOT NULL DEFAULT '0',
  `principal_amount_track` varchar(100) NOT NULL DEFAULT '0',
  `total_paid_track` varchar(100) NOT NULL DEFAULT '0',
  `interest_waiver` varchar(100) NOT NULL DEFAULT '0',
  `penalty_waiver` varchar(255) NOT NULL DEFAULT '0',
  `fine_charge_waiver` varchar(255) NOT NULL DEFAULT '0',
  `principal_waiver` varchar(100) NOT NULL DEFAULT '0',
  `total_waiver` varchar(255) NOT NULL DEFAULT '0',
  `collection_date` datetime NOT NULL,
  `collection_id` varchar(11) NOT NULL,
  `collection_mode` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `collect_sts` int(11) NOT NULL DEFAULT 0,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL COMMENT 'Create Time',
  `updated_date` datetime DEFAULT current_timestamp() COMMENT 'Update Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`id`, `loan_entry_id`, `cus_id`, `collection_status`, `coll_sub_status`, `loan_amount`, `paid_amount`, `balance_amount`, `interest_amount`, `pending_amount`, `payable_amount`, `penalty`, `fine_charge`, `till_now_payable`, `interest_amount_track`, `penalty_track`, `fine_charge_track`, `principal_amount_track`, `total_paid_track`, `interest_waiver`, `penalty_waiver`, `fine_charge_waiver`, `principal_waiver`, `total_waiver`, `collection_date`, `collection_id`, `collection_mode`, `bank_id`, `transaction_id`, `collect_sts`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 2, 'C-101', 'Present', 'Current', '75000', '0', '75000', '2250', '0', '0', '0', '0', NULL, '', '', '', '75000', '75000', '', '', '', '', '', '2025-06-13 18:00:18', 'COL-101', 1, 0, 0, 0, '1', NULL, NULL, '2025-06-13 18:00:18', '2025-06-13 18:00:18'),
(2, 1, 'C-101', 'Present', 'Current', '50000', '0', '50000', '1000', '0', '0', '0', '0', NULL, '', '', '', '50000', '50000', '', '', '', '', '', '2025-06-13 18:00:27', 'COL-102', 1, 0, 0, 0, '1', NULL, NULL, '2025-06-13 18:00:27', '2025-06-13 18:00:27'),
(3, 3, 'C-102', 'Present', 'Current', '50000', '0', '50000', '1000', '0', '0', '0', '0', NULL, '', '', '', '50000', '50000', '', '', '', '', '', '2025-06-16 11:48:35', 'COL-103', 1, 0, 0, 0, '1', NULL, NULL, '2025-06-16 11:48:35', '2025-06-16 11:48:35'),
(4, 4, 'C-102', 'Present', 'Current', '50000', '0', '50000', '1500', '0', '0', '0', '0', NULL, '', '', '', '50000', '50000', '', '', '', '', '', '2025-06-16 14:49:49', 'COL-104', 1, 0, 0, 0, '1', NULL, NULL, '2025-06-16 14:49:49', '2025-06-16 14:49:49'),
(5, 5, 'C-102', 'Present', 'Current', '40000', '0', '40000', '800', '0', '0', '0', '0', NULL, '', '', '', '40000', '40000', '', '', '', '', '', '2025-06-17 15:15:22', 'COL-105', 2, 1, 453443, 0, '1', NULL, NULL, '2025-06-17 15:15:22', '2025-06-17 15:15:22');

-- --------------------------------------------------------

--
-- Table structure for table `company_creation`
--

CREATE TABLE `company_creation` (
  `id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `state` int(11) NOT NULL,
  `district` int(11) NOT NULL,
  `taluk` int(11) NOT NULL,
  `place` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `mailid` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `landline_code` varchar(100) DEFAULT NULL,
  `landline` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `insert_user_id` int(11) NOT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_creation`
--

INSERT INTO `company_creation` (`id`, `company_name`, `address`, `state`, `district`, `taluk`, `place`, `pincode`, `website`, `mailid`, `mobile`, `whatsapp`, `landline_code`, `landline`, `status`, `insert_user_id`, `update_user_id`, `created_date`, `updated_date`) VALUES
(1, 'Feather Technology', 'Bussy Street', 2, 39, 315, 'Pondicherry', '605110', 'feather.www.com', 'feather@gmail.com', '7897898978', '9789789789', '78978', '78678678', 1, 1, 1, '2025-05-15 12:36:11', '2025-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `customer_creation`
--

CREATE TABLE `customer_creation` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `aadhar_number` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `age` varchar(100) DEFAULT NULL,
  `area` varchar(11) NOT NULL,
  `line` varchar(50) NOT NULL,
  `customer_data` varchar(25) NOT NULL,
  `mobile1` varchar(100) NOT NULL,
  `mobile2` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `occ_detail` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `native_address` varchar(255) DEFAULT NULL,
  `cus_limit` varchar(50) NOT NULL,
  `about_cus` varchar(100) NOT NULL,
  `pic` varchar(100) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_creation`
--

INSERT INTO `customer_creation` (`id`, `cus_id`, `aadhar_number`, `first_name`, `last_name`, `dob`, `age`, `area`, `line`, `customer_data`, `mobile1`, `mobile2`, `whatsapp`, `occupation`, `occ_detail`, `address`, `native_address`, `cus_limit`, `about_cus`, `pic`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-101', '345434534535', 'Testing ', 'One', '2002-01-31', '23', '14', '15', '2', '9687868678', '8768766786', '9687868678', 'Business', 'Tailor shop and sales', 'No.10 Pondicherry ', 'No.10 Pondicherry ', '100000', 'Good', '6847d42bbaaa5.jpg', 1, 1, '2025-06-10', '2025-06-13'),
(2, 'C-102', '977657567567', 'Testing', 'Two', '2001-01-25', '24', '20', '17', '2', '9677687867', '8787867867', '8787867867', 'Nurse', 'Private hospital', 'No.8 Villianur', 'No.8 Villianur', '100000', 'Good', '6847d504936d2.jpg', 1, 1, '2025-06-10', '2025-06-17'),
(3, 'C-103', '475675675675', 'Testing ', 'Three', '2001-02-25', '24', '18', '17', '1', '9578567567', '8657567657', '', 'College Staff', 'Christ College ', 'No.5 Mullakulam', 'No.5 Mullakulam', '50000', 'Good', '68e9e567e0b10.png', 1, 1, '2025-10-11', '2025-09-11'),
(4, 'C-104', '857567567567', 'Testing ', 'Four', '2001-02-02', '24', '18', '17', '1', '9789789789', '8767867878', '9789789789', '', '', '', '', '50000', 'gggg', '684aa1955b999.png', 1, 1, '2025-06-12', '2025-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `customer_status`
--

CREATE TABLE `customer_status` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `loan_entry_id` int(11) NOT NULL,
  `status` int(15) NOT NULL,
  `collection_status` varchar(100) NOT NULL,
  `closed_date` datetime DEFAULT NULL,
  `sub_status` int(11) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_status`
--

INSERT INTO `customer_status` (`id`, `cus_id`, `loan_entry_id`, `status`, `collection_status`, `closed_date`, `sub_status`, `remark`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-101', 1, 12, 'Closed', '2025-06-13 18:01:03', 1, 'hh', 1, 1, '2025-06-13', '2025-06-13'),
(2, 'C-101', 2, 12, 'Closed', '2025-06-16 10:09:00', 1, 'sss', 1, 1, '2025-06-13', '2025-06-16'),
(3, 'C-102', 3, 14, 'Closed', '2025-06-16 11:48:58', 1, 'aaa', 1, 1, '2025-06-16', '2025-06-17'),
(4, 'C-102', 4, 14, 'Closed', '2025-06-16 14:50:14', 1, 'dd', 1, 1, '2025-06-16', '2025-06-17'),
(5, 'C-102', 5, 12, 'Closed', '2025-06-17 15:15:33', 1, 'tre', 1, 1, '2025-06-17', '2025-06-17');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `designation` varchar(150) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp(),
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `designation`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'Head', 1, NULL, '2025-05-17', NULL),
(2, 'Sub Head', 1, NULL, '2025-05-17', NULL),
(3, 'Staff', 1, NULL, '2025-05-17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `district_name` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `state_id`, `district_name`, `status`) VALUES
(1, 1, 'Ariyalur', 1),
(2, 1, 'Chennai', 1),
(3, 1, 'Chengalpattu', 1),
(4, 1, 'Coimbatore', 1),
(5, 1, 'Cuddalore', 1),
(6, 1, 'Dharmapuri', 1),
(7, 1, 'Dindigul', 1),
(8, 1, 'Erode', 1),
(9, 1, 'Kallakurichi', 1),
(10, 1, 'Kancheepuram', 1),
(11, 1, 'Kanniyakumari', 1),
(12, 1, 'Karur', 1),
(13, 1, 'Krishnagiri', 1),
(14, 1, 'Madurai', 1),
(15, 1, 'Mayiladuthurai', 1),
(16, 1, 'Nagapattinam', 1),
(17, 1, 'Namakkal', 1),
(18, 1, 'Nilgiris', 1),
(19, 1, 'Perambalur', 1),
(20, 1, 'Pudukkottai', 1),
(21, 1, 'Ramanathapuram', 1),
(22, 1, 'Ranipet', 1),
(23, 1, 'Salem', 1),
(24, 1, 'Sivaganga', 1),
(25, 1, 'Tenkasi', 1),
(26, 1, 'Thanjavur', 1),
(27, 1, 'Theni', 1),
(28, 1, 'Thoothukudi', 1),
(29, 1, 'Tiruchirappalli', 1),
(30, 1, 'Tirunelveli', 1),
(31, 1, 'Tiruppur', 1),
(32, 1, 'Tirupathur', 1),
(33, 1, 'Tiruvallur', 1),
(34, 1, 'Tiruvannamalai', 1),
(35, 1, 'Tiruvarur', 1),
(36, 1, 'Vellore', 1),
(37, 1, 'Viluppuram', 1),
(38, 1, 'Virudhunagar', 1),
(39, 2, 'Puducherry', 1);

-- --------------------------------------------------------

--
-- Table structure for table `document_info`
--

CREATE TABLE `document_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `cus_profile_id` int(11) NOT NULL,
  `doc_name` varchar(150) NOT NULL,
  `doc_type` int(11) NOT NULL,
  `holder_name` int(11) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `upload` varchar(100) NOT NULL,
  `noc_status` int(11) NOT NULL DEFAULT 0,
  `date_of_noc` date DEFAULT NULL,
  `noc_member` varchar(150) DEFAULT NULL,
  `noc_relationship` varchar(150) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_info`
--

INSERT INTO `document_info` (`id`, `cus_id`, `cus_profile_id`, `doc_name`, `doc_type`, `holder_name`, `relationship`, `upload`, `noc_status`, `date_of_noc`, `noc_member`, `noc_relationship`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-102', 3, 'Aadhar Card', 2, 2, 'Brother', '', 1, '2025-06-16', '2', 'Brother', 1, 1, '2025-06-16', '2025-06-16'),
(2, 'C-102', 4, 'Pan Card', 2, 0, 'Customer', '', 1, '2025-06-16', 'Testing Two', 'Customer', 1, 1, '2025-06-16', '2025-06-16'),
(3, 'C-101', 2, 'hgfhgf', 1, 0, 'Customer', '68500813b6ea1.jpg', 0, NULL, NULL, NULL, 1, 1, '2025-06-16', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `document_need`
--

CREATE TABLE `document_need` (
  `id` int(11) NOT NULL,
  `cus_profile_id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `document_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `endorsement_info`
--

CREATE TABLE `endorsement_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `cus_profile_id` int(11) NOT NULL,
  `owner_name` int(11) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `vehicle_details` varchar(255) NOT NULL,
  `endorsement_name` varchar(250) NOT NULL,
  `key_original` varchar(50) NOT NULL,
  `rc_original` varchar(50) NOT NULL,
  `upload` varchar(255) NOT NULL,
  `noc_status` int(11) NOT NULL DEFAULT 0,
  `date_of_noc` date DEFAULT NULL,
  `noc_member` varchar(150) DEFAULT NULL,
  `noc_relationship` varchar(150) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `endorsement_info`
--

INSERT INTO `endorsement_info` (`id`, `cus_id`, `cus_profile_id`, `owner_name`, `relationship`, `vehicle_details`, `endorsement_name`, `key_original`, `rc_original`, `upload`, `noc_status`, `date_of_noc`, `noc_member`, `noc_relationship`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-102', 3, 0, 'Customer', 'sdsd', 'sds', 'NO', 'NO', '', 1, '2025-06-16', '2', 'Brother', 1, 1, '2025-06-16', '2025-06-16'),
(2, 'C-102', 4, 2, 'Brother', 'Car', 'vijay', 'YES', 'YES', '', 1, '2025-06-16', 'Testing Two', 'Customer', 1, 1, '2025-06-16', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `collection_mode` int(11) NOT NULL,
  `bank_id` varchar(11) DEFAULT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `branch` int(11) NOT NULL,
  `expenses_category` varchar(50) NOT NULL,
  `agent_id` varchar(50) DEFAULT NULL,
  `total_issued` varchar(50) DEFAULT NULL,
  `total_amount` varchar(100) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `amount` varchar(150) NOT NULL,
  `trans_id` varchar(150) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `collection_mode`, `bank_id`, `invoice_id`, `branch`, `expenses_category`, `agent_id`, `total_issued`, `total_amount`, `description`, `amount`, `trans_id`, `insert_login_id`, `created_on`) VALUES
(3, 1, '', '2506001', 7, '2', '6', '0', '0', 'dsf', '5000', '', 1, '2025-06-17 16:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `family_info`
--

CREATE TABLE `family_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `fam_name` varchar(100) NOT NULL,
  `fam_relationship` varchar(100) DEFAULT NULL,
  `relation_type` varchar(50) DEFAULT NULL,
  `fam_age` varchar(100) DEFAULT NULL,
  `fam_occupation` varchar(100) DEFAULT NULL,
  `fam_aadhar` varchar(100) DEFAULT NULL,
  `fam_mobile` varchar(100) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_info`
--

INSERT INTO `family_info` (`id`, `cus_id`, `fam_name`, `fam_relationship`, `relation_type`, `fam_age`, `fam_occupation`, `fam_aadhar`, `fam_mobile`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-101', 'vasanth', 'Brother', '', '22', 'Accounts', '123325564654', '9879878987', 1, NULL, '2025-06-10', NULL),
(2, 'C-102', 'Vijay', 'Brother', '', '22', 'Developer', '545646456456', '9547456456', 1, NULL, '2025-06-10', NULL),
(3, 'C-103', 'Ajith', 'Brother', '', '50', 'Racer', '756545654654', '8446645464', 1, NULL, '2025-10-11', NULL),
(4, 'C-104', 'Vikaram', 'Son', '', '23', 'Actor', '756567567567', '9576756756', 1, NULL, '2025-06-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fine_charges`
--

CREATE TABLE `fine_charges` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `loan_entry_id` int(11) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `fine_date` varchar(255) DEFAULT NULL,
  `fine_purpose` varchar(255) DEFAULT NULL,
  `fine_charge` varchar(255) NOT NULL DEFAULT '0',
  `paid_date` varchar(255) DEFAULT NULL,
  `paid_amnt` varchar(255) DEFAULT '0',
  `waiver_amnt` varchar(255) DEFAULT '0',
  `status` int(11) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL COMMENT 'Create Time',
  `updated_date` datetime DEFAULT current_timestamp() COMMENT 'Update Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gold_info`
--

CREATE TABLE `gold_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `cus_profile_id` int(11) NOT NULL,
  `gold_type` varchar(150) NOT NULL,
  `purity` varchar(150) NOT NULL,
  `weight` varchar(150) NOT NULL,
  `value` varchar(150) NOT NULL,
  `noc_status` int(11) NOT NULL DEFAULT 0,
  `date_of_noc` date DEFAULT NULL,
  `noc_member` varchar(150) DEFAULT NULL,
  `noc_relationship` varchar(150) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gold_info`
--

INSERT INTO `gold_info` (`id`, `cus_id`, `cus_profile_id`, `gold_type`, `purity`, `weight`, `value`, `noc_status`, `date_of_noc`, `noc_member`, `noc_relationship`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-102', 3, 'ddd', '222', '222', '232', 1, '2025-06-16', '2', 'Brother', 1, 1, '2025-06-16', '2025-06-16'),
(2, 'C-102', 4, 'ff', '44', '44', '44', 1, '2025-06-16', 'Testing Two', 'Customer', 1, 1, '2025-06-16', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `guarantor_info`
--

CREATE TABLE `guarantor_info` (
  `id` int(15) NOT NULL,
  `loan_entry_id` int(15) NOT NULL,
  `family_info_id` int(15) NOT NULL,
  `gu_pic` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guarantor_info`
--

INSERT INTO `guarantor_info` (`id`, `loan_entry_id`, `family_info_id`, `gu_pic`) VALUES
(1, 1, 1, '684bda692fde4.jpg'),
(2, 2, 1, '684bf5c68b55d.jpg'),
(3, 3, 2, '684fb6936195b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `kyc_info`
--

CREATE TABLE `kyc_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `proof_of` varchar(100) NOT NULL,
  `fam_mem` int(11) DEFAULT NULL,
  `proof` int(11) NOT NULL,
  `proof_detail` varchar(100) NOT NULL,
  `upload` varchar(100) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kyc_info`
--

INSERT INTO `kyc_info` (`id`, `cus_id`, `proof_of`, `fam_mem`, `proof`, `proof_detail`, `upload`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-101', '1', NULL, 1, '902532', '6847d3fe3d91b.jpg', 1, NULL, '2025-06-10', NULL),
(2, 'C-102', '1', NULL, 2, '67867868', '', 1, NULL, '2025-06-10', NULL),
(3, 'C-103', '2', 3, 2, '654654645', '', 1, NULL, '2025-10-11', NULL),
(4, 'C-104', '1', NULL, 2, '7567567567', '', 1, NULL, '2025-06-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `line_name_creation`
--

CREATE TABLE `line_name_creation` (
  `id` int(11) NOT NULL,
  `linename` varchar(150) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `line_name_creation`
--

INSERT INTO `line_name_creation` (`id`, `linename`, `branch_id`, `status`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(15, 'L1', 7, 1, 1, NULL, '2025-05-23 09:57:32', NULL),
(16, 'L2', 7, 1, 1, NULL, '2025-05-23 12:06:56', NULL),
(17, 'L7', 8, 1, 1, NULL, '2025-05-23 15:45:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loan_category`
--

CREATE TABLE `loan_category` (
  `id` int(11) NOT NULL,
  `loan_category` varchar(150) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_category`
--

INSERT INTO `loan_category` (`id`, `loan_category`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'Personal', 1, NULL, '2025-05-27', NULL),
(2, 'Home', 1, NULL, '2025-05-27', NULL),
(3, 'Business', 1, NULL, '2025-05-27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loan_category_creation`
--

CREATE TABLE `loan_category_creation` (
  `id` int(11) NOT NULL,
  `loan_category` int(11) NOT NULL,
  `loan_limit` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `profit_type` varchar(50) NOT NULL,
  `due_method` varchar(50) NOT NULL,
  `due_type` varchar(50) NOT NULL,
  `benefit_method` varchar(50) NOT NULL,
  `due_period` varchar(50) NOT NULL,
  `interest_calculate` varchar(50) NOT NULL,
  `due_calculate` varchar(50) NOT NULL,
  `interest_rate_min` varchar(50) NOT NULL,
  `interest_rate_max` varchar(50) NOT NULL,
  `document_charge` varchar(50) NOT NULL,
  `doc_charge_min` varchar(50) NOT NULL,
  `doc_charge_max` varchar(50) NOT NULL,
  `processing_fee_type` varchar(50) NOT NULL,
  `processing_fee_min` varchar(50) NOT NULL,
  `processing_fee_max` varchar(100) NOT NULL,
  `overdue_type` varchar(50) NOT NULL,
  `overdue_penalty` varchar(100) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp(),
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_category_creation`
--

INSERT INTO `loan_category_creation` (`id`, `loan_category`, `loan_limit`, `status`, `profit_type`, `due_method`, `due_type`, `benefit_method`, `due_period`, `interest_calculate`, `due_calculate`, `interest_rate_min`, `interest_rate_max`, `document_charge`, `doc_charge_min`, `doc_charge_max`, `processing_fee_type`, `processing_fee_min`, `processing_fee_max`, `overdue_type`, `overdue_penalty`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 1, '50000', '1', 'Calculation', 'Monthly', 'Interest', 'After Benefit', '12', '1', 'On Date', '1', '2', 'percentage', '3', '4', 'percentage', '5', '6', 'rupee', '200', 1, NULL, '2025-05-27', NULL),
(2, 2, '100000', '1', 'Calculation', 'Monthly', 'Interest', 'After Benefit', '18', '2', 'On Date', '2', '4', 'percentage', '4', '6', 'rupee', '200', '300', 'percentage', '5', 1, NULL, '2025-05-27', NULL),
(3, 3, '100000', '1', 'Calculation', 'Monthly', 'Interest', 'After Benefit', '3', '1', 'On Date', '2', '3', 'rupee', '250', '500', 'percentage', '2', '4', 'rupee', '300', 1, NULL, '2025-06-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loan_entry`
--

CREATE TABLE `loan_entry` (
  `id` int(11) NOT NULL,
  `aadhar_number` varchar(50) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `cus_data` varchar(50) NOT NULL,
  `loan_id` varchar(50) DEFAULT NULL,
  `loan_category` varchar(20) DEFAULT NULL,
  `loan_amount` varchar(50) DEFAULT NULL,
  `benefit_method` varchar(50) DEFAULT NULL,
  `due_method` varchar(50) DEFAULT NULL,
  `due_period` varchar(50) DEFAULT NULL,
  `interest_calculate` varchar(50) DEFAULT NULL,
  `due_calculate` varchar(50) DEFAULT NULL,
  `interest_rate_calc` varchar(50) DEFAULT NULL,
  `due_period_calc` varchar(50) DEFAULT NULL,
  `doc_charge_calc` varchar(50) DEFAULT NULL,
  `processing_fees_calc` varchar(50) DEFAULT NULL,
  `loan_amnt_calc` varchar(50) DEFAULT NULL,
  `doc_charge_calculate` varchar(50) DEFAULT NULL,
  `processing_fees_calculate` varchar(50) DEFAULT NULL,
  `net_cash_calc` varchar(50) DEFAULT NULL,
  `interest_amnt_calc` varchar(50) DEFAULT NULL,
  `loan_date` date DEFAULT NULL,
  `due_startdate_calc` varchar(50) DEFAULT NULL,
  `maturity_date_calc` varchar(50) DEFAULT NULL,
  `referred_calc` varchar(50) DEFAULT NULL,
  `agent_id_calc` varchar(50) DEFAULT NULL,
  `agent_name_calc` varchar(50) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_entry`
--

INSERT INTO `loan_entry` (`id`, `aadhar_number`, `cus_id`, `cus_data`, `loan_id`, `loan_category`, `loan_amount`, `benefit_method`, `due_method`, `due_period`, `interest_calculate`, `due_calculate`, `interest_rate_calc`, `due_period_calc`, `doc_charge_calc`, `processing_fees_calc`, `loan_amnt_calc`, `doc_charge_calculate`, `processing_fees_calculate`, `net_cash_calc`, `interest_amnt_calc`, `loan_date`, `due_startdate_calc`, `maturity_date_calc`, `referred_calc`, `agent_id_calc`, `agent_name_calc`, `remark`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, '345434534535', 'C-101', 'New', 'LID-101', '1', '50000', 'After Benefit', 'Monthly', 'Month', 'Month', 'On Date', '2', '12', '3', '5', '50000', '1500', '2500', '46000', '1000', '2025-06-13', '2025-06-13', '2026-05-13', '1', '', '', 'dd', 1, 1, '2025-06-13', '2025-06-13'),
(2, '345434534535', 'C-101', 'Existing', 'LID-102', '3', '75000', 'After Benefit', 'Monthly', 'Month', 'Month', 'On Date', '3', '3', '250', '2', '75000', '250', '1500', '73250', '2250', '2025-06-13', '2025-06-14', '2025-08-14', '1', '', '', NULL, 1, 1, '2025-06-13', '2025-06-13'),
(3, '977657567567', 'C-102', 'New', 'LID-103', '1', '50000', 'After Benefit', 'Monthly', 'Month', 'Month', 'On Date', '2', '12', '3', '5', '50000', '1500', '2500', '46000', '1000', '2025-06-16', '2025-06-17', '2026-05-17', '1', '', '', NULL, 1, 1, '2025-06-16', '2025-06-16'),
(4, '977657567567', 'C-102', 'Existing', 'LID-104', '3', '50000', 'After Benefit', 'Monthly', 'Month', 'Month', 'On Date', '3', '3', '250', '2', '50000', '250', '1000', '48750', '1500', '2025-06-16', '2025-06-17', '2025-08-17', '', '', '', NULL, 1, 1, '2025-06-16', '2025-06-16'),
(5, '977657567567', 'C-102', 'Existing', 'LID-105', '1', '40000', 'After Benefit', 'Monthly', 'Month', 'Month', 'On Date', '2', '12', '3', '5', '40000', '1200', '2000', '36800', '800', '2025-06-17', '2025-06-18', '2026-05-18', '0', '6', 'AG-101', NULL, 1, 1, '2025-06-17', '2025-06-17');

-- --------------------------------------------------------

--
-- Table structure for table `loan_issue`
--

CREATE TABLE `loan_issue` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(255) NOT NULL,
  `loan_entry_id` int(11) NOT NULL,
  `loan_amnt` int(11) NOT NULL,
  `net_cash` int(11) NOT NULL,
  `net_bal_cash` varchar(100) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_mode` varchar(11) DEFAULT NULL,
  `bank_name` varchar(11) DEFAULT NULL,
  `cash` varchar(100) DEFAULT NULL,
  `cheque_val` varchar(100) DEFAULT NULL,
  `transaction_val` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `cheque_no` varchar(50) DEFAULT NULL,
  `cheque_remark` varchar(100) DEFAULT NULL,
  `tran_remark` varchar(100) DEFAULT NULL,
  `balance_amount` varchar(100) DEFAULT NULL,
  `issue_date` date NOT NULL,
  `issue_person` varchar(50) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_issue`
--

INSERT INTO `loan_issue` (`id`, `cus_id`, `loan_entry_id`, `loan_amnt`, `net_cash`, `net_bal_cash`, `payment_type`, `payment_mode`, `bank_name`, `cash`, `cheque_val`, `transaction_val`, `transaction_id`, `cheque_no`, `cheque_remark`, `tran_remark`, `balance_amount`, `issue_date`, `issue_person`, `relationship`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-101', 1, 50000, 46000, '46000', 2, '1', '', '46000', '', '', '', '', '', '', '0', '2025-06-13', 'Testing  One', 'Customer', 1, NULL, '2025-06-13 13:32:16', NULL),
(2, 'C-101', 2, 75000, 73250, '73250', 2, '1', '', '73250', '', '', '', '', '', '', '0', '2025-06-13', 'Testing  One', 'Customer', 1, NULL, '2025-06-13 15:28:05', NULL),
(3, 'C-102', 3, 50000, 46000, '46000', 2, '1', '', '46000', '', '', '', '', '', '', '0', '2025-06-16', 'Vijay', 'Brother', 1, NULL, '2025-06-16 11:48:14', NULL),
(4, 'C-102', 4, 50000, 48750, '48750', 2, '1', '', '48750', '', '', '', '', '', '', '0', '2025-06-16', 'Testing Two', 'Customer', 1, NULL, '2025-06-16 14:49:32', NULL),
(5, 'C-102', 5, 40000, 36800, '36800', 1, '2', '1', '', '', '15000', '45345', '', '', '345435', '21800', '2025-06-17', 'Testing Two', 'Customer', 1, NULL, '2025-06-17 15:14:30', NULL),
(6, 'C-102', 5, 40000, 36800, '21800', 1, '3', '1', '', '21800', '', '', '3534', '3454', '345435', '0', '2025-06-17', 'Testing Two', 'Customer', 1, NULL, '2025-06-17 15:15:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_list`
--

CREATE TABLE `menu_list` (
  `id` int(11) NOT NULL,
  `menu` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='All Main Menu''s will be placed here';

--
-- Dumping data for table `menu_list`
--

INSERT INTO `menu_list` (`id`, `menu`, `link`, `icon`) VALUES
(1, 'Dashboard', 'dashboard', 'developer_board'),
(2, 'Master', 'master', 'camera1'),
(3, 'Administration', 'admin', 'layers'),
(4, 'Customer Creation', 'customer_creation', 'person_add'),
(5, 'Loan Entry', 'loan_entry', 'archive'),
(6, 'Approval', 'approval', 'user-check'),
(7, 'Loan Issue', 'loan_issue', 'wallet'),
(8, 'Collection', 'collection', 'credit'),
(9, 'Closed', 'closed', 'uninstall'),
(10, 'NOC', 'noc', 'export'),
(11, 'Accounts', 'accounts', 'domain'),
(12, 'Update', 'update', 'share1'),
(13, 'Customer Data', 'customer_data', 'folder_shared'),
(14, 'Search', 'search', 'magnifying-glass'),
(15, 'Reports', 'reports', 'assignment_turned_in'),
(16, 'Bulk Upload', 'bulk_upload', 'cloud_upload');

-- --------------------------------------------------------

--
-- Table structure for table `mortgage_info`
--

CREATE TABLE `mortgage_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `cus_profile_id` int(11) NOT NULL,
  `property_holder_name` int(11) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `property_details` varchar(255) NOT NULL,
  `mortgage_name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `mortgage_number` varchar(100) NOT NULL,
  `reg_office` varchar(100) NOT NULL,
  `mortgage_value` varchar(100) NOT NULL,
  `upload` varchar(100) NOT NULL,
  `noc_status` int(11) NOT NULL DEFAULT 0,
  `date_of_noc` date DEFAULT NULL,
  `noc_member` varchar(150) DEFAULT NULL,
  `noc_relationship` varchar(150) DEFAULT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mortgage_info`
--

INSERT INTO `mortgage_info` (`id`, `cus_id`, `cus_profile_id`, `property_holder_name`, `relationship`, `property_details`, `mortgage_name`, `designation`, `mortgage_number`, `reg_office`, `mortgage_value`, `upload`, `noc_status`, `date_of_noc`, `noc_member`, `noc_relationship`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-102', 3, 2, 'Brother', 'Bike', 'Bike', 'End', '35453', '354334', '34545', '', 1, '2025-06-16', '2', 'Brother', 1, 1, '2025-06-16', '2025-06-16'),
(2, 'C-102', 4, 2, 'Brother', 'Car', 'Car', 'End', '55435', '5344', '43545', '', 1, '2025-06-16', 'Testing Two', 'Customer', 1, 1, '2025-06-16', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `noc`
--

CREATE TABLE `noc` (
  `id` int(11) NOT NULL,
  `cus_profile_id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `cheque_list` int(11) NOT NULL DEFAULT 0,
  `mortgage_list` int(11) NOT NULL DEFAULT 0,
  `endorsement_list` int(11) NOT NULL DEFAULT 0,
  `document_list` int(11) NOT NULL DEFAULT 0,
  `gold_info` int(11) NOT NULL DEFAULT 0,
  `noc_status` int(11) NOT NULL DEFAULT 0,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `noc`
--

INSERT INTO `noc` (`id`, `cus_profile_id`, `cus_id`, `cheque_list`, `mortgage_list`, `endorsement_list`, `document_list`, `gold_info`, `noc_status`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 4, 'C-102', 2, 2, 2, 2, 2, 2, 1, 1, '2025-06-16', '2025-06-16'),
(2, 3, 'C-102', 2, 2, 2, 2, 2, 2, 1, 1, '2025-06-16', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `noc_ref`
--

CREATE TABLE `noc_ref` (
  `id` int(11) NOT NULL,
  `noc_id` int(11) NOT NULL,
  `date_of_noc` date NOT NULL,
  `noc_member` varchar(150) NOT NULL,
  `noc_relationship` varchar(150) NOT NULL,
  `created_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `noc_ref`
--

INSERT INTO `noc_ref` (`id`, `noc_id`, `date_of_noc`, `noc_member`, `noc_relationship`, `created_on`) VALUES
(1, 1, '2025-06-16', 'Testing Two', 'Customer', '2025-06-16'),
(2, 2, '2025-06-16', '2', 'Brother', '2025-06-16'),
(3, 0, '2025-06-16', 'Testing Two', 'Customer', '2025-06-16'),
(4, 2, '2025-06-16', '2', 'Brother', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `other_transaction`
--

CREATE TABLE `other_transaction` (
  `id` int(11) NOT NULL,
  `collection_mode` int(11) NOT NULL,
  `bank_id` varchar(11) DEFAULT NULL,
  `trans_cat` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ref_id` varchar(100) DEFAULT NULL,
  `trans_id` varchar(100) DEFAULT NULL,
  `user_name` varchar(11) DEFAULT NULL,
  `amount` varchar(150) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `other_transaction`
--

INSERT INTO `other_transaction` (`id`, `collection_mode`, `bank_id`, `trans_cat`, `name`, `type`, `ref_id`, `trans_id`, `user_name`, `amount`, `remark`, `insert_login_id`, `created_on`) VALUES
(2, 1, '', 2, 2, 1, 'INV-101', '', NULL, '5000000', 'wwwe', 1, '2025-06-17 16:05:17'),
(3, 1, '', 1, 1, 1, 'DEP-101', '', NULL, '5000', 'hhf', 1, '2025-06-17 16:26:28'),
(4, 1, '', 3, 3, 1, 'EL-101', '', NULL, '10000', 'yyy', 1, '2025-06-17 16:27:27'),
(6, 2, '1', 4, 4, 1, 'EXC-101', '35005', NULL, '35000', 'fdf', 1, '2025-06-17 17:03:25');

-- --------------------------------------------------------

--
-- Table structure for table `other_trans_name`
--

CREATE TABLE `other_trans_name` (
  `id` int(11) NOT NULL,
  `trans_cat` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `other_trans_name`
--

INSERT INTO `other_trans_name` (`id`, `trans_cat`, `name`, `insert_login_id`, `created_on`) VALUES
(1, 1, 'vas', 1, '2025-06-17'),
(2, 2, 'fff', 1, '2025-06-17'),
(3, 3, 'el', 1, '2025-06-17'),
(4, 4, 'ex', 1, '2025-06-17');

-- --------------------------------------------------------

--
-- Table structure for table `penalty_charges`
--

CREATE TABLE `penalty_charges` (
  `loan_entry_id` varchar(255) DEFAULT NULL,
  `penalty_date` varchar(255) DEFAULT NULL,
  `penalty` varchar(255) DEFAULT NULL,
  `paid_date` varchar(255) DEFAULT NULL,
  `paid_amnt` varchar(255) DEFAULT '0',
  `waiver_amnt` varchar(255) DEFAULT '0',
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `penalty_charges`
--

INSERT INTO `penalty_charges` (`loan_entry_id`, `penalty_date`, `penalty`, `paid_date`, `paid_amnt`, `waiver_amnt`, `created_date`, `updated_time`) VALUES
('6', '2025-06', '300', NULL, '0', '0', '2025-09-13 10:42:31', '2025-09-13 10:42:31'),
('6', '2025-07', '300', NULL, '0', '0', '2025-09-13 10:42:31', '2025-09-13 10:42:31');

-- --------------------------------------------------------

--
-- Table structure for table `proof_info`
--

CREATE TABLE `proof_info` (
  `id` int(11) NOT NULL,
  `addProof_name` varchar(100) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proof_info`
--

INSERT INTO `proof_info` (`id`, `addProof_name`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'Pan Card', 1, NULL, '2025-06-10', NULL),
(2, 'Aadhar Card', 1, NULL, '2025-06-10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_info`
--

CREATE TABLE `property_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) NOT NULL,
  `property` varchar(100) NOT NULL,
  `property_detail` varchar(100) NOT NULL,
  `property_holder` int(11) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_info`
--

INSERT INTO `property_info` (`id`, `cus_id`, `property`, `property_detail`, `property_holder`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'C-101', 'house', 'Own House', 0, 1, NULL, '2025-06-10', NULL),
(2, 'C-102', 'Bike', 'Own Bike', 0, 1, NULL, '2025-06-10', NULL),
(3, 'C-103', 'Car', 'Own car', 0, 1, NULL, '2025-10-11', NULL),
(4, 'C-104', 'house', 'own house', 4, 1, NULL, '2025-06-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(150) NOT NULL,
  `insert_login_id` int(11) NOT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp(),
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'Developer', 1, NULL, '2025-05-17', NULL),
(2, 'Testing Developer', 1, NULL, '2025-05-17', NULL),
(3, 'PHP Testing', 1, NULL, '2025-05-17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state_name`, `status`) VALUES
(1, 'Tamil Nadu', 1),
(2, 'Puducherry', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_menu_list`
--

CREATE TABLE `sub_menu_list` (
  `id` int(11) NOT NULL,
  `main_menu` int(11) NOT NULL,
  `sub_menu` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='All Sub menu of the project should be placed here';

--
-- Dumping data for table `sub_menu_list`
--

INSERT INTO `sub_menu_list` (`id`, `main_menu`, `sub_menu`, `link`, `icon`) VALUES
(1, 1, 'Dashboard', 'dashboard', 'view_comfy'),
(2, 2, 'Company Creation', 'company_creation', 'domain'),
(3, 2, 'Branch Creation', 'branch_creation', 'add-to-list'),
(4, 2, 'Loan Category Creation', 'loan_category_creation', 'recent_actors'),
(5, 2, 'Area Creation', 'area_creation', 'location'),
(6, 3, 'Bank Creation', 'bank_creation', 'store_mall_directory'),
(7, 3, 'Agent Creation', 'agent_creation', 'person_add'),
(8, 3, 'User Creation', 'user_creation', 'group_add'),
(9, 4, 'Customer Creation', 'customer_creation', 'recent_actors'),
(10, 5, 'Loan Entry', 'loan_entry', 'local_library'),
(11, 6, 'Approval', 'approval', 'offline_pin'),
(12, 7, 'Loan Issue', 'loan_issue', 'credit-card'),
(13, 8, 'Collection', 'collection', 'devices_other'),
(14, 9, 'Closed', 'closed', 'circle-with-cross'),
(15, 10, 'NOC', 'noc', 'book'),
(16, 11, 'Accounts', 'accounts', 'rate_review'),
(17, 11, 'Bank Clearance', 'bank_clearance', 'assignment'),
(18, 11, 'Balance Sheet', 'balance_sheet', 'colours'),
(19, 12, 'Update Customer', 'update_customer', 'cloud_upload'),
(20, 13, 'Customer Data', 'customer_data', 'person_pin'),
(21, 14, 'Search', 'search_screen', 'search'),
(22, 15, 'Loan Issue Report', 'loan_issue_report', 'area-graph'),
(23, 15, 'Collection Report', 'collection_report', 'event_note'),
(24, 15, 'Balance Report', 'balance_report', 'event_available'),
(25, 15, 'Closed Report', 'closed_report', 'erase'),
(26, 15, 'Ledger View Report', 'ledger_view_report', 'terrain'),
(27, 16, 'Bulk Upload Report', 'bulk_upload', 'cloud_done');

-- --------------------------------------------------------

--
-- Table structure for table `taluks`
--

CREATE TABLE `taluks` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `taluk_name` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taluks`
--

INSERT INTO `taluks` (`id`, `state_id`, `district_id`, `taluk_name`, `status`) VALUES
(1, 1, 1, 'Ariyalur', 1),
(2, 1, 1, 'Andimadam', 1),
(3, 1, 1, 'Sendurai', 1),
(4, 1, 1, 'Udaiyarpalayam', 1),
(5, 1, 2, 'Alandur', 1),
(6, 1, 2, 'Ambattur', 1),
(7, 1, 2, 'Aminjikarai', 1),
(8, 1, 2, 'Ayanavaram', 1),
(9, 1, 2, 'Egmore', 1),
(10, 1, 2, 'Guindy', 1),
(11, 1, 2, 'Madhavaram', 1),
(12, 1, 2, 'Madhuravoyal', 1),
(13, 1, 2, 'Mambalam', 1),
(14, 1, 2, 'Mylapore', 1),
(15, 1, 2, 'Perambur', 1),
(16, 1, 2, 'Purasavakkam', 1),
(17, 1, 2, 'Sholinganallur', 1),
(18, 1, 2, 'Thiruvottriyur', 1),
(19, 1, 2, 'Tondiarpet', 1),
(20, 1, 2, 'Velacherry', 1),
(21, 1, 3, 'Chengalpattu', 1),
(22, 1, 3, 'Cheyyur', 1),
(23, 1, 3, 'Maduranthakam', 1),
(24, 1, 3, 'Pallavaram', 1),
(25, 1, 3, 'Tambaram', 1),
(26, 1, 3, 'Thirukalukundram', 1),
(27, 1, 3, 'Tiruporur', 1),
(28, 1, 3, 'Vandalur', 1),
(29, 1, 4, 'Aanaimalai', 1),
(30, 1, 4, 'Annur', 1),
(31, 1, 4, 'Coimbatore(North)', 1),
(32, 1, 4, 'Coimbatore(South)', 1),
(33, 1, 4, 'Kinathukadavu', 1),
(34, 1, 4, 'Madukarai', 1),
(35, 1, 4, 'Mettupalayam', 1),
(36, 1, 4, 'Perur', 1),
(37, 1, 4, 'Pollachi', 1),
(38, 1, 4, 'Sulur', 1),
(39, 1, 4, 'Valparai', 1),
(40, 1, 5, 'Cuddalore', 1),
(41, 1, 5, 'Bhuvanagiri', 1),
(42, 1, 5, 'Chidambaram', 1),
(43, 1, 5, 'Kattumannarkoil', 1),
(44, 1, 5, 'Kurinjipadi', 1),
(45, 1, 5, 'Panruti', 1),
(46, 1, 5, 'Srimushnam', 1),
(47, 1, 5, 'Thittakudi', 1),
(48, 1, 5, 'Veppur', 1),
(49, 1, 5, 'Virudhachalam', 1),
(50, 1, 6, 'Dharmapuri', 1),
(51, 1, 6, 'Harur', 1),
(52, 1, 6, 'Karimangalam', 1),
(53, 1, 6, 'Nallampalli', 1),
(54, 1, 6, 'Palacode', 1),
(55, 1, 6, 'Pappireddipatti', 1),
(56, 1, 6, 'Pennagaram', 1),
(57, 1, 7, 'Atthur', 1),
(58, 1, 7, 'Dindigul(East)', 1),
(59, 1, 7, 'Dindigul(West)', 1),
(60, 1, 7, 'Guziliyamparai', 1),
(61, 1, 7, 'Kodaikanal', 1),
(62, 1, 7, 'Natham', 1),
(63, 1, 7, 'Nilakottai', 1),
(64, 1, 7, 'Oddanchatram', 1),
(65, 1, 7, 'Palani', 1),
(66, 1, 7, 'Vedasandur', 1),
(67, 1, 8, 'Erode', 1),
(68, 1, 8, 'Anthiyur', 1),
(69, 1, 8, 'Bhavani', 1),
(70, 1, 8, 'Gobichettipalayam', 1),
(71, 1, 8, 'Kodumudi', 1),
(72, 1, 8, 'Modakurichi', 1),
(73, 1, 8, 'Nambiyur', 1),
(74, 1, 8, 'Perundurai', 1),
(75, 1, 8, 'Sathiyamangalam', 1),
(76, 1, 8, 'Thalavadi', 1),
(77, 1, 9, 'Kallakurichi', 1),
(78, 1, 9, 'Chinnaselam', 1),
(79, 1, 9, 'Kalvarayan Hills', 1),
(80, 1, 9, 'Sankarapuram', 1),
(81, 1, 9, 'Tirukoilur', 1),
(82, 1, 9, 'Ulundurpet', 1),
(83, 1, 10, 'Kancheepuram', 1),
(84, 1, 10, 'Kundrathur', 1),
(85, 1, 10, 'Sriperumbudur', 1),
(86, 1, 10, 'Uthiramerur', 1),
(87, 1, 10, 'Walajabad', 1),
(88, 1, 11, 'Agasteeswaram', 1),
(89, 1, 11, 'Kalkulam', 1),
(90, 1, 11, 'Killiyur', 1),
(91, 1, 11, 'Thiruvatar', 1),
(92, 1, 11, 'Thovalai', 1),
(93, 1, 11, 'Vilavankodu', 1),
(94, 1, 12, 'Karur', 1),
(95, 1, 12, 'Aravakurichi', 1),
(96, 1, 12, 'Kadavur', 1),
(97, 1, 12, 'Krishnarayapuram', 1),
(98, 1, 12, 'Kulithalai', 1),
(99, 1, 12, 'Manmangalam', 1),
(100, 1, 12, 'Pugalur', 1),
(101, 1, 13, 'Krishnagiri', 1),
(102, 1, 13, 'Anjetty', 1),
(103, 1, 13, 'Bargur', 1),
(104, 1, 13, 'Hosur', 1),
(105, 1, 13, 'Pochampalli', 1),
(106, 1, 13, 'Sulagiri', 1),
(107, 1, 13, 'Thenkanikottai', 1),
(108, 1, 13, 'Uthangarai', 1),
(109, 1, 14, 'Kallikudi', 1),
(110, 1, 14, 'Madurai (East)', 1),
(111, 1, 14, 'Madurai (North)', 1),
(112, 1, 14, 'Madurai (South)', 1),
(113, 1, 14, 'Madurai (West)', 1),
(114, 1, 14, 'Melur', 1),
(115, 1, 14, 'Peraiyur', 1),
(116, 1, 14, 'Thirumangalam', 1),
(117, 1, 14, 'Thiruparankundram', 1),
(118, 1, 14, 'Usilampatti', 1),
(119, 1, 14, 'Vadipatti', 1),
(120, 1, 15, 'Mayiladuthurai', 1),
(121, 1, 15, 'Kuthalam', 1),
(122, 1, 15, 'Sirkali', 1),
(123, 1, 15, 'Tharangambadi', 1),
(124, 1, 16, 'Nagapattinam', 1),
(125, 1, 16, 'Kilvelur', 1),
(126, 1, 16, 'Thirukkuvalai', 1),
(127, 1, 16, 'Vedaranyam', 1),
(128, 1, 17, 'Namakkal', 1),
(129, 1, 17, 'Kholli Hills', 1),
(130, 1, 17, 'Kumarapalayam', 1),
(131, 1, 17, 'Mohanoor', 1),
(132, 1, 17, 'Paramathi Velur', 1),
(133, 1, 17, 'Rasipuram', 1),
(134, 1, 17, 'Senthamangalam', 1),
(135, 1, 17, 'Tiruchengode', 1),
(136, 1, 18, 'Udagamandalam', 1),
(137, 1, 18, 'Coonoor', 1),
(138, 1, 18, 'Gudalur', 1),
(139, 1, 18, 'Kothagiri', 1),
(140, 1, 18, 'Kundah', 1),
(141, 1, 18, 'Pandalur', 1),
(142, 1, 19, 'Perambalur', 1),
(143, 1, 19, 'Alathur', 1),
(144, 1, 19, 'Kunnam', 1),
(145, 1, 19, 'Veppanthattai', 1),
(146, 1, 20, 'Pudukottai', 1),
(147, 1, 20, 'Alangudi', 1),
(148, 1, 20, 'Aranthangi', 1),
(149, 1, 20, 'Avudiyarkoil', 1),
(150, 1, 20, 'Gandarvakottai', 1),
(151, 1, 20, 'Iluppur', 1),
(152, 1, 20, 'Karambakudi', 1),
(153, 1, 20, 'Kulathur', 1),
(154, 1, 20, 'Manamelkudi', 1),
(155, 1, 20, 'Ponnamaravathi', 1),
(156, 1, 20, 'Thirumayam', 1),
(157, 1, 20, 'Viralimalai', 1),
(158, 1, 21, 'Ramanathapuram', 1),
(159, 1, 21, 'Kadaladi', 1),
(160, 1, 21, 'Kamuthi', 1),
(161, 1, 21, 'Kezhakarai', 1),
(162, 1, 21, 'Mudukulathur', 1),
(163, 1, 21, 'Paramakudi', 1),
(164, 1, 21, 'Rajasingamangalam', 1),
(165, 1, 21, 'Rameswaram', 1),
(166, 1, 21, 'Tiruvadanai', 1),
(167, 1, 22, 'Arakkonam', 1),
(168, 1, 22, 'Arcot', 1),
(169, 1, 22, 'Kalavai', 1),
(170, 1, 22, 'Nemili', 1),
(171, 1, 22, 'Sholingur', 1),
(172, 1, 22, 'Walajah', 1),
(173, 1, 23, 'Salem', 1),
(174, 1, 23, 'Attur', 1),
(175, 1, 23, 'Edapadi', 1),
(176, 1, 23, 'Gangavalli', 1),
(177, 1, 23, 'Kadaiyampatti', 1),
(178, 1, 23, 'Mettur', 1),
(179, 1, 23, 'Omalur', 1),
(180, 1, 23, 'Pethanayakanpalayam', 1),
(181, 1, 23, 'Salem South', 1),
(182, 1, 23, 'Salem West', 1),
(183, 1, 23, 'Sankari', 1),
(184, 1, 23, 'Vazhapadi', 1),
(185, 1, 23, 'Yercaud', 1),
(186, 1, 24, 'Sivagangai', 1),
(187, 1, 24, 'Devakottai', 1),
(188, 1, 24, 'Ilayankudi', 1),
(189, 1, 24, 'Kalaiyarkovil', 1),
(190, 1, 24, 'Karaikudi', 1),
(191, 1, 24, 'Manamadurai', 1),
(192, 1, 24, 'Singampunari', 1),
(193, 1, 24, 'Thirupuvanam', 1),
(194, 1, 24, 'Tirupathur', 1),
(195, 1, 25, 'Tenkasi', 1),
(196, 1, 25, 'Alangulam', 1),
(197, 1, 25, 'Kadayanallur', 1),
(198, 1, 25, 'Sankarankovil', 1),
(199, 1, 25, 'Shenkottai', 1),
(200, 1, 25, 'Sivagiri', 1),
(201, 1, 25, 'Thiruvengadam', 1),
(202, 1, 25, 'Veerakeralampudur', 1),
(203, 1, 26, 'Thanjavur', 1),
(204, 1, 26, 'Boothalur', 1),
(205, 1, 26, 'Kumbakonam', 1),
(206, 1, 26, 'Orathanadu', 1),
(207, 1, 26, 'Papanasam', 1),
(208, 1, 26, 'Pattukottai', 1),
(209, 1, 26, 'Peravurani', 1),
(210, 1, 26, 'Thiruvaiyaru', 1),
(211, 1, 26, 'Thiruvidaimaruthur', 1),
(212, 1, 27, 'Theni', 1),
(213, 1, 27, 'Aandipatti', 1),
(214, 1, 27, 'Bodinayakanur', 1),
(215, 1, 27, 'Periyakulam', 1),
(216, 1, 27, 'Uthamapalayam', 1),
(217, 1, 28, 'Thoothukudi', 1),
(218, 1, 28, 'Eral', 1),
(219, 1, 28, 'Ettayapuram', 1),
(220, 1, 28, 'Kayathar', 1),
(221, 1, 28, 'Kovilpatti', 1),
(222, 1, 28, 'Ottapidaram', 1),
(223, 1, 28, 'Sattankulam', 1),
(224, 1, 28, 'Srivaikundam', 1),
(225, 1, 28, 'Tiruchendur', 1),
(226, 1, 28, 'Vilathikulam', 1),
(227, 1, 29, 'Lalgudi', 1),
(228, 1, 29, 'Manachanallur', 1),
(229, 1, 29, 'Manapparai', 1),
(230, 1, 29, 'Marungapuri', 1),
(231, 1, 29, 'Musiri', 1),
(232, 1, 29, 'Srirangam', 1),
(233, 1, 29, 'Thottiam', 1),
(234, 1, 29, 'Thuraiyur', 1),
(235, 1, 29, 'Tiruchirapalli (West)', 1),
(236, 1, 29, 'Tiruchirappalli (East)', 1),
(237, 1, 29, 'Tiruverumbur', 1),
(238, 1, 30, 'Tirunelveli', 1),
(239, 1, 30, 'Ambasamudram', 1),
(240, 1, 30, 'Cheranmahadevi', 1),
(241, 1, 30, 'Manur', 1),
(242, 1, 30, 'Nanguneri', 1),
(243, 1, 30, 'Palayamkottai', 1),
(244, 1, 30, 'Radhapuram', 1),
(245, 1, 30, 'Thisayanvilai', 1),
(246, 1, 31, 'Avinashi', 1),
(247, 1, 31, 'Dharapuram', 1),
(248, 1, 31, 'Kangeyam', 1),
(249, 1, 31, 'Madathukkulam', 1),
(250, 1, 31, 'Oothukuli', 1),
(251, 1, 31, 'Palladam', 1),
(252, 1, 31, 'Tiruppur (North)', 1),
(253, 1, 31, 'Tiruppur (South)', 1),
(254, 1, 31, 'Udumalaipettai', 1),
(255, 1, 32, 'Tirupathur\"', 1),
(256, 1, 32, 'Ambur', 1),
(257, 1, 32, 'Natrampalli', 1),
(258, 1, 32, 'Vaniyambadi', 1),
(259, 1, 33, 'Thiruvallur', 1),
(260, 1, 33, 'Avadi', 1),
(261, 1, 33, 'Gummidipoondi', 1),
(262, 1, 33, 'Pallipattu', 1),
(263, 1, 33, 'Ponneri', 1),
(264, 1, 33, 'Poonamallee', 1),
(265, 1, 33, 'R.K. Pet', 1),
(266, 1, 33, 'Tiruthani', 1),
(267, 1, 33, 'Uthukottai', 1),
(268, 1, 34, 'Thiruvannamalai', 1),
(269, 1, 34, 'Arni', 1),
(270, 1, 34, 'Chengam', 1),
(271, 1, 34, 'Chetpet', 1),
(272, 1, 34, 'Cheyyar', 1),
(273, 1, 34, 'Jamunamarathur', 1),
(274, 1, 34, 'Kalasapakkam', 1),
(275, 1, 34, 'Kilpennathur', 1),
(276, 1, 34, 'Polur', 1),
(277, 1, 34, 'Thandramet', 1),
(278, 1, 34, 'Vandavasi', 1),
(279, 1, 34, 'Vembakkam', 1),
(280, 1, 35, 'Thiruvarur', 1),
(281, 1, 35, 'Kodavasal', 1),
(282, 1, 35, 'Koothanallur', 1),
(283, 1, 35, 'Mannargudi', 1),
(284, 1, 35, 'Nannilam', 1),
(285, 1, 35, 'Needamangalam', 1),
(286, 1, 35, 'Thiruthuraipoondi', 1),
(287, 1, 35, 'Valangaiman', 1),
(288, 1, 36, 'Vellore', 1),
(289, 1, 36, 'Aanikattu', 1),
(290, 1, 36, 'Gudiyatham', 1),
(291, 1, 36, 'K V Kuppam', 1),
(292, 1, 36, 'Katpadi', 1),
(293, 1, 36, 'Pernambut', 1),
(294, 1, 37, 'Villupuram', 1),
(295, 1, 37, 'Gingee', 1),
(296, 1, 37, 'Kandachipuram', 1),
(297, 1, 37, 'Marakanam', 1),
(298, 1, 37, 'Melmalaiyanur', 1),
(299, 1, 37, 'Thiruvennainallur', 1),
(300, 1, 37, 'Tindivanam', 1),
(301, 1, 37, 'Vanur', 1),
(302, 1, 37, 'Vikravandi', 1),
(303, 1, 38, 'Virudhunagar', 1),
(304, 1, 38, 'Aruppukottai', 1),
(305, 1, 38, 'Kariyapatti', 1),
(306, 1, 38, 'Rajapalayam', 1),
(307, 1, 38, 'Sathur', 1),
(308, 1, 38, 'Sivakasi', 1),
(309, 1, 38, 'Srivilliputhur', 1),
(310, 1, 38, 'Tiruchuli', 1),
(311, 1, 38, 'Vembakottai', 1),
(312, 1, 38, 'Watrap', 1),
(313, 2, 39, 'Puducherry', 1),
(314, 2, 39, 'Oulgaret', 1),
(315, 2, 39, 'Villianur', 1),
(316, 2, 39, 'Bahour', 1),
(317, 2, 39, 'Karaikal', 1),
(318, 2, 39, 'Thirunallar', 1),
(319, 2, 39, 'Mahe', 1),
(320, 2, 39, 'Yanam', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_code` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `designation` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `place` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `loan_category` varchar(255) NOT NULL,
  `line` varchar(255) NOT NULL,
  `collection_access` int(11) NOT NULL,
  `download_access` int(11) NOT NULL,
  `screens` varchar(255) NOT NULL,
  `insert_login_id` varchar(100) NOT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp(),
  `updated_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='All the users will be stored here with screen access details';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_code`, `role`, `designation`, `address`, `place`, `email`, `mobile`, `user_name`, `password`, `branch`, `loan_category`, `line`, `collection_access`, `download_access`, `screens`, `insert_login_id`, `update_login_id`, `created_on`, `updated_on`) VALUES
(1, 'Super Admin', 'US-001', 1, 1, 'No.8 Mullakulam', 'Arumathupuram', 'vasanth@gmail.com', '9798798798', 'admin', '123', '7,8', '1,2,3', '15,17', 1, 1, '2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,18', '1', '1', '2024-06-13', '2025-06-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_collect_entry`
--
ALTER TABLE `accounts_collect_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agent_creation`
--
ALTER TABLE `agent_creation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_creation`
--
ALTER TABLE `area_creation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Line id` (`line_id`),
  ADD KEY `branch` (`branch_id`);

--
-- Indexes for table `area_creation_area_name`
--
ALTER TABLE `area_creation_area_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_name_creation`
--
ALTER TABLE `area_name_creation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branchid` (`branch_id`);

--
-- Indexes for table `bank_creation`
--
ALTER TABLE `bank_creation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_info`
--
ALTER TABLE `bank_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_creation`
--
ALTER TABLE `branch_creation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state`),
  ADD KEY `district_id` (`district`),
  ADD KEY `taluk_id` (`taluk`);

--
-- Indexes for table `cheque_info`
--
ALTER TABLE `cheque_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheque_no_list`
--
ALTER TABLE `cheque_no_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheque_upd`
--
ALTER TABLE `cheque_upd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Profileid` (`loan_entry_id`);

--
-- Indexes for table `company_creation`
--
ALTER TABLE `company_creation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `State ids` (`state`),
  ADD KEY `District ids` (`district`),
  ADD KEY `Taluk ids` (`taluk`);

--
-- Indexes for table `customer_creation`
--
ALTER TABLE `customer_creation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_status`
--
ALTER TABLE `customer_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loanEntryId` (`loan_entry_id`) USING BTREE;

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `State id` (`state_id`);

--
-- Indexes for table `document_info`
--
ALTER TABLE `document_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_need`
--
ALTER TABLE `document_need`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `endorsement_info`
--
ALTER TABLE `endorsement_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `family_info`
--
ALTER TABLE `family_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fine_charges`
--
ALTER TABLE `fine_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cusprofileid` (`loan_entry_id`);

--
-- Indexes for table `gold_info`
--
ALTER TABLE `gold_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guarantor_info`
--
ALTER TABLE `guarantor_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kyc_info`
--
ALTER TABLE `kyc_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proof` (`proof`),
  ADD KEY `fam-mem` (`fam_mem`);

--
-- Indexes for table `line_name_creation`
--
ALTER TABLE `line_name_creation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch id` (`branch_id`);

--
-- Indexes for table `loan_category`
--
ALTER TABLE `loan_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_category_creation`
--
ALTER TABLE `loan_category_creation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_category_key` (`loan_category`);

--
-- Indexes for table `loan_entry`
--
ALTER TABLE `loan_entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer profile id` (`aadhar_number`);

--
-- Indexes for table `loan_issue`
--
ALTER TABLE `loan_issue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_list`
--
ALTER TABLE `menu_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mortgage_info`
--
ALTER TABLE `mortgage_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `noc`
--
ALTER TABLE `noc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `noc_ref`
--
ALTER TABLE `noc_ref`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_transaction`
--
ALTER TABLE `other_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_trans_name`
--
ALTER TABLE `other_trans_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proof_info`
--
ALTER TABLE `proof_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_info`
--
ALTER TABLE `property_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_menu_list`
--
ALTER TABLE `sub_menu_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Main menu id` (`main_menu`);

--
-- Indexes for table `taluks`
--
ALTER TABLE `taluks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `District id` (`district_id`),
  ADD KEY `States id` (`state_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_key` (`role`),
  ADD KEY `designation_key` (`designation`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_collect_entry`
--
ALTER TABLE `accounts_collect_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent_creation`
--
ALTER TABLE `agent_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `area_creation`
--
ALTER TABLE `area_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `area_creation_area_name`
--
ALTER TABLE `area_creation_area_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `area_name_creation`
--
ALTER TABLE `area_name_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bank_creation`
--
ALTER TABLE `bank_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_info`
--
ALTER TABLE `bank_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branch_creation`
--
ALTER TABLE `branch_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cheque_info`
--
ALTER TABLE `cheque_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cheque_no_list`
--
ALTER TABLE `cheque_no_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cheque_upd`
--
ALTER TABLE `cheque_upd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `company_creation`
--
ALTER TABLE `company_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_creation`
--
ALTER TABLE `customer_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_status`
--
ALTER TABLE `customer_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `document_info`
--
ALTER TABLE `document_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document_need`
--
ALTER TABLE `document_need`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `endorsement_info`
--
ALTER TABLE `endorsement_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `family_info`
--
ALTER TABLE `family_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fine_charges`
--
ALTER TABLE `fine_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `gold_info`
--
ALTER TABLE `gold_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guarantor_info`
--
ALTER TABLE `guarantor_info`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kyc_info`
--
ALTER TABLE `kyc_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `line_name_creation`
--
ALTER TABLE `line_name_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `loan_category`
--
ALTER TABLE `loan_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loan_category_creation`
--
ALTER TABLE `loan_category_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loan_entry`
--
ALTER TABLE `loan_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan_issue`
--
ALTER TABLE `loan_issue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu_list`
--
ALTER TABLE `menu_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mortgage_info`
--
ALTER TABLE `mortgage_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `noc`
--
ALTER TABLE `noc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `noc_ref`
--
ALTER TABLE `noc_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `other_transaction`
--
ALTER TABLE `other_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `other_trans_name`
--
ALTER TABLE `other_trans_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `proof_info`
--
ALTER TABLE `proof_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `property_info`
--
ALTER TABLE `property_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sub_menu_list`
--
ALTER TABLE `sub_menu_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `taluks`
--
ALTER TABLE `taluks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `area_name_creation`
--
ALTER TABLE `area_name_creation`
  ADD CONSTRAINT `branchid` FOREIGN KEY (`branch_id`) REFERENCES `branch_creation` (`id`);

--
-- Constraints for table `branch_creation`
--
ALTER TABLE `branch_creation`
  ADD CONSTRAINT `district_id` FOREIGN KEY (`district`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `state_id` FOREIGN KEY (`state`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `taluk_id` FOREIGN KEY (`taluk`) REFERENCES `taluks` (`id`);

--
-- Constraints for table `company_creation`
--
ALTER TABLE `company_creation`
  ADD CONSTRAINT `District ids` FOREIGN KEY (`district`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `State ids` FOREIGN KEY (`state`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `Taluk ids` FOREIGN KEY (`taluk`) REFERENCES `taluks` (`id`);

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `State id` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `line_name_creation`
--
ALTER TABLE `line_name_creation`
  ADD CONSTRAINT `branch id` FOREIGN KEY (`branch_id`) REFERENCES `branch_creation` (`id`);

--
-- Constraints for table `loan_category_creation`
--
ALTER TABLE `loan_category_creation`
  ADD CONSTRAINT `loan_category_key` FOREIGN KEY (`loan_category`) REFERENCES `loan_category` (`id`) ON UPDATE NO ACTION;

--
-- Constraints for table `sub_menu_list`
--
ALTER TABLE `sub_menu_list`
  ADD CONSTRAINT `Main menu id` FOREIGN KEY (`main_menu`) REFERENCES `menu_list` (`id`);

--
-- Constraints for table `taluks`
--
ALTER TABLE `taluks`
  ADD CONSTRAINT `District id` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `States id` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `designation_key` FOREIGN KEY (`designation`) REFERENCES `designation` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `role_key` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
