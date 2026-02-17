-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2026 at 04:06 PM
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
-- Database: `hayluxury`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `created_at`) VALUES
(7, 'CHOPARD', 'chopard', '2026-02-12 17:34:10'),
(8, 'GRAFF', 'graff', '2026-02-12 17:34:24'),
(9, 'MESSIKA', 'messika', '2026-02-12 17:34:40'),
(11, 'MARLI', 'marli', '2026-02-12 17:35:31'),
(12, 'VCA', 'vca', '2026-02-12 18:34:05'),
(13, 'CTR', 'ctr', '2026-02-12 18:34:25'),
(14, 'BVG', 'bvg', '2026-02-12 18:34:57'),
(15, 'T&CO', 't&co', '2026-02-12 18:38:08'),
(17, 'Bouch', 'bouch', '2026-02-12 18:53:04');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(3, 'Rings', 'rings', '2026-02-12 18:07:46'),
(4, 'Earrings', 'earrings', '2026-02-12 18:08:14'),
(5, 'Necklaces', 'necklaces', '2026-02-12 18:12:29'),
(6, 'Bracelets', 'bracelets', '2026-02-12 18:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `diamond_info` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('In Stock','Sold Out','Reserved') NOT NULL DEFAULT 'In Stock',
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `type`, `color`, `size`, `weight`, `diamond_info`, `description`, `price`, `image`, `status`, `whatsapp_number`, `created_at`) VALUES
(8, 6, 12, 'Van Cleef', 'Bracelet', 'yellow gold', '', '11.5', '', '', 2800.00, 'uploads/1770922649_U1dj44RpQkmyhcDcYseOxQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-12 18:57:29'),
(9, 6, 12, 'Van Cleef', 'Bracelet', 'yellow gold', 'Standard', '11.5', '', '', 2800.00, 'uploads/1770925487_ReEWx6NXRwGh9Rd1fXj9Sg.png.transform.vca-w820-2x.avif', 'In Stock', '971561741383', '2026-02-12 19:44:47'),
(11, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '13.2', '', '', 7800.00, 'uploads/1771087568_0_ghG-6aZ4SnubiJjCTcYNVw.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-14 16:46:08'),
(12, 6, 12, 'VCA', 'Bracelet', 'white gold', 'Standard ', '13.2', '', '', 7800.00, 'uploads/1771156865_0_vq5Rv7VRQeWwoFUWsPh4lA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:01:05'),
(13, 6, 12, 'VCA', 'Bracelet', 'yellow gold', '', '11.5', '', '', 2800.00, 'uploads/1771157159_0_kOPjMIcmSwWC7T82jRnUSA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:05:59'),
(14, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '16.44', '', '', 9000.00, 'uploads/1771157292_0_prC7zzvIQdqyI3crhzJqOg.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:08:12'),
(15, 6, 12, 'VCA', 'Bracelet', 'rose gold', '', '16.44', '', '', 9000.00, 'uploads/1771157339_0_Ig5K7ZEkR4yUmpzfLmlmIg.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:08:59'),
(16, 6, 12, 'VCA', 'Bracelet', 'white gold', 'Standard ', '12.36', '0.94ct ', '', 13100.00, 'uploads/1771157440_0_Bqf268clTrG-2FRf699sew.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:10:40'),
(17, 6, 12, 'VCA', 'Bracelet', 'white gold', 'Standard ', '11.5', '', '', 2800.00, 'uploads/1771157482_0_DVY6xVHYS26gviVtmAKSzQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:11:22'),
(18, 6, 12, 'VCA', 'Bracelet', 'yellow gold', '', '12.7', '0.94ct ', '', 12800.00, 'uploads/1771157602_0_IbPsdfSxTiaVg5n9vdhmfw.png.transform.vca-w820-2x.avif', 'In Stock', '7899090083', '2026-02-15 12:13:22'),
(19, 6, 12, 'VCA', 'Bracelet', 'rose gold', 'Standard ', '12.7', '0.94ct ', '', 12100.00, 'uploads/1771157741_0_PiUUh9PBQrOuQZ5VAzg3Zw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:15:41'),
(20, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '13.82', '2.48ct', '', 16100.00, 'uploads/1771158253_0_oY6G-hQNRA6Cc48qamfFDA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:24:13'),
(21, 6, 12, 'VCA', 'Bracelet', 'white gold', 'Standard ', '13.82', '2.48ct', '', 12100.00, 'uploads/1771158344_0_42hFAg21Tx-KyJ-12rHJpA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:25:44'),
(22, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.5', '', '', 2800.00, 'uploads/1771158464_0_JhwteI6BTFmymAV2Osxc5Q.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:27:44'),
(23, 6, 12, 'VCA', 'Bracelet', 'rose gold', 'Standard ', '2.25', '', '', 2544.00, 'uploads/1771158689_0_1627657.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:31:29'),
(24, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.5', '', '', 3544.00, 'uploads/1771158758_0_kLlOhvYSRG-sVOps1rBwmA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:32:38'),
(25, 6, 12, 'VCA', 'Bracelet', 'rose gold', 'Standard ', '7.2', '', '', 3500.00, 'uploads/1771158973_0_1627659.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:36:13'),
(26, 6, 12, 'VCA', 'Bracelet', 'white gold', 'Standard ', '6.27', '0.6ct', '', 12100.00, 'uploads/1771159101_0_1627631.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:38:21'),
(27, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.5', '', '', 1300.00, 'uploads/1771159247_0_R5qb221xTxOrTW4_Oz6w6Q.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:40:47'),
(28, 6, 12, 'VCA', 'Bracelet', 'white gold', 'Standard ', '2.24', '0.10ct', '', 1300.00, 'uploads/1771159491_0_1627628.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:44:51'),
(29, 6, 12, 'VCA', 'Bracelet', 'white gold', 'Standard ', '11.5', '', '', 2800.00, 'uploads/1771159598_0_1-vj4V5wRUCpursBXBglGQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:46:38'),
(30, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '1.73', '', '', 1450.00, 'uploads/1771160221_0_1793477.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:57:01'),
(31, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.5', '', '', 12050.00, 'uploads/1771160350_0_U1dj44RpQkmyhcDcYseOxQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 12:59:10'),
(32, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.5', '', '', 12050.00, 'uploads/1771160394_0_ReEWx6NXRwGh9Rd1fXj9Sg.png.transform.vca-w820-2x.avif', 'In Stock', '7899090083', '2026-02-15 12:59:54'),
(33, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.6', '', '', 14550.00, 'uploads/1771160516_0_W9cY3X-UQMiolV1DG5JKtg.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 13:01:56'),
(34, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '1.71', '', '', 1450.00, 'uploads/1771160691_0_1793471.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 13:04:51'),
(35, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.9', '0.94ct ', '', 12050.00, 'uploads/1771160836_0_8eha9YIaRbWBZM_et7N-zw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-15 13:07:16'),
(36, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '11.37', '', '', 12050.00, 'uploads/1771215626_0_2138777.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 04:20:26'),
(37, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '5.01', '', '', 1250.00, 'uploads/1771216282_0_CyfxD3_ja0Kv__qstXS3Xw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 04:31:22'),
(38, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '1.76', '', '', 12050.00, 'uploads/1771216472_0_1626814.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 04:34:32'),
(39, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '3.67', '', '', 12000.00, 'uploads/1771216713_0_t1YowOPuzECAr6BTribspw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 04:38:33'),
(40, 6, 12, 'VCA', 'Bracelet', 'yellow gold', '', '1.63', '', '', 1200.00, 'uploads/1771216829_0_1626926.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 04:40:29'),
(41, 6, 12, 'VCA', 'Bracelet', 'yellow gold', 'Standard ', '3.41', '', '', 12000.00, 'uploads/1771217030_0_3BA8ldDwhkSX0dEOVDIlKQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 04:43:50'),
(42, 6, 12, 'VCA', 'Bracelet', 'yellow gold', '', '11.53', '', '', 12000.00, 'uploads/1771217459_0_1626775.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 04:50:59'),
(44, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '24.13', '', '', 1200.00, 'uploads/1771229128_0_UL5jG-sdTXuwIVmNsyS-BQ.avif', 'In Stock', '+971561741383', '2026-02-16 08:05:28'),
(45, 5, 12, 'VCA', 'Necklace', 'yellow gold', '', '23.72', '', '', 12000.00, 'uploads/1771229259_0_R8UGWQ1QSWydtY6IssVMgQ.avif', 'In Stock', '+971561741383', '2026-02-16 08:07:39'),
(46, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '24.32', '', '', 12000.00, 'uploads/1771232631_0_1dYoNQ7rReu2RaAqIBbvIw.avif', 'In Stock', '+971561741383', '2026-02-16 09:03:51'),
(47, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '23.30', '', '', 14000.00, 'uploads/1771232772_0_c33CZ-sKSCSsv2f_AXZTzg.avif', 'In Stock', '+971561741383', '2026-02-16 09:06:12'),
(48, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '22.03', '', '', 13000.00, 'uploads/1771232878_0__Ov41OCITKeGmE2XPv5lvA.avif', 'In Stock', '+971561741383', '2026-02-16 09:07:58'),
(49, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '22.14', '', '', 12000.00, 'uploads/1771232966_0_YgwIO3dDT3249R3dF7DYRg.avif', 'In Stock', '+971561741383', '2026-02-16 09:09:26'),
(50, 5, 12, 'VCA', 'Necklace', 'white gold', 'Standard ', '22.03', '', '', 12000.00, 'uploads/1771233233_0_w0OGy7fnTEWXJMxQ_M4dMg.avif', 'In Stock', '+971561741383', '2026-02-16 09:13:53'),
(51, 5, 12, 'VCA', 'Necklace', 'white gold', 'Standard ', '22.32', '', '', 12000.00, 'uploads/1771233373_0_wpl_T08DQxO1617Cjn8eOQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 09:16:13'),
(52, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '22.42', '', '', 12000.00, 'uploads/1771233652_0_8z-kPJ9NQT-1riuPh_Bzyg.avif', 'In Stock', '+971561741383', '2026-02-16 09:20:52'),
(53, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '22.58', '', '', 12000.00, 'uploads/1771233901_0_xq4PsmVsTQinKur31I3gIQ.avif', 'In Stock', '+971561741383', '2026-02-16 09:25:01'),
(54, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '22.5', '', '', 12000.00, 'uploads/1771234070_0_tRVejRrwTEa2gceGy1oqmA.avif', 'In Stock', '+971561741383', '2026-02-16 09:27:50'),
(55, 5, 12, 'VCA', 'Necklace', 'yellow gold', 'Standard ', '27', '', '', 15000.00, 'uploads/1771234264_0_qo2h-cwtTkeFHSHDEfsSlA.avif', 'In Stock', '+971561741383', '2026-02-16 09:31:04'),
(56, 5, 12, 'VCA', 'Pendant', 'rose gold', 'Standard ', '6.45', '', '', 12000.00, 'uploads/1771234600_0_wx1sGS3RuEmfJ5FzETOaLg.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 09:36:40'),
(57, 5, 12, 'VCA', 'Pendant', 'rose gold', 'Standard ', '2.6', '', '', 12000.00, 'uploads/1771234840_0_pDEuWFekj0Ch7YDH9x4aBQ.png.transform.vca-w820-2x.avif', 'In Stock', '7899090083', '2026-02-16 09:40:40'),
(58, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '2.63', '', '', 1200.00, 'uploads/1771234937_0_2pPDVXnA6EqiFX7HTVJOzw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 09:42:17'),
(59, 5, 12, 'VCA', 'Pendant', 'white gold', '', '16.23', '3.3ct', '', 12000.00, 'uploads/1771235107_0_Ry5g_xqSRp-o_i0g8714gQ.avif', 'In Stock', '+971561741383', '2026-02-16 09:45:07'),
(60, 5, 12, 'VCA', 'Pendant', 'rose gold', 'Standard ', '5.59g', '0.14ct', '', 14000.00, 'uploads/1771235295_0_1627736.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 09:48:15'),
(61, 5, 12, 'VCA', 'Pendant', 'white gold', 'Standard ', '5.68g', '0.80ct', '', 12000.00, 'uploads/1771235451_0_1627210.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 09:50:51'),
(62, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '4.5g', '', '', 3300.00, 'uploads/1771235943_0_8SVLf7dvToWyjc5-V_AMFQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 09:59:03'),
(63, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '4.5g', '', '', 3300.00, 'uploads/1771236028_0_YEmBat5TQWGLu9nuGUeRcQ.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:00:28'),
(64, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '4.75g', '', '', 3300.00, 'uploads/1771236088_0_Bii4WBrbQJ2pqEdZoGHwnA.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:01:28'),
(65, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '4.5g', '', '', 2800.00, 'uploads/1771236298_0_rWXl_1YZTmipGvOt7-UprA.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:04:58'),
(66, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '4.5g', '', '', 3300.00, 'uploads/1771236440_0_L14wag6FQhCQEyysNKQN2g.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:07:20'),
(67, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '4.5g', '', '', 3300.00, 'uploads/1771236549_0_D5-YFu7LRQaFdHqcIYDM8w.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:09:09'),
(68, 5, 12, 'VCA', 'Pendant', 'white gold', 'Standard ', '4.5g', '', '', 3300.00, 'uploads/1771236706_0_EewS_xfLSAGKNMi-A2WOSw.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:11:46'),
(69, 5, 12, 'VCA', 'Pendant', 'rose gold', 'Small', '2.68', '', '', 1800.00, 'uploads/1771238071_0_Xovzb3HFKUW0t5VWo6q_dQ.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:34:31'),
(70, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Small', '2.6g', '', '', 1400.00, 'uploads/1771238221_0_BoV0F2c7kkunYFbVii3h1A.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:37:01'),
(71, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '4.5g', '', '', 3300.00, 'uploads/1771238471_0_Q-MNvRAKTjyLYIdZaVUbqg.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:41:11'),
(72, 5, 12, 'VCA', 'Pendant', 'white gold', 'Standard ', '4.5g', '', '', 2300.00, 'uploads/1771238613_0_9k6etCBrSaKzsIxpRDdkQw.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:43:33'),
(73, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '5.2g', '', '', 3300.00, 'uploads/1771238769_0_bstD37ArSJCugXc_RVsHVA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 10:46:09'),
(74, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Long (Heavy)', '16.38', '', '', 14000.00, 'uploads/1771239132_0_1QbtqB3wRoWlgoLcc0LSUQ.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 10:52:12'),
(75, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Long (Heavy)', '13.07g', '', '', 12000.00, 'uploads/1771253613_0_1m76uYRcTgCQzQx26QMLrg.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 14:53:33'),
(76, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Long (Heavy)', '12.95', '', '', 12500.00, 'uploads/1771253708_0_xsdHP8qGTfGE1cQLUIQsCQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 14:55:08'),
(77, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Long (Heavy)', '12.95', '', '', 12000.00, 'uploads/1771253794_0_BPwyF7fJQ1ieWpWxaEmpag.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 14:56:34'),
(78, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Long (Heavy)', '12.9', '', '', 12000.00, 'uploads/1771253891_0_aGmj48yCR_G651TxyUiNEQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 14:58:11'),
(79, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Long (Heavy)', '14g', '', '', 14000.00, 'uploads/1771254723_0_HZTKvVi2SAuVWgUYimH4sw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:02:09'),
(80, 5, 12, 'VCA', 'Pendant', 'white gold', 'Long (Heavy)', '12.7', '', '', 12000.00, 'uploads/1771254344_0_aPaOnArkRdWOA9AshbctGA (1).avif', 'In Stock', '+971561741383', '2026-02-16 15:05:44'),
(81, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '5.74g', '', '', 12000.00, 'uploads/1771254503_0_9Jlm3ZFrvkOpSdWNW5bs4Q.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 15:08:23'),
(82, 5, 12, 'VCA', 'Pendant', 'rose gold', 'Small', '3.3g', '', '', 12000.00, 'uploads/1771254988_0_QhltTcsMQmWnFXzyT5m9aw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:16:28'),
(83, 5, 12, 'VCA', 'Pendant', 'white gold', 'Standard ', '5.26g', '0.47ct', '', 12000.00, 'uploads/1771255146_0_z-U0BbI9SjeHJBcdRTELpg.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 15:19:06'),
(84, 5, 12, 'VCA', 'Pendant', 'rose gold', 'Standard ', '5.26g', '0.47', '', 12000.00, 'uploads/1771255233_0_sSBfdkvdRcOK8LurAkDb5w.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:20:33'),
(85, 5, 12, 'VCA', 'Pendant', 'yellow gold', 'Standard ', '5.43', '0.47ct', '', 12000.00, 'uploads/1771255543_0_sSBfdkvdRcOK8LurAkDb5w.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 15:25:43'),
(86, 5, 12, 'VCA', 'Pendant', 'white gold', 'Small', '3.21g', '0.12ct', '', 12000.00, 'uploads/1771255946_0_DhmNVF3fmUiqMPRerWHIww.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:32:26'),
(87, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '5.7g', '', '', 3300.00, 'uploads/1771256304_0_pq_pcwjKRkujwDKfxHmL1g.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:38:24'),
(88, 4, 12, 'VCA', 'Earring', 'white gold', 'Standard ', '5.7g', '', '', 3300.00, 'uploads/1771256579_0_FNDYu8EdTGC_j3c6quahQA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:42:59'),
(89, 5, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '5.7g', '', '', 3300.00, 'uploads/1771256719_0_6Vo-7xjCQtqH9cjV3cHWHA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:45:19'),
(90, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '5.7g', '', '', 3300.00, 'uploads/1771256904_0_LkX8ReuuRjC_27cQr1Y-YQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:48:24'),
(91, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '5.7g', '', '', 3300.00, 'uploads/1771257066_0_mIP54ezcRgKU_1zXDxQF4Q.png.transform.vca-w820-2x (1).avif', 'In Stock', '+971561741383', '2026-02-16 15:51:06'),
(92, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '5.7g', '', '', 3300.00, 'uploads/1771257200_0_jxFmmAveQGug7p-e7QXbsg.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:53:20'),
(93, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '5.7g', '', '', 3300.00, 'uploads/1771257383_0_5_1rPmT6ScScd6pm72ku_Q.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 15:56:23'),
(94, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '7.6g', '0.9ct', '', 7700.00, 'uploads/1771257884_0_2MQzbTFRRfiPO3rNArJsIg.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:04:44'),
(95, 4, 12, 'VCA', 'Earring', 'rose gold', 'Standard ', '7.59g', '0.9ct', '', 5500.00, 'uploads/1771257971_0_jVjtpVxWQgma3CGbWb0Arw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:06:11'),
(96, 4, 12, 'VCA', 'Earring', 'white gold', 'Standard ', '7.48g', '0.9ct', '', 5500.00, 'uploads/1771258160_0__q-CCmZJRs6uojqId_WC1Q.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:09:20'),
(97, 4, 12, 'VCA', 'Earring', 'rose gold', 'Small', '2.45', '', '', 3300.00, 'uploads/1771258451_0_z6vchqBcUU-5IiEOPBJHqA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:14:11'),
(98, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '9.15', '', '', 3300.00, 'uploads/1771258613_0_XcJ9MnMsQUKhJtJIhp0h6g.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:16:53'),
(99, 4, 12, 'VCA', 'Earring', 'white gold', 'Standard ', '9.15', '', '', 3300.00, 'uploads/1771258670_0_gGGsjHNUR0OnS_3RWBxWIw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:17:50'),
(100, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '14.71g', '', '', 4500.00, 'uploads/1771258798_0_i8I7yXgKRfKeoTeu2C2SWA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:19:58'),
(101, 4, 12, 'VCA', 'Earring', 'RG,YG,WG', 'Small', '2.3g', '0.22ct', '', 3300.00, 'uploads/1771259131_0__q-CCmZJRs6uojqId_WC1Q.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:25:31'),
(102, 4, 12, 'VCA', 'Earring', 'white gold', 'Small', '2.07', '', '', 1400.00, 'uploads/1771259296_0_baRx0XZWLEGlEI3jzWL2Kw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:28:16'),
(103, 5, 12, 'VCA', 'Pendant', 'white gold', 'Standard ', '14.68g', '2.13ct', '', 4400.00, 'uploads/1771259408_0_xTimuwKzR6Cw5hlqEhgKxw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:30:08'),
(104, 4, 12, 'VCA', 'Earring', 'white gold', 'Standard ', '9.82g', '1.5ct', '', 5500.00, 'uploads/1771259574_0_1626651.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:32:54'),
(105, 4, 12, 'VCA', 'Earring', 'rose gold', 'Standard ', '8.59g', '0.20ct', '', 4500.00, 'uploads/1771259685_0_1627669.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:34:45'),
(106, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Small', '2g', '', '', 1800.00, 'uploads/1771259866_0_BuYLbz_P9kSAC-j0wNhkCg.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:37:46'),
(107, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Small', '2.07', '', '', 1800.00, 'uploads/1771260030_0_YMMijKoVmUy1d7tGxMJOlw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:40:30'),
(108, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Small', '2g', '', '', 1800.00, 'uploads/1771260155_0_9HFr1MbKBESXj01uTrEcaA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:42:35'),
(109, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Small', '2.31g', '', '', 1400.00, 'uploads/1771260311_0_FtrcpcZtVEWxWURG9DpDAw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 16:45:11'),
(110, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '2.10g', '', '', 1400.00, 'uploads/1771261016_0_IMG_6255-removebg-preview-Photoroom.png', 'In Stock', '+971561741383', '2026-02-16 16:52:53'),
(111, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '12.21g', '', '', 12000.00, 'uploads/1771261411_0_CkjGriQ0Q4inrP8J1IKBxw.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:03:31'),
(112, 4, 12, 'VCA', 'Earring', 'rose gold', 'Standard ', '13.4g', '0.93ct', '', 12000.00, 'uploads/1771261573_0_wta3HoAqStuneBROWdNKug.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:06:13'),
(113, 4, 12, 'VCA', 'Earring', 'white gold', 'Standard ', '13.35g', '0.93ct', '', 13000.00, 'uploads/1771261670_0__2YhYAQDQ7-KIvRqqldGAA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:07:50'),
(114, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '30.61g', '', '', 15000.00, 'uploads/1771261782_0_aMMyTGTyQWa-dPtZG_8vgA.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:09:42'),
(115, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '12g', '', '', 13000.00, 'uploads/1771261876_0_1626778.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:11:16'),
(116, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '17.69', '0.94ct ', '', 13000.00, 'uploads/1771262159_0_MRahQYJXR4-BjKjcT5dgxQ.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:15:59'),
(117, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '6.4g', '0.18ct', '', 12000.00, 'uploads/1771262283_0_1626986.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:18:03'),
(118, 4, 12, 'VCA', 'Earring', 'yellow gold', 'Standard ', '16.7g', '', '', 15000.00, 'uploads/1771262365_0_6xKiKKo2S_G0-gac8NsB1A.png.transform.vca-w820-2x.avif', 'In Stock', '+971561741383', '2026-02-16 17:19:25'),
(119, 3, 14, 'BVG', 'Ring', 'white gold', '58', '8.91g', '1.5ct', '', 12000.00, 'uploads/1771306920_0_459763.avif', 'In Stock', '+971561741383', '2026-02-17 05:42:00'),
(120, 3, 14, 'BVG', 'Ring', 'rose gold', '52,54,55,57', '5.8g', '0.6ct', '', 12000.00, 'uploads/1771307159_0_460145.avif', 'In Stock', '+971561741383', '2026-02-17 05:45:59'),
(121, 5, 14, 'BVG', 'Pendant', 'rose gold', 'Standard ', '18.52g', '2.26ct', '', 12000.00, 'uploads/1771307299_0_462052.avif', 'In Stock', '+971561741383', '2026-02-17 05:48:19'),
(122, 5, 14, 'BVG', 'Pendant', 'white gold', 'Standard ', '20g', '2.26ct', '', 12000.00, 'uploads/1771307568_0_462058.avif', 'In Stock', '+971561741383', '2026-02-17 05:52:48'),
(123, 5, 14, 'BVG', 'Necklace', 'white gold', 'Standard ', '48.8g', '8.06ct', '', 14000.00, 'uploads/1771307723_0_472219.avif', 'In Stock', '+971561741383', '2026-02-17 05:55:23'),
(124, 5, 14, 'BVG', 'Necklace', 'white gold', 'Standard ', '18.06g', '2.68ct', '', 14000.00, 'uploads/1771307899_0_462054.avif', 'In Stock', '+971561741383', '2026-02-17 05:58:19'),
(125, 5, 14, 'BVG', 'Necklace', 'white gold', 'Standard ', '37.41g', '5.5ct', '', 14000.00, 'uploads/1771308112_0_1495244.avif', 'In Stock', '+971561741383', '2026-02-17 06:01:52'),
(126, 6, 14, 'BVG', 'Bracelet', 'yellow gold', '16,17,18,19', '19.3g', '0.48ct', '', 12000.00, 'uploads/1771308435_0_1474481.avif', 'In Stock', '+971561741383', '2026-02-17 06:07:15'),
(127, 6, 14, 'BVG', 'Bracelet', 'rose gold', '16,17,18,19', '19.3g', '0.48ct', '', 12000.00, 'uploads/1771308556_0_1538877.avif', 'In Stock', '+971561741383', '2026-02-17 06:09:16'),
(128, 6, 14, 'BVG', 'Bracelet', 'white gold', '16,17,18,19', '19.5g', '0.48ct', '', 12000.00, 'uploads/1771308604_0_1629181.avif', 'In Stock', '+971561741383', '2026-02-17 06:10:04'),
(129, 6, 14, 'BVG', 'Bracelet', 'yellow gold', '16,17,18,19', '21.48g', '3.4ct', '', 12000.00, 'uploads/1771311106_0_1567314.avif', 'In Stock', '+971561741383', '2026-02-17 06:51:46'),
(130, 6, 14, 'BVG', 'Bracelet', 'rose gold', '16,17,18,19', '20g', '3.3ct', '', 13000.00, 'uploads/1771311220_0_1567313.avif', 'In Stock', '+971561741383', '2026-02-17 06:53:40'),
(131, 6, 14, 'BVG', 'Bracelet', 'white gold', '16,17,18,19', '20g', '3.3ct', '', 14000.00, 'uploads/1771311287_0_1567315.avif', 'In Stock', '+971561741383', '2026-02-17 06:54:47'),
(132, 4, 14, 'BVG', 'Earring', 'white gold', 'Standard ', '18.54g', '2.2ct', '', 12000.00, 'uploads/1771311668_0_459820.avif', 'In Stock', '+971561741383', '2026-02-17 07:01:08'),
(133, 4, 14, 'BVG', 'Earring', 'yellow gold', 'Standard ', '9.44g', '0.17ct', '', 12000.00, 'uploads/1771311795_0_1529990.avif', 'In Stock', '+971561741383', '2026-02-17 07:03:15'),
(134, 4, 14, 'BVG', 'Earring', 'rose gold', 'Standard ', '9.44g', '0.17ct', '', 13000.00, 'uploads/1771311846_0_1525669.avif', 'In Stock', '+971561741383', '2026-02-17 07:04:06'),
(135, 4, 14, 'BVG', 'Earring', 'RG,YG,WG', 'Standard ', '8.64g', '0.75ct', '', 12000.00, 'uploads/1771312155_0_1338623.avif', 'In Stock', '+971561741383', '2026-02-17 07:09:15'),
(136, 4, 14, 'BVG', 'Earring', 'RG,YG,WG', 'Standard ', '9.7g', '0.63ct', '', 12000.00, 'uploads/1771312674_0_1266500.avif', 'In Stock', '+971561741383', '2026-02-17 07:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `created_at`) VALUES
(1, 8, 'uploads/1770922649_U1dj44RpQkmyhcDcYseOxQ.png.transform.vca-w820-2x.avif', 1, '2026-02-14 16:05:45'),
(2, 9, 'uploads/1770925487_ReEWx6NXRwGh9Rd1fXj9Sg.png.transform.vca-w820-2x.avif', 1, '2026-02-14 16:05:45'),
(5, 11, 'uploads/1771087568_0_ghG-6aZ4SnubiJjCTcYNVw.png.transform.vca-w820-2x (1).avif', 1, '2026-02-14 16:46:08'),
(6, 12, 'uploads/1771156865_0_vq5Rv7VRQeWwoFUWsPh4lA.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:01:05'),
(7, 13, 'uploads/1771157159_0_kOPjMIcmSwWC7T82jRnUSA.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:05:59'),
(8, 14, 'uploads/1771157292_0_prC7zzvIQdqyI3crhzJqOg.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:08:12'),
(9, 15, 'uploads/1771157339_0_Ig5K7ZEkR4yUmpzfLmlmIg.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:08:59'),
(10, 16, 'uploads/1771157440_0_Bqf268clTrG-2FRf699sew.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:10:40'),
(11, 17, 'uploads/1771157482_0_DVY6xVHYS26gviVtmAKSzQ.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:11:22'),
(12, 18, 'uploads/1771157602_0_IbPsdfSxTiaVg5n9vdhmfw.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:13:22'),
(13, 19, 'uploads/1771157741_0_PiUUh9PBQrOuQZ5VAzg3Zw.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:15:41'),
(14, 20, 'uploads/1771158253_0_oY6G-hQNRA6Cc48qamfFDA.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:24:13'),
(15, 21, 'uploads/1771158344_0_42hFAg21Tx-KyJ-12rHJpA.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:25:44'),
(16, 22, 'uploads/1771158464_0_JhwteI6BTFmymAV2Osxc5Q.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:27:44'),
(17, 23, 'uploads/1771158689_0_1627657.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:31:29'),
(18, 24, 'uploads/1771158758_0_kLlOhvYSRG-sVOps1rBwmA.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:32:38'),
(19, 25, 'uploads/1771158973_0_1627659.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:36:13'),
(20, 26, 'uploads/1771159101_0_1627631.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:38:21'),
(21, 27, 'uploads/1771159247_0_R5qb221xTxOrTW4_Oz6w6Q.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:40:47'),
(22, 28, 'uploads/1771159491_0_1627628.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:44:51'),
(23, 29, 'uploads/1771159598_0_1-vj4V5wRUCpursBXBglGQ.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:46:38'),
(24, 30, 'uploads/1771160221_0_1793477.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:57:01'),
(25, 31, 'uploads/1771160350_0_U1dj44RpQkmyhcDcYseOxQ.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:59:10'),
(26, 32, 'uploads/1771160394_0_ReEWx6NXRwGh9Rd1fXj9Sg.png.transform.vca-w820-2x.avif', 1, '2026-02-15 12:59:54'),
(27, 33, 'uploads/1771160516_0_W9cY3X-UQMiolV1DG5JKtg.png.transform.vca-w820-2x.avif', 1, '2026-02-15 13:01:56'),
(28, 34, 'uploads/1771160691_0_1793471.png.transform.vca-w820-2x.avif', 1, '2026-02-15 13:04:51'),
(29, 35, 'uploads/1771160836_0_8eha9YIaRbWBZM_et7N-zw.png.transform.vca-w820-2x.avif', 1, '2026-02-15 13:07:16'),
(30, 36, 'uploads/1771215626_0_2138777.png.transform.vca-w820-2x.avif', 1, '2026-02-16 04:20:26'),
(31, 37, 'uploads/1771216282_0_CyfxD3_ja0Kv__qstXS3Xw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 04:31:22'),
(32, 38, 'uploads/1771216472_0_1626814.png.transform.vca-w820-2x.avif', 1, '2026-02-16 04:34:32'),
(33, 39, 'uploads/1771216713_0_t1YowOPuzECAr6BTribspw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 04:38:33'),
(34, 40, 'uploads/1771216829_0_1626926.png.transform.vca-w820-2x.avif', 1, '2026-02-16 04:40:29'),
(35, 41, 'uploads/1771217030_0_3BA8ldDwhkSX0dEOVDIlKQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 04:43:50'),
(36, 42, 'uploads/1771217459_0_1626775.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 04:50:59'),
(38, 44, 'uploads/1771229128_0_UL5jG-sdTXuwIVmNsyS-BQ.avif', 1, '2026-02-16 08:05:28'),
(39, 45, 'uploads/1771229259_0_R8UGWQ1QSWydtY6IssVMgQ.avif', 1, '2026-02-16 08:07:39'),
(40, 46, 'uploads/1771232631_0_1dYoNQ7rReu2RaAqIBbvIw.avif', 1, '2026-02-16 09:03:51'),
(41, 47, 'uploads/1771232772_0_c33CZ-sKSCSsv2f_AXZTzg.avif', 1, '2026-02-16 09:06:12'),
(42, 48, 'uploads/1771232878_0__Ov41OCITKeGmE2XPv5lvA.avif', 1, '2026-02-16 09:07:58'),
(43, 49, 'uploads/1771232966_0_YgwIO3dDT3249R3dF7DYRg.avif', 1, '2026-02-16 09:09:26'),
(44, 50, 'uploads/1771233233_0_w0OGy7fnTEWXJMxQ_M4dMg.avif', 1, '2026-02-16 09:13:53'),
(45, 51, 'uploads/1771233373_0_wpl_T08DQxO1617Cjn8eOQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 09:16:13'),
(46, 52, 'uploads/1771233652_0_8z-kPJ9NQT-1riuPh_Bzyg.avif', 1, '2026-02-16 09:20:52'),
(47, 53, 'uploads/1771233901_0_xq4PsmVsTQinKur31I3gIQ.avif', 1, '2026-02-16 09:25:01'),
(48, 54, 'uploads/1771234070_0_tRVejRrwTEa2gceGy1oqmA.avif', 1, '2026-02-16 09:27:50'),
(49, 55, 'uploads/1771234264_0_qo2h-cwtTkeFHSHDEfsSlA.avif', 1, '2026-02-16 09:31:04'),
(50, 56, 'uploads/1771234600_0_wx1sGS3RuEmfJ5FzETOaLg.png.transform.vca-w820-2x.avif', 1, '2026-02-16 09:36:40'),
(51, 57, 'uploads/1771234840_0_pDEuWFekj0Ch7YDH9x4aBQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 09:40:40'),
(52, 58, 'uploads/1771234937_0_2pPDVXnA6EqiFX7HTVJOzw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 09:42:17'),
(53, 59, 'uploads/1771235107_0_Ry5g_xqSRp-o_i0g8714gQ.avif', 1, '2026-02-16 09:45:07'),
(54, 59, 'uploads/1771235107_1_Q2ITbq_qQDGSE7RlenvAkg.avif', 0, '2026-02-16 09:45:07'),
(55, 60, 'uploads/1771235295_0_1627736.png.transform.vca-w820-2x.avif', 1, '2026-02-16 09:48:15'),
(56, 60, 'uploads/1771235295_1_1627757.png.transform.vca-w820-2x.avif', 0, '2026-02-16 09:48:15'),
(57, 61, 'uploads/1771235451_0_1627210.png.transform.vca-w820-2x.avif', 1, '2026-02-16 09:50:51'),
(58, 61, 'uploads/1771235451_1_1627206.png.transform.vca-w820-2x.avif', 0, '2026-02-16 09:50:51'),
(59, 62, 'uploads/1771235943_0_8SVLf7dvToWyjc5-V_AMFQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 09:59:03'),
(60, 63, 'uploads/1771236028_0_YEmBat5TQWGLu9nuGUeRcQ.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:00:28'),
(61, 64, 'uploads/1771236088_0_Bii4WBrbQJ2pqEdZoGHwnA.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:01:28'),
(62, 65, 'uploads/1771236298_0_rWXl_1YZTmipGvOt7-UprA.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:04:58'),
(63, 66, 'uploads/1771236440_0_L14wag6FQhCQEyysNKQN2g.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:07:20'),
(64, 67, 'uploads/1771236549_0_D5-YFu7LRQaFdHqcIYDM8w.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:09:09'),
(65, 68, 'uploads/1771236706_0_EewS_xfLSAGKNMi-A2WOSw.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:11:46'),
(66, 69, 'uploads/1771238071_0_Xovzb3HFKUW0t5VWo6q_dQ.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:34:31'),
(67, 70, 'uploads/1771238221_0_BoV0F2c7kkunYFbVii3h1A.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:37:01'),
(68, 71, 'uploads/1771238471_0_Q-MNvRAKTjyLYIdZaVUbqg.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:41:11'),
(69, 72, 'uploads/1771238613_0_9k6etCBrSaKzsIxpRDdkQw.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:43:33'),
(70, 73, 'uploads/1771238769_0_bstD37ArSJCugXc_RVsHVA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 10:46:09'),
(71, 74, 'uploads/1771239132_0_1QbtqB3wRoWlgoLcc0LSUQ.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 10:52:12'),
(72, 75, 'uploads/1771253613_0_1m76uYRcTgCQzQx26QMLrg.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 14:53:33'),
(73, 76, 'uploads/1771253708_0_xsdHP8qGTfGE1cQLUIQsCQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 14:55:08'),
(74, 77, 'uploads/1771253794_0_BPwyF7fJQ1ieWpWxaEmpag.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 14:56:34'),
(75, 78, 'uploads/1771253891_0_aGmj48yCR_G651TxyUiNEQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 14:58:11'),
(77, 80, 'uploads/1771254344_0_aPaOnArkRdWOA9AshbctGA (1).avif', 1, '2026-02-16 15:05:44'),
(78, 81, 'uploads/1771254503_0_9Jlm3ZFrvkOpSdWNW5bs4Q.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 15:08:23'),
(79, 79, 'uploads/1771254723_0_HZTKvVi2SAuVWgUYimH4sw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:12:03'),
(80, 82, 'uploads/1771254988_0_QhltTcsMQmWnFXzyT5m9aw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:16:28'),
(81, 83, 'uploads/1771255146_0_z-U0BbI9SjeHJBcdRTELpg.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 15:19:06'),
(82, 84, 'uploads/1771255233_0_sSBfdkvdRcOK8LurAkDb5w.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:20:33'),
(83, 85, 'uploads/1771255543_0_sSBfdkvdRcOK8LurAkDb5w.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 15:25:43'),
(84, 86, 'uploads/1771255946_0_DhmNVF3fmUiqMPRerWHIww.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:32:26'),
(85, 87, 'uploads/1771256304_0_pq_pcwjKRkujwDKfxHmL1g.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:38:24'),
(86, 88, 'uploads/1771256579_0_FNDYu8EdTGC_j3c6quahQA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:43:00'),
(87, 89, 'uploads/1771256719_0_6Vo-7xjCQtqH9cjV3cHWHA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:45:19'),
(88, 90, 'uploads/1771256904_0_LkX8ReuuRjC_27cQr1Y-YQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:48:24'),
(89, 91, 'uploads/1771257066_0_mIP54ezcRgKU_1zXDxQF4Q.png.transform.vca-w820-2x (1).avif', 1, '2026-02-16 15:51:06'),
(90, 92, 'uploads/1771257200_0_jxFmmAveQGug7p-e7QXbsg.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:53:20'),
(91, 93, 'uploads/1771257383_0_5_1rPmT6ScScd6pm72ku_Q.png.transform.vca-w820-2x.avif', 1, '2026-02-16 15:56:23'),
(92, 94, 'uploads/1771257884_0_2MQzbTFRRfiPO3rNArJsIg.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:04:44'),
(93, 95, 'uploads/1771257971_0_jVjtpVxWQgma3CGbWb0Arw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:06:11'),
(94, 96, 'uploads/1771258160_0__q-CCmZJRs6uojqId_WC1Q.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:09:20'),
(95, 97, 'uploads/1771258451_0_z6vchqBcUU-5IiEOPBJHqA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:14:11'),
(96, 98, 'uploads/1771258613_0_XcJ9MnMsQUKhJtJIhp0h6g.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:16:53'),
(97, 99, 'uploads/1771258670_0_gGGsjHNUR0OnS_3RWBxWIw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:17:50'),
(98, 100, 'uploads/1771258798_0_i8I7yXgKRfKeoTeu2C2SWA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:19:58'),
(99, 101, 'uploads/1771259131_0__q-CCmZJRs6uojqId_WC1Q.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:25:31'),
(100, 101, 'uploads/1771259131_1_jVjtpVxWQgma3CGbWb0Arw.png.transform.vca-w820-2x.avif', 0, '2026-02-16 16:25:31'),
(101, 101, 'uploads/1771259131_2_2MQzbTFRRfiPO3rNArJsIg.png.transform.vca-w820-2x.avif', 0, '2026-02-16 16:25:31'),
(102, 102, 'uploads/1771259296_0_baRx0XZWLEGlEI3jzWL2Kw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:28:16'),
(103, 103, 'uploads/1771259408_0_xTimuwKzR6Cw5hlqEhgKxw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:30:08'),
(104, 104, 'uploads/1771259574_0_1626651.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:32:54'),
(105, 105, 'uploads/1771259685_0_1627669.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:34:45'),
(106, 106, 'uploads/1771259866_0_BuYLbz_P9kSAC-j0wNhkCg.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:37:46'),
(107, 107, 'uploads/1771260030_0_YMMijKoVmUy1d7tGxMJOlw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:40:30'),
(108, 108, 'uploads/1771260155_0_9HFr1MbKBESXj01uTrEcaA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:42:35'),
(109, 109, 'uploads/1771260311_0_FtrcpcZtVEWxWURG9DpDAw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 16:45:11'),
(112, 110, 'uploads/1771261016_0_IMG_6255-removebg-preview-Photoroom.png', 1, '2026-02-16 16:56:56'),
(113, 111, 'uploads/1771261411_0_CkjGriQ0Q4inrP8J1IKBxw.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:03:31'),
(114, 112, 'uploads/1771261573_0_wta3HoAqStuneBROWdNKug.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:06:13'),
(115, 113, 'uploads/1771261670_0__2YhYAQDQ7-KIvRqqldGAA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:07:50'),
(116, 114, 'uploads/1771261782_0_aMMyTGTyQWa-dPtZG_8vgA.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:09:42'),
(117, 115, 'uploads/1771261876_0_1626778.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:11:16'),
(118, 116, 'uploads/1771262159_0_MRahQYJXR4-BjKjcT5dgxQ.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:15:59'),
(119, 117, 'uploads/1771262283_0_1626986.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:18:03'),
(120, 118, 'uploads/1771262365_0_6xKiKKo2S_G0-gac8NsB1A.png.transform.vca-w820-2x.avif', 1, '2026-02-16 17:19:25'),
(121, 119, 'uploads/1771306920_0_459763.avif', 1, '2026-02-17 05:42:00'),
(122, 120, 'uploads/1771307159_0_460145.avif', 1, '2026-02-17 05:45:59'),
(123, 121, 'uploads/1771307299_0_462052.avif', 1, '2026-02-17 05:48:19'),
(124, 121, 'uploads/1771307299_1_459805.avif', 0, '2026-02-17 05:48:19'),
(125, 122, 'uploads/1771307568_0_462058.avif', 1, '2026-02-17 05:52:48'),
(126, 122, 'uploads/1771307568_1_461036.avif', 0, '2026-02-17 05:52:48'),
(127, 123, 'uploads/1771307723_0_472219.avif', 1, '2026-02-17 05:55:23'),
(128, 124, 'uploads/1771307899_0_462054.avif', 1, '2026-02-17 05:58:19'),
(129, 124, 'uploads/1771307899_1_461815.avif', 0, '2026-02-17 05:58:19'),
(130, 125, 'uploads/1771308112_0_1495244.avif', 1, '2026-02-17 06:01:52'),
(131, 126, 'uploads/1771308435_0_1474481.avif', 1, '2026-02-17 06:07:15'),
(132, 127, 'uploads/1771308556_0_1538877.avif', 1, '2026-02-17 06:09:16'),
(133, 128, 'uploads/1771308604_0_1629181.avif', 1, '2026-02-17 06:10:04'),
(134, 129, 'uploads/1771311106_0_1567314.avif', 1, '2026-02-17 06:51:46'),
(135, 130, 'uploads/1771311220_0_1567313.avif', 1, '2026-02-17 06:53:40'),
(136, 131, 'uploads/1771311287_0_1567315.avif', 1, '2026-02-17 06:54:47'),
(137, 132, 'uploads/1771311668_0_459820.avif', 1, '2026-02-17 07:01:08'),
(138, 133, 'uploads/1771311795_0_1529990.avif', 1, '2026-02-17 07:03:15'),
(139, 134, 'uploads/1771311846_0_1525669.avif', 1, '2026-02-17 07:04:06'),
(140, 135, 'uploads/1771312155_0_1338623.avif', 1, '2026-02-17 07:09:15'),
(141, 135, 'uploads/1771312155_1_1337239.avif', 0, '2026-02-17 07:09:15'),
(142, 136, 'uploads/1771312674_0_1266500.avif', 1, '2026-02-17 07:17:54'),
(143, 136, 'uploads/1771312674_1_1307850.avif', 0, '2026-02-17 07:17:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
