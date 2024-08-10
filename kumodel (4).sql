-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2024 at 01:36 PM
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
-- Database: `kumodel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(6) UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `reg_date`) VALUES
(1, 'thanaphat.sal@ku.th', '1234', '2024-08-03 14:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `booth_id` int(11) DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `payment_slip_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booths`
--

CREATE TABLE `booths` (
  `booth_id` int(11) NOT NULL,
  `booth_number` int(11) NOT NULL,
  `booth_name` varchar(255) NOT NULL,
  `booth_size` varchar(50) NOT NULL,
  `booth_status` tinyint(4) NOT NULL,
  `booth_price` decimal(10,2) NOT NULL,
  `zone_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booths`
--

INSERT INTO `booths` (`booth_id`, `booth_number`, `booth_name`, `booth_size`, `booth_status`, `booth_price`, `zone_id`) VALUES
(1, 0, 'Booth 1', 'Small', 1, 500.00, 1),
(2, 0, 'Booth 2', 'Medium', 1, 750.00, 1),
(3, 0, 'Booth 3', 'Large', 1, 1000.00, 1),
(4, 0, 'Booth 4', 'Small', 0, 500.00, 2),
(5, 0, 'Booth 5', 'Medium', 0, 750.00, 2),
(6, 0, 'Booth 6', 'Large', 1, 1000.00, 2),
(7, 0, 'Booth 7', 'Small', 1, 500.00, 3),
(8, 0, 'Booth 8', 'Medium', 1, 750.00, 3),
(9, 0, 'Booth 9', 'Large', 0, 1000.00, 3),
(10, 0, 'Booth 10', 'Small', 0, 500.00, 4),
(11, 0, 'Booth 11', 'Medium', 1, 750.00, 4),
(12, 0, 'Booth 12', 'Large', 0, 1000.00, 4),
(13, 0, 'Booth 13', 'Small', 0, 500.00, 5),
(14, 0, 'Booth 14', 'Medium', 0, 750.00, 5),
(15, 0, 'Booth 15', 'Large', 0, 1000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_date`) VALUES
(1, '2024-08-06');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `member_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `booth_number` int(11) NOT NULL,
  `product` varchar(255) NOT NULL,
  `payment_proof` varchar(255) NOT NULL,
  `member_id` int(11) NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `member_id` varchar(30) NOT NULL,
  `prefix` varchar(10) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `booked_booths` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `member_id`, `prefix`, `firstname`, `lastname`, `phone`, `email`, `password`, `reg_date`, `booked_booths`) VALUES
(1, '666', 'นาย', 'Oya', 'Nolan', '88888888', 'pop@gmail.com', '123456', '2024-08-03 15:10:06', 0),
(3, '1000', 'นาย', 'กิตติพงศ์', 'เวียงวิเศษ', '0969426931', 'kittipong.wai@ku.th', '$2y$10$RUu4tTgQp0aOvW0jTyVL2uCS9eRpe34BG158KWKH7UaWKwL8QGiE2', '2024-08-08 08:30:51', 0),
(4, '9296', 'นาย', 'เกียรติพงศ์', 'ประกอบคำ', '0123456789', 'aogbank2@gmail.com', '$2y$10$RUu4tTgQp0aOvW0jTyVL2uCS9eRpe34BG158KWKH7UaWKwL8QGiE2', '2024-08-08 07:43:44', 0),
(5, '2377', 'นาง', 'ิbababo', 'boboba', '1519541855', 'aogbank4652@gmail.com', '$2y$10$7PLeBmpCGRqmDQvDOuf8EO8dLwBBOJ6HvfM9DF8aVn0L2m3cRvGcG', '2024-08-08 07:46:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `zone_id` int(11) NOT NULL,
  `zone_name` varchar(255) NOT NULL,
  `zone_info` text NOT NULL,
  `booth_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`zone_id`, `zone_name`, `zone_info`, `booth_count`) VALUES
(1, 'Zone A', 'This is Zone A. It contains various booths for different purposes.', 3),
(2, 'Zone B', 'This is Zone B. It features booths for exhibitions and sales.', 3),
(3, 'Zone C', 'This is Zone C. It includes booths for workshops and presentations.', 3),
(4, 'Zone D', 'This is Zone D. It is designated for food and beverage stalls.', 3),
(5, 'Zone E', 'This is Zone E. It has booths for art and crafts.', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `booths`
--
ALTER TABLE `booths`
  ADD PRIMARY KEY (`booth_id`),
  ADD KEY `zone_id` (`zone_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`zone_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booths`
--
ALTER TABLE `booths`
  MODIFY `booth_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booths`
--
ALTER TABLE `booths`
  ADD CONSTRAINT `booths_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`zone_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
