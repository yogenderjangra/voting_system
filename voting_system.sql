-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 18, 2025 at 01:19 PM
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
-- Database: `voting_system`
--
CREATE DATABASE IF NOT EXISTS `voting_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `voting_system`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'ram', '221'),
(3, 'rohit', '12341');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `poll` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `photo` varchar(255) DEFAULT NULL,
  `poll_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `position`, `poll`, `status`, `photo`, `poll_id`) VALUES
(1, 'Sandra sharma', 'Year representative (2020)', 'Libs election', 'Active', 'sandra.jpg', 1),
(2, 'Mrinalini', 'Year representative (2019)', 'Libs election', 'Active', 'mrinalini.jpg', 1),
(4, 'Sandra sharma', 'Year representative (2020)', NULL, 'Active', 'cand_687a1d2416df9.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reviewed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `message`, `created_at`, `reviewed`) VALUES
(1, 1, 'Great voting experience!', '2025-07-09 17:25:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `title`, `description`, `start_date`, `end_date`, `active`, `archived`) VALUES
(1, 'Student Council Election 2025', 'Vote for your favorite candidate', '2025-07-07 09:00:00', '2025-07-14 18:00:00', 1, 1),
(2, 'cr', 'class', '2025-07-01 00:00:00', '2025-07-12 00:00:00', 0, 1),
(3, 'class representative', 'cr', '2025-07-18 00:00:00', '2025-07-19 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL,
  `photo` varchar(300) NOT NULL,
  `has_voted` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `middle_name`, `username`, `password`, `department`, `photo`, `has_voted`, `created_at`) VALUES
(6, 'sunita', 'kumari', 'singh', 'sunita121', '$2y$10$ZtnNJb1k2bSeoHg.iyIAVO/RzVziHaPr7fSaayW9l2roCPmf9KSJO', 'btech', 'uploads/686b903395e77_IMG-20250504-WA0009.jpg', 1, '2025-07-11 02:09:54'),
(7, 'rohit', 'sharma', '', 'rohit', '$2y$10$RMXOGYLMfbxC.t5yuU3pRuMIYxE4Za7QUWPcdclHrJsQcB29IW9/W', 'btech', '', 0, '2025-07-11 02:49:41'),
(8, 'lokesh', 'rao', 'rao', 'lokeshh', '$2y$10$TaMvD88sGC7bWTdubzMMm.fotyp85jpRkfnhCCXSlBbufULIEnUSS', 'btech', '', 0, '2025-07-11 03:39:28'),
(9, 'priya', 'sharma', 'priya', 'priya', '$2y$10$R9u2gvDuJtkXSn.ph/MKd.In/5ivdKL7bWXTx3iwTDThUuCBs4cE6', 'btech', '', 0, '2025-07-11 03:47:02'),
(10, 'abcd', 'ab', 'aa', 'aaa', '$2y$10$h5gWgfu/Jn//M.hFH7N9.OPgbQQHgQupNHXpuRo0NEmER/FzfV9MK', 'btech', '', 0, '2025-07-11 03:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `voted_at` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `poll_id`, `candidate_id`, `voted_at`, `created_at`) VALUES
(1, 6, 1, 1, '2025-07-07 14:57:47', '2025-07-11 01:42:19'),
(2, 7, 1, 2, '2025-07-11 02:50:09', '2025-07-11 02:50:09'),
(3, 8, 1, NULL, '2025-07-11 03:43:32', '2025-07-11 03:43:32'),
(4, 9, 1, NULL, '2025-07-11 03:47:33', '2025-07-11 03:47:33'),
(5, 10, 1, 1, '2025-07-11 03:52:24', '2025-07-11 03:52:24'),
(6, 10, 3, 4, '2025-07-18 15:45:03', '2025-07-18 15:45:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
