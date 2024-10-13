-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2024 at 11:33 PM
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
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `tID` int(11) NOT NULL,
  `uID` int(11) NOT NULL,
  `tType` int(11) DEFAULT NULL,
  `tStatus` int(11) NOT NULL DEFAULT 1,
  `tDeduct` float(9,2) DEFAULT NULL,
  `tDateOrder` timestamp NULL DEFAULT NULL,
  `tPayID` varchar(50) DEFAULT NULL,
  `tPayIDRemain` varchar(50) DEFAULT NULL,
  `tPayRemain` float(9,2) DEFAULT NULL,
  `tPayStatus` int(11) NOT NULL,
  `tDateClaim` date DEFAULT NULL,
  `tDateClaimed` timestamp NULL DEFAULT NULL,
  `tCancelReason` text DEFAULT NULL,
  `tCancelTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`tID`, `uID`, `tType`, `tStatus`, `tDeduct`, `tDateOrder`, `tPayID`, `tPayIDRemain`, `tPayRemain`, `tPayStatus`, `tDateClaim`, `tDateClaimed`, `tCancelReason`, `tCancelTime`) VALUES
(76, 8, 2, 6, NULL, '2024-10-13 21:08:27', 'cs_nwX2AEFh8zUzMx91n3c3cQu6', 'cs_5BuA8dHRD8nexWrybWy5zqvR', 0.00, 2, '2024-10-16', NULL, NULL, NULL),
(77, 8, 1, 6, NULL, '2024-10-13 21:08:47', 'cs_4R7M8XiohgCJjMVuDC7sxjFs', NULL, 0.00, 2, '2024-10-16', NULL, NULL, NULL),
(78, 8, 2, 6, NULL, '2024-10-13 21:09:14', 'cs_x4YZWZ6DcPxJwQxB1hVhJbXP', 'cs_4Ti6NwbX9hiu7gzpixEbfM69', 0.00, 2, '2024-10-15', NULL, NULL, NULL),
(79, 8, 1, 6, NULL, '2024-10-13 21:09:36', 'cs_pqVs35qwjCc6E5vYaJGJqpZr', NULL, 0.00, 2, '2024-10-15', NULL, NULL, NULL),
(80, 8, 2, -1, NULL, '2024-10-13 21:16:06', 'cs_W665Ai9K9ipCNeHevXPreHZa', NULL, 950.00, 1, '2024-10-16', NULL, 'duplicate', '2024-10-13 21:22:44'),
(81, 8, 1, -1, NULL, '2024-10-13 21:16:32', 'cs_3TCvkrfV1a53q54KXbKM3W1N', NULL, 0.00, 2, '2024-10-16', NULL, 'duplicate', '2024-10-13 21:22:40'),
(82, 8, 2, 5, NULL, '2024-10-13 21:16:52', 'cs_C7BH5LfrZVmBsfpszBFgHq8N', 'cs_He6rzibiSrMoZUDAfkSN5MXZ', 0.00, 2, '2024-10-15', NULL, NULL, NULL),
(85, 8, 2, 0, NULL, '2024-10-13 21:30:36', 'cs_sTAhgNW8z48tU51rNqb2sQBe', 'cs_3SqRXmF3YKtj2KY7rr9Vy7FE', 0.00, 2, '2024-10-15', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`tID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `tID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
