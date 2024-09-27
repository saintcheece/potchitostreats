-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 05:45 PM
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
-- Database: `potchitos`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `oID` int(11) NOT NULL,
  `tID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `oQty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`oID`, `tID`, `pID`, `oQty`) VALUES
(19, 14, 14, 2),
(20, 14, 16, 1),
(21, 15, 14, 3),
(22, 15, 16, 8),
(23, 16, 16, 15),
(24, 17, 14, 4),
(25, 17, 19, 1),
(26, 17, 21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pID` int(11) NOT NULL,
  `pName` varchar(50) NOT NULL,
  `pPrice` float(9,2) NOT NULL,
  `pDesc` text NOT NULL,
  `pShelfLife` int(11) NOT NULL,
  `pType` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pID`, `pName`, `pPrice`, `pDesc`, `pShelfLife`, `pType`) VALUES
(14, 'Honeybun', 40.00, 'honey with bun', 3, 1),
(16, 'Custom Cake (Large)', 5000.00, 'Special occasions calls for special cakes, write in the description the cake you want us to make.', 5, 3),
(18, 'Chocolate Chip Cookie', 60.00, 'The classic crunchy homemade cookie with thick-chipped chocolate toppings.', 10, 2),
(19, 'Matcha Cookies', 60.00, 'Matcha flavored crunchy cookies', 5, 2),
(20, 'Product 1', 50.00, 'Qwerty', 3, 2),
(21, 'Product 2', 100.00, 'qwerty', 3, 1),
(22, 'Product 3', 250.00, 'Qwerty', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `sID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `sMfgDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sQty` int(11) NOT NULL,
  `sStat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`sID`, `pID`, `sMfgDate`, `sQty`, `sStat`) VALUES
(1, 14, '2024-09-22 03:05:00', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `tID` int(11) NOT NULL,
  `uID` int(11) NOT NULL,
  `tType` int(11) NOT NULL,
  `tStatus` int(11) NOT NULL DEFAULT 1,
  `tTaxDeduct` float(9,2) DEFAULT NULL,
  `tDateOrder` timestamp NULL DEFAULT NULL,
  `tDateClaim` timestamp NULL DEFAULT NULL,
  `tRevStars` float DEFAULT NULL,
  `tRevDesc` text DEFAULT NULL,
  `tRevDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`tID`, `uID`, `tType`, `tStatus`, `tTaxDeduct`, `tDateOrder`, `tDateClaim`, `tRevStars`, `tRevDesc`, `tRevDate`) VALUES
(14, 1, 2, 4, NULL, '2024-09-24 06:23:51', NULL, NULL, NULL, NULL),
(15, 1, 2, 0, NULL, '2024-09-24 10:39:10', NULL, NULL, NULL, NULL),
(16, 1, 2, 5, NULL, '2024-09-24 10:39:37', NULL, NULL, NULL, NULL),
(17, 1, 2, 3, NULL, '2024-09-25 08:13:59', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uID` int(11) NOT NULL,
  `uFName` varchar(50) NOT NULL,
  `uLName` varchar(50) NOT NULL,
  `uType` int(11) NOT NULL DEFAULT 1,
  `uEmail` varchar(50) NOT NULL,
  `uPass` varchar(250) NOT NULL,
  `uPhone` varchar(11) DEFAULT NULL,
  `uAddrRegion` varchar(50) NOT NULL,
  `uAddrProvince` varchar(50) NOT NULL,
  `uAddrCity` varchar(50) NOT NULL,
  `uAddrTown` varchar(50) NOT NULL,
  `uAddrStreet` varchar(50) NOT NULL,
  `uAddrHouseNum` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uID`, `uFName`, `uLName`, `uType`, `uEmail`, `uPass`, `uPhone`, `uAddrRegion`, `uAddrProvince`, `uAddrCity`, `uAddrTown`, `uAddrStreet`, `uAddrHouseNum`) VALUES
(1, 'cust', 'omer', 1, 'cust@email.com', 'Cust_123', '12345678910', '', '', '', '', '', ''),
(2, 'admin', 'admin', 3, 'admin@email.com', 'Admin_123', '12345678910', '', '', '', '', '', ''),
(7, 'Reece', 'Santos', 1, 'reecesantos33@gmail.com', '$2y$10$M1pZRgZ77iOVMnIuBvErEuN5GkIuGlFrfXvyuAbbj3Fc.spE6w3mG', NULL, '03', '0314', '031406', '031406014', 'N/A', 'N/A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`oID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pID`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`sID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`tID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `oID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `sID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `tID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
