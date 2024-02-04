-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2024 at 09:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

-- Database was created by Matyáš Závora (matyaszavora@outlook.com) for the Alpha 3 project

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alpha3`
--
CREATE DATABASE IF NOT EXISTS `alpha3` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `alpha3`;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `zip` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `address`, `zip`, `city`, `country`) VALUES
(1, 'Company B', '456 Oak St', 56789, 'City B', 'CA'),
(3, 'Company C', '789 Maple St', 98765, 'City C', 'UK');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `type` enum('insert','delete','view','') NOT NULL,
  `id_user` int(11) NOT NULL,
  `occured` datetime DEFAULT current_timestamp(),
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `type`, `id_user`, `occured`, `description`) VALUES
(1, '', 1, '2024-02-04 21:30:33', 'Read table user'),
(2, '', 1, '2024-02-04 21:30:34', 'Read table parcel'),
(3, '', 1, '2024-02-04 21:30:35', 'Read table owner'),
(4, 'delete', 1, '2024-02-04 21:30:37', 'Delete data from table owner'),
(5, '', 1, '2024-02-04 21:30:37', 'Read table owner'),
(6, '', 1, '2024-02-04 21:30:40', 'Read table ownership_list'),
(7, '', 1, '2024-02-04 21:34:44', 'Read table ownership_list'),
(8, '', 1, '2024-02-04 21:34:45', 'Read table parcel'),
(9, 'delete', 1, '2024-02-04 21:34:46', 'Delete data from table parcel'),
(10, '', 1, '2024-02-04 21:34:46', 'Read table parcel'),
(11, 'delete', 1, '2024-02-04 21:34:47', 'Delete data from table parcel'),
(12, '', 1, '2024-02-04 21:34:47', 'Read table parcel'),
(13, 'delete', 1, '2024-02-04 21:34:47', 'Delete data from table parcel'),
(14, '', 1, '2024-02-04 21:34:47', 'Read table parcel'),
(15, '', 1, '2024-02-04 21:34:56', 'Read table ownership_list'),
(16, '', 1, '2024-02-04 21:34:58', 'Read table parcel'),
(17, '', 1, '2024-02-04 21:34:59', 'Read table owner'),
(18, 'delete', 1, '2024-02-04 21:35:02', 'Delete data from table owner'),
(19, '', 1, '2024-02-04 21:35:02', 'Read table owner'),
(20, '', 1, '2024-02-04 21:35:04', 'Read table owner'),
(21, '', 1, '2024-02-04 21:35:06', 'Read table company'),
(22, 'delete', 1, '2024-02-04 21:35:07', 'Delete data from table company'),
(23, '', 1, '2024-02-04 21:35:07', 'Read table company');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `phone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id`, `first_name`, `last_name`, `phone`) VALUES
(2, 'Bob', 'Johnson', 555555555);

-- --------------------------------------------------------

--
-- Table structure for table `ownership_list`
--

CREATE TABLE `ownership_list` (
  `id` int(11) NOT NULL,
  `id_parcel` int(11) NOT NULL,
  `id_owner` int(11) DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `stake` float(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ownership_list`
--

INSERT INTO `ownership_list` (`id`, `id_parcel`, `id_owner`, `id_company`, `stake`) VALUES
(5, 4, NULL, 1, 1.00),
(6, 5, 2, NULL, 0.70),
(7, 5, NULL, 3, 0.30);

-- --------------------------------------------------------

--
-- Table structure for table `parcel`
--

CREATE TABLE `parcel` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `size` float(8,2) NOT NULL,
  `latitude` float(10,2) NOT NULL,
  `longitude` float(10,2) NOT NULL,
  `date_of_ownership` datetime NOT NULL DEFAULT current_timestamp(),
  `legal_state` enum('owned','leased','pledged','') NOT NULL,
  `type` enum('zastavěné plochy a nádvoří','lesní pozemky','vodní plochy','ostatní plochy','zemědělské pozemky') NOT NULL,
  `address` varchar(100) NOT NULL,
  `zip` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcel`
--

INSERT INTO `parcel` (`id`, `number`, `size`, `latitude`, `longitude`, `date_of_ownership`, `legal_state`, `type`, `address`, `zip`, `city`, `country`) VALUES
(4, 102, 85.30, 34.05, -118.24, '2024-01-26 14:17:24', 'leased', 'zemědělské pozemky', '789 Elm St', 87654, 'City E', 'CA'),
(5, 103, 200.00, 51.51, -0.12, '2024-01-26 14:17:24', 'pledged', 'lesní pozemky', '123 Birch St', 13579, 'City F', 'UK'),
(6, 101, 120.50, 40.71, -74.01, '2024-01-26 14:17:24', 'owned', 'zastavěné plochy a nádvoří', '456 Pine St', 54321, 'City D', 'US');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`) VALUES
(1, 'admin@admin.com', '$2y$10$4bR87TXTrpf1uHj7UbbHIenGaIQkFz5HE2WuijQ6pISCxsZ8eVdlK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_user` (`id_user`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ownership_list`
--
ALTER TABLE `ownership_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ownership_list_owner` (`id_owner`),
  ADD KEY `fk_ownership_list_parcel` (`id_parcel`),
  ADD KEY `fk_ownership_list_company` (`id_company`);

--
-- Indexes for table `parcel`
--
ALTER TABLE `parcel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ownership_list`
--
ALTER TABLE `ownership_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `parcel`
--
ALTER TABLE `parcel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Constraints for table `ownership_list`
--
ALTER TABLE `ownership_list`
  ADD CONSTRAINT `fk_ownership_list_company` FOREIGN KEY (`id_company`) REFERENCES `company` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ownership_list_owner` FOREIGN KEY (`id_owner`) REFERENCES `owner` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ownership_list_parcel` FOREIGN KEY (`id_parcel`) REFERENCES `parcel` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
