-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 10:50 AM
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
-- Table structure for table `cakes`
--

CREATE TABLE `cakes` (
  `cID` int(11) NOT NULL,
  `tID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `cFlavor` int(11) NOT NULL,
  `cSize` int(11) NOT NULL,
  `cMessage` text DEFAULT NULL,
  `cInstructions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cakes`
--

INSERT INTO `cakes` (`cID`, `tID`, `pID`, `cFlavor`, `cSize`, `cMessage`, `cInstructions`) VALUES
(0, 19, 42, 3, 10, 'February 10', 'no side icing');

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
(52, 19, 42, 1),
(53, 19, 35, 1),
(54, 19, 41, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pID` int(11) NOT NULL,
  `pName` varchar(50) NOT NULL,
  `pPrice` float(9,2) NOT NULL,
  `pDesc` text NOT NULL,
  `pType` int(11) NOT NULL DEFAULT 0,
  `pVisibility` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pID`, `pName`, `pPrice`, `pDesc`, `pType`, `pVisibility`) VALUES
(35, 'Brown Butter Cookie', 270.00, 'Indulge in the rich, nutty flavor of our Brown Butter Cookie, a game-changing twist on the classic favorite. Made with browned butter that&#39;s carefully cooked to perfection, these chewy cookies boast a deep, caramel-like flavor that&#39;s balanced by a hint of sweetness.\r\n\r\nWith a tender, crinkled texture and a delicate crunch, every bite is a delight. The brown butter adds a sophisticated, almost-butterscotch flavor that elevates this cookie to new heights. Perfect for those who crave something a little more complex and interesting in their cookie game.', 1, 1),
(36, 'Double Chocolate and Wallnut Cookie', 320.00, 'Decadence in every bite, our Double Chocolate Walnut Cookie is a chocolate lover&#39;s dream come true. Rich, velvety dark chocolate is paired with the deep, satisfying flavor of walnuts, creating a cookie that&#39;s both indulgent and irresistible.\r\n\r\nWith a dense, fudgy texture and a crinkled surface, these cookies are packed with intense chocolate flavor and crunchy walnut bits. The perfect treat for anyone who craves a serious chocolate fix, our Double Chocolate Walnut Cookie is a delight for the senses.', 1, 1),
(37, 'White Chocolate Mcadamia Cookie', 370.00, 'Escape to a tropical paradise with our White Chocolate Macadamia Cookie, a sweet and indulgent treat that&#39;s as creamy as it is crunchy. Rich white chocolate chunks are perfectly balanced by the buttery, nutty flavor of macadamia nuts, creating a cookie that&#39;s both refreshing and decadent.\r\n\r\nWith a tender, chewy texture and a delicate crunch from the macadamias, these cookies are a masterclass in texture and flavor. The white chocolate adds a sweet and creamy element, while the macadamias provide a satisfying crunch. Perfect for those who crave a cookie that&#39;s a little bit sweet, a little bit nutty, and totally irresistible.', 1, 1),
(38, 'NYC-Style Cookie', 450.00, 'Experience the classic charm of the Big Apple with our NYC-Style Cookie, a timeless treat that&#39;s as iconic as the city itself. Soft, chewy, and utterly irresistible, these cookies are made with a special blend of ingredients that captures the essence of a traditional New York City bakery.\r\n\r\nWith a thick, chunky texture and a subtle crunch, these cookies are loaded with a rich, buttery flavor that&#39;s balanced by a hint of sweetness. Perfect for dunking in milk or enjoying on its own, our NYC-Style Cookie is a nostalgic treat that&#39;s sure to transport you to the streets of Manhattan.', 1, 1),
(41, 'Classic Brownies', 480.00, 'Indulge in the rich, fudgy goodness of our Classic Brownies, a timeless treat that&#39;s sure to satisfy any chocolate craving. Made with a special blend of dark chocolate and premium ingredients, these brownies are dense, moist, and utterly irresistible.\r\n\r\nCut into a generous 8x8 size, these brownies are perfect for sharing (or not!). With a velvety texture and a deep, chocolatey flavor, they&#39;re sure to become a new favorite. Whether you&#39;re a chocolate aficionado or just looking for a sweet treat, our Classic Brownies are the perfect choice.', 2, 1),
(42, 'Minimal Cake', 1500.00, 'This elegant cake features a smooth, monochromatic base and a minimalist design. The calendar detail adds a personal touch, making it perfect for celebrating special occasions.', 3, 1);

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
(19, 8, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL);

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
(7, 'Reece', 'Santos', 3, 'reecesantos33@gmail.com', '$2y$10$M1pZRgZ77iOVMnIuBvErEuN5GkIuGlFrfXvyuAbbj3Fc.spE6w3mG', NULL, '03', '0314', '031406', '031406014', 'N/A', 'N/A'),
(8, 'Ariel', 'Santos', 1, 'arielsantos21070@gmail.com', '$2y$10$Uvt8eMvtMle5BsDCnTJS1O.jMvOCwITKaZ7s76qJro9tztgpnMYRq', NULL, '03', '0314', '031406', '031406014', 'N/A', 'N/A');

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
  MODIFY `oID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `tID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
