-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2021 at 10:44 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testui`
--

-- --------------------------------------------------------

--
-- Table structure for table `process_item_statuses`
--

CREATE TABLE `process_item_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `process_item_statuses`
--

INSERT INTO `process_item_statuses` (`id`, `status_title`, `created_at`, `updated_at`, `status`, `deleted_at`) VALUES
(1, 'Assign', NULL, NULL, 7, NULL),
(2, 'Review', NULL, NULL, 7, NULL),
(3, 'Recommended for special approval', NULL, NULL, 7, NULL),
(4, 'Not approved', NULL, NULL, 7, NULL),
(5, 'Approved', NULL, NULL, 7, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `process_item_statuses`
--
ALTER TABLE `process_item_statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `process_item_statuses`
--
ALTER TABLE `process_item_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
