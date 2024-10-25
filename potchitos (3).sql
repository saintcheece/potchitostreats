-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 09:07 AM
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
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `aID` int(11) NOT NULL,
  `uID` int(11) NOT NULL,
  `aOpID` int(11) NOT NULL,
  `aTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit`
--

INSERT INTO `audit` (`aID`, `uID`, `aOpID`, `aTime`) VALUES
(123, 10, 103, '2024-10-24 18:53:49'),
(124, 10, 103, '2024-10-24 18:59:28'),
(125, 10, 103, '2024-10-24 21:56:03'),
(126, 10, 103, '2024-10-24 21:58:20'),
(127, 10, 103, '2024-10-24 22:04:59'),
(128, 10, 103, '2024-10-24 22:15:11'),
(129, 10, 103, '2024-10-24 22:20:59'),
(130, 10, 102, '2024-10-24 22:23:05'),
(131, 8, 101, '2024-10-24 22:23:12'),
(132, 8, 109, '2024-10-25 00:43:53'),
(133, 8, 202, '2024-10-25 00:54:30'),
(134, 10, 201, '2024-10-25 00:54:58'),
(135, 10, 102, '2024-10-25 01:03:36'),
(136, 8, 101, '2024-10-25 01:05:07'),
(137, 8, 202, '2024-10-25 01:17:03'),
(138, 10, 201, '2024-10-25 01:17:14'),
(139, 10, 102, '2024-10-25 01:17:25'),
(140, 8, 101, '2024-10-25 01:17:31'),
(141, 8, 202, '2024-10-25 01:17:40'),
(142, 10, 201, '2024-10-25 01:17:47'),
(143, 10, 103, '2024-10-25 01:26:48'),
(144, 10, 102, '2024-10-25 01:28:20'),
(145, 8, 101, '2024-10-25 01:28:31'),
(146, 8, 109, '2024-10-25 01:31:54'),
(147, 8, 202, '2024-10-25 01:32:29'),
(148, 10, 201, '2024-10-25 01:32:50'),
(149, 10, 102, '2024-10-25 01:33:28'),
(150, 8, 101, '2024-10-25 01:33:38'),
(151, 8, 202, '2024-10-25 01:35:36'),
(152, 10, 201, '2024-10-25 01:35:55'),
(153, 10, 102, '2024-10-25 01:36:03'),
(154, 8, 101, '2024-10-25 01:36:17'),
(155, 8, 202, '2024-10-25 01:36:56'),
(156, 10, 201, '2024-10-25 01:37:03'),
(157, 10, 102, '2024-10-25 01:37:12'),
(158, 8, 101, '2024-10-25 01:37:32'),
(159, 8, 202, '2024-10-25 01:37:39'),
(160, 10, 201, '2024-10-25 01:37:46'),
(161, 10, 102, '2024-10-25 01:37:55'),
(162, 8, 101, '2024-10-25 01:38:16'),
(163, 8, 109, '2024-10-25 01:42:19'),
(164, 8, 202, '2024-10-25 02:12:47'),
(165, 10, 201, '2024-10-25 02:13:12'),
(166, 10, 102, '2024-10-25 05:29:45'),
(167, 8, 101, '2024-10-25 05:51:00'),
(168, 8, 202, '2024-10-25 05:51:25'),
(169, 8, 101, '2024-10-25 05:51:43'),
(170, 8, 202, '2024-10-25 05:51:44'),
(171, 10, 201, '2024-10-25 05:51:50'),
(172, 10, 103, '2024-10-25 06:41:28'),
(173, 10, 103, '2024-10-25 06:45:20'),
(174, 10, 103, '2024-10-25 06:46:26'),
(175, 10, 103, '2024-10-25 06:48:29'),
(176, 10, 103, '2024-10-25 06:52:08'),
(177, 10, 102, '2024-10-25 06:52:12'),
(178, 8, 101, '2024-10-25 06:52:21'),
(179, 8, 202, '2024-10-25 06:58:24'),
(180, 10, 201, '2024-10-25 06:58:31'),
(181, 10, 103, '2024-10-25 07:00:28'),
(182, 10, 103, '2024-10-25 07:02:40'),
(183, 10, 102, '2024-10-25 07:02:46'),
(184, 8, 101, '2024-10-25 07:06:14'),
(185, 8, 202, '2024-10-25 07:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `audit_enum`
--

CREATE TABLE `audit_enum` (
  `aOpID` int(11) NOT NULL,
  `aOpDesc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_enum`
--

INSERT INTO `audit_enum` (`aOpID`, `aOpDesc`) VALUES
(100, 'View Audit Records'),
(101, 'Customer Log In'),
(102, 'Customer Log Out'),
(201, 'Admin Log In'),
(202, 'Admin Log Out'),
(103, 'Add Product'),
(104, 'Edit Product'),
(105, 'Delete Product'),
(106, 'Feature Product'),
(107, 'Update Order'),
(108, 'Cancel Order'),
(109, 'Check Out'),
(203, 'Update Contact Details'),
(109, 'View Transaction History'),
(110, 'View Customer Records'),
(111, 'Ban Customer'),
(112, 'View Orders'),
(113, 'Edit Order');

-- --------------------------------------------------------

--
-- Table structure for table `cakes`
--

CREATE TABLE `cakes` (
  `cID` int(11) NOT NULL,
  `tID` int(11) NOT NULL,
  `oID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `cMessage` text DEFAULT NULL,
  `cInstructions` text DEFAULT NULL,
  `cfID` int(11) DEFAULT NULL,
  `csID` int(11) DEFAULT NULL,
  `cLayers` int(11) NOT NULL,
  `ccID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cakes_color`
--

CREATE TABLE `cakes_color` (
  `ccID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `ccName` varchar(20) NOT NULL,
  `ccHex` varchar(20) NOT NULL,
  `ccIsVisible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cakes_color`
--

INSERT INTO `cakes_color` (`ccID`, `pID`, `ccName`, `ccHex`, `ccIsVisible`) VALUES
(25, 64, 'Choco Brown', '#604539', 1),
(26, 64, 'Cotton Candy Blue', '#61a0db', 1),
(27, 64, 'Blush Pink', '#f485b5', 1),
(28, 64, 'Leaf Green', '#5cc454', 1),
(29, 65, 'Bubblegum Blue', '#65a5e2', 1),
(30, 65, 'Hot Pink', '#f1abf2', 1),
(31, 65, 'Sunshine Yellow', '#ead54d', 1),
(32, 70, 'Unicorn White', '#f9e7f7', 1),
(33, 70, 'Bubblegum Blue', '#80bff9', 1),
(34, 70, 'Choco Brown', '#473838', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cakes_flavor`
--

CREATE TABLE `cakes_flavor` (
  `cfID` int(11) NOT NULL,
  `cfName` varchar(30) NOT NULL,
  `cfPrice` float(9,2) NOT NULL,
  `cfIsAvailable` int(11) DEFAULT 1,
  `pID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cakes_flavor`
--

INSERT INTO `cakes_flavor` (`cfID`, `cfName`, `cfPrice`, `cfIsAvailable`, `pID`) VALUES
(54, 'Chocolate', 40.00, 1, 64),
(55, 'Vanilla', 20.00, 1, 64),
(56, 'Chocolate', 40.00, 1, 65),
(57, 'Vanilla', 20.00, 1, 65),
(58, 'Strawberry', 30.00, 1, 65),
(59, 'Chocolate', 50.00, 1, 70),
(60, 'Vanilla', 20.00, 1, 70),
(61, 'Strawberry', 30.00, 1, 70);

-- --------------------------------------------------------

--
-- Table structure for table `cakes_layer`
--

CREATE TABLE `cakes_layer` (
  `clID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `clMaxCount` int(11) NOT NULL,
  `clMinCount` int(11) NOT NULL DEFAULT 1,
  `clDefault` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cakes_layer`
--

INSERT INTO `cakes_layer` (`clID`, `pID`, `clMaxCount`, `clMinCount`, `clDefault`) VALUES
(7, 64, 5, 3, 3),
(8, 65, 5, 3, 3),
(9, 70, 6, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cakes_size`
--

CREATE TABLE `cakes_size` (
  `csID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `csSize` int(11) NOT NULL,
  `csIsVisible` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cakes_size`
--

INSERT INTO `cakes_size` (`csID`, `pID`, `csSize`, `csIsVisible`) VALUES
(44, 64, 8, 1),
(45, 64, 12, 1),
(46, 64, 16, 1),
(47, 65, 6, 1),
(48, 65, 8, 1),
(49, 65, 12, 1),
(50, 70, 12, 1),
(51, 70, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `nID` int(11) NOT NULL,
  `uID` int(11) NOT NULL,
  `neOpID` int(11) NOT NULL,
  `nTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `nFor` int(11) NOT NULL,
  `nViewed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`nID`, `uID`, `neOpID`, `nTime`, `nFor`, `nViewed`) VALUES
(6, 8, 1, '2024-10-25 00:43:53', 0, 1),
(7, 8, 1, '2024-10-25 01:31:54', 0, 1),
(8, 8, 1, '2024-10-25 01:42:19', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications_enum`
--

CREATE TABLE `notifications_enum` (
  `neOpID` int(11) NOT NULL,
  `neOpDesc` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications_enum`
--

INSERT INTO `notifications_enum` (`neOpID`, `neOpDesc`) VALUES
(1, 'Checked Out'),
(2, 'Sent an Email Message'),
(3, 'Canceled Their Order'),
(4, 's orders are approaching Claim Date'),
(5, 'Made Changes on their Order');

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
(235, 109, 66, 1);

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
  `pVisibility` int(11) NOT NULL DEFAULT 1,
  `pPrepTime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pID`, `pName`, `pPrice`, `pDesc`, `pType`, `pVisibility`, `pPrepTime`) VALUES
(64, 'Number Cake', 900.00, 'Number cakes are delightful desserts crafted with a unique twist: they&#39;re shaped like numbers! These cakes are often made with your favorite cake flavors and frosted in a variety of colors and designs. They&#39;re perfect for celebrating birthdays, anniversaries, or any special occasion where you want to add a personal touch. The number can represent the age of the person being celebrated, a lucky number, or even a significant year.', 3, 1, 48),
(65, 'Minimal Calendar Cake', 1000.00, 'A minimalist calendar cake is a modern and elegant dessert that combines the simplicity of a calendar design with the sweetness of a cake. The cake is typically frosted in a single, neutral color like white or light gray, and then decorated with small, edible numbers to represent the days of the month. The minimalist aesthetic creates a clean and sophisticated look that is perfect for any special occasion.', 3, 1, 48),
(66, 'Brown Butter Cookie', 270.00, 'A brown butter cookie is a classic treat that is loved for its rich, nutty flavor and buttery texture. The key to making a brown butter cookie is to brown the butter on the stovetop until it turns a deep amber color and releases a nutty aroma. This process adds a unique depth of flavor to the cookies. Brown butter cookies are often made with a simple dough of flour, sugar, and eggs, but they can also be flavored with vanilla, cinnamon, or other spices. The result is a delicious and satisfying cookie that is perfect for any occasion.', 1, 1, 12),
(67, 'Classic Brownies', 480.00, 'Classic brownies are a beloved dessert known for their rich, fudgy texture and intense chocolate flavor. This recipe yields a generous 480 servings, cut into 64 slices, making it perfect for large gatherings or sharing with friends and family. The brownies are made with simple ingredients like flour, sugar, cocoa powder, eggs, oil, and boiling water, resulting in a classic, satisfying taste that everyone will enjoy. Whether you prefer them chewy or fudgy, these classic brownies are sure to be a hit.', 2, 1, 12),
(68, 'NYC-Style Cookie', 450.00, 'NYC-style cookies are known for their large size, chewy texture, and often bold flavors. They are often made with a high ratio of butter to sugar, creating a rich and indulgent taste. Some popular flavors include chocolate chip, oatmeal raisin, and snickerdoodle. These cookies are typically baked in a large, sheet pan, allowing them to spread out and become extra chewy.', 1, 1, 12),
(69, 'Cereal Milk Tresleches', 400.00, 'Cereal Milk Tres Leches is a delightful twist on the classic tres leches cake. Infused with the nostalgic flavor of cereal milk, this dessert offers a unique and comforting taste. The cake is soaked in a mixture of sweetened condensed milk, evaporated milk, and cereal milk, resulting in a moist and flavorful treat. Whether you prefer it topped with whipped cream, cereal crumbs, or a drizzle of chocolate sauce, Cereal Milk Tres Leches is sure to satisfy your sweet tooth and bring back fond memories of childhood.', 2, 1, 12),
(70, 'Dedication Slab Cake', 1000.00, 'A Dedication Slab Cake is a versatile and customizable dessert that can be used to honor a special person or occasion. It&#39;s typically a rectangular cake, frosted with a simple glaze or buttercream, and then decorated with personalized messages, photos, or other elements. The cake is often cut into squares or rectangles, making it easy to share with a large group of people. Dedication Slab Cakes are perfect for birthdays, anniversaries, graduations, or any event where you want to create a meaningful and memorable dessert.', 3, 1, 48);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `tID` int(11) NOT NULL,
  `oID` int(11) NOT NULL,
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

INSERT INTO `transactions` (`tID`, `oID`, `uID`, `tType`, `tStatus`, `tDeduct`, `tDateOrder`, `tPayID`, `tPayIDRemain`, `tPayRemain`, `tPayStatus`, `tDateClaim`, `tDateClaimed`, `tCancelReason`, `tCancelTime`) VALUES
(109, 0, 8, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL);

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
  `uAddrProvince` varchar(50) NOT NULL,
  `uAddrCity` varchar(50) NOT NULL,
  `uAddrTown` varchar(50) NOT NULL,
  `uAddrStreet` varchar(50) NOT NULL,
  `uAddrHouseNum` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uID`, `uFName`, `uLName`, `uType`, `uEmail`, `uPass`, `uPhone`, `uAddrProvince`, `uAddrCity`, `uAddrTown`, `uAddrStreet`, `uAddrHouseNum`) VALUES
(8, 'Ariel', 'Santos', 1, 'arielsantos21070@gmail.com', '$2y$10$Uvt8eMvtMle5BsDCnTJS1O.jMvOCwITKaZ7s76qJro9tztgpnMYRq', '09123456789', '0314', '031406', '031406014', 'N/A', 'N/A'),
(10, 'Reece', 'Santos', 3, 'reecesantos33@gmail.com', '$2y$10$Zj6lPSVfAUufCLFEWEKBceIDXaFp4yC0cUNM6LSXvW4LlkUBq1R9G', '9322003038', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `vID` int(11) NOT NULL,
  `vDate` date NOT NULL DEFAULT current_timestamp(),
  `vCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`vID`, `vDate`, `vCount`) VALUES
(1, '2024-10-21', 1),
(2, '2024-10-21', 1),
(3, '2024-10-21', 1),
(4, '2024-10-21', 1),
(5, '2024-10-21', 1),
(6, '2024-10-21', 1),
(7, '2024-10-21', 1),
(8, '2024-10-21', 1),
(9, '2024-10-21', 1),
(10, '2024-10-21', 1),
(11, '2024-10-21', 1),
(12, '2024-10-21', 1),
(13, '2024-10-22', 1),
(14, '2024-10-22', 1),
(15, '2024-10-22', 1),
(16, '2024-10-22', 1),
(17, '2024-10-22', 1),
(18, '2024-10-22', 1),
(19, '2024-10-22', 1),
(20, '2024-10-22', 1),
(21, '2024-10-22', 1),
(22, '2024-10-22', 1),
(23, '2024-10-22', 1),
(24, '2024-10-22', 1),
(25, '2024-10-22', 1),
(26, '2024-10-22', 1),
(27, '2024-10-22', 1),
(28, '2024-10-22', 1),
(29, '2024-10-22', 1),
(30, '2024-10-22', 1),
(31, '2024-10-22', 1),
(32, '2024-10-22', 1),
(33, '2024-10-23', 1),
(34, '2024-10-23', 1),
(35, '2024-10-23', 1),
(36, '2024-10-23', 1),
(37, '2024-10-23', 1),
(38, '2024-10-23', 1),
(39, '2024-10-23', 1),
(40, '2024-10-23', 1),
(41, '2024-10-23', 1),
(42, '2024-10-23', 1),
(43, '2024-10-23', 1),
(44, '2024-10-24', 1),
(45, '2024-10-24', 1),
(46, '2024-10-24', 1),
(47, '2024-10-24', 1),
(48, '2024-10-24', 1),
(49, '2024-10-24', 1),
(50, '2024-10-24', 1),
(51, '2024-10-24', 1),
(52, '2024-10-24', 1),
(53, '2024-10-24', 1),
(54, '2024-10-24', 1),
(55, '2024-10-24', 1),
(56, '2024-10-24', 1),
(57, '2024-10-24', 1),
(58, '2024-10-24', 1),
(59, '2024-10-24', 1),
(60, '2024-10-24', 1),
(61, '2024-10-24', 1),
(62, '2024-10-24', 1),
(63, '2024-10-24', 1),
(64, '2024-10-24', 1),
(65, '2024-10-25', 1),
(66, '2024-10-25', 1),
(67, '2024-10-25', 1),
(68, '2024-10-25', 1),
(69, '2024-10-25', 1),
(70, '2024-10-25', 1),
(71, '2024-10-25', 1),
(72, '2024-10-25', 1),
(73, '2024-10-25', 1),
(74, '2024-10-25', 1),
(75, '2024-10-25', 1),
(76, '2024-10-25', 1),
(77, '2024-10-25', 1),
(78, '2024-10-25', 1),
(79, '2024-10-25', 1),
(80, '2024-10-25', 1),
(81, '2024-10-25', 1),
(82, '2024-10-25', 1),
(83, '2024-10-25', 1),
(84, '2024-10-25', 1),
(85, '2024-10-25', 1),
(86, '2024-10-25', 1),
(87, '2024-10-25', 1),
(88, '2024-10-25', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`aID`);

--
-- Indexes for table `cakes`
--
ALTER TABLE `cakes`
  ADD PRIMARY KEY (`cID`);

--
-- Indexes for table `cakes_color`
--
ALTER TABLE `cakes_color`
  ADD PRIMARY KEY (`ccID`);

--
-- Indexes for table `cakes_flavor`
--
ALTER TABLE `cakes_flavor`
  ADD PRIMARY KEY (`cfID`);

--
-- Indexes for table `cakes_layer`
--
ALTER TABLE `cakes_layer`
  ADD PRIMARY KEY (`clID`);

--
-- Indexes for table `cakes_size`
--
ALTER TABLE `cakes_size`
  ADD PRIMARY KEY (`csID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`nID`);

--
-- Indexes for table `notifications_enum`
--
ALTER TABLE `notifications_enum`
  ADD PRIMARY KEY (`neOpID`);

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
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`vID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit`
--
ALTER TABLE `audit`
  MODIFY `aID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `cakes`
--
ALTER TABLE `cakes`
  MODIFY `cID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `cakes_color`
--
ALTER TABLE `cakes_color`
  MODIFY `ccID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `cakes_flavor`
--
ALTER TABLE `cakes_flavor`
  MODIFY `cfID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `cakes_layer`
--
ALTER TABLE `cakes_layer`
  MODIFY `clID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cakes_size`
--
ALTER TABLE `cakes_size`
  MODIFY `csID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `nID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications_enum`
--
ALTER TABLE `notifications_enum`
  MODIFY `neOpID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `oID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `tID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `vID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
