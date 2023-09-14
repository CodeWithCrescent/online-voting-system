-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2023 at 01:57 PM
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
-- Database: `evoting_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `candidate_year` int(11) NOT NULL,
  `candidate_photo` varchar(255) DEFAULT NULL,
  `fellow_candidate_name` varchar(255) DEFAULT NULL,
  `fellow_candidate_year` int(11) DEFAULT NULL,
  `fellow_candidate_photo` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `election_id`, `category_id`, `name`, `candidate_year`, `candidate_photo`, `fellow_candidate_name`, `fellow_candidate_year`, `fellow_candidate_photo`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(54, 19, 49, 'Juma Mgunda', 2, '', 'Samia Suluhu', 3, '', 1, NULL, '2023-08-31 21:46:50', NULL),
(55, 19, 49, 'John Boko', 2, '', 'Anna Amani', 2, '', 1, NULL, '2023-08-31 21:47:02', NULL),
(56, 19, 49, 'Mzava Madam', 3, '', 'Sara Joseph', 2, '', 1, NULL, '2023-08-31 21:47:13', NULL),
(57, 19, 50, 'ALLY Hassan Mwinyi', 1, '', '', 0, '', 1, NULL, '2023-08-31 21:47:21', NULL),
(58, 19, 50, 'Allan Mweusi', 3, '', '', 0, '', 1, NULL, '2023-08-31 21:47:37', NULL),
(59, 19, 50, 'Juma Mgunda', 1, '', '', 0, '', 1, NULL, '2023-08-31 21:47:49', NULL),
(60, 19, 50, 'Jakaya M. Kikwete', 1, '', '', 0, '', 1, NULL, '2023-08-31 21:48:03', NULL),
(61, 19, 51, 'Juma Mgunda12', 3, '', '', 0, '', 1, NULL, '2023-08-31 21:48:14', NULL),
(62, 19, 51, 'Allan Mweusie', 3, '', '', 0, '', 1, NULL, '2023-08-31 21:48:24', NULL),
(63, 19, 51, 'Elizabeth Kabisu', 3, '', '', 0, '', 1, NULL, '2023-08-31 21:48:36', NULL),
(66, 19, 52, 'Radhia Ramadhan', 3, '', 'Anna Amani', 2, '', 1, NULL, '2023-08-31 21:49:43', NULL),
(67, 19, 52, 'Halima Mdee', 2, '', 'Hadija John', 3, '', 1, NULL, '2023-08-31 21:49:57', NULL),
(68, 20, 53, 'Allan Mweusi', 2, '', '', 0, '', 1, NULL, '2023-09-03 21:10:51', NULL),
(69, 20, 53, 'Che Malone', 3, '', '', 0, '', 1, NULL, '2023-09-03 21:11:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `election_id`, `name`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(49, 19, 'President', 1, NULL, '2023-08-31 20:39:38', NULL),
(50, 19, 'Secretary', 1, NULL, '2023-08-31 21:32:18', NULL),
(51, 19, 'Accountant', 1, NULL, '2023-08-31 21:32:31', NULL),
(52, 19, 'Project Manager', 1, NULL, '2023-08-31 21:42:31', NULL),
(53, 20, 'Test Category', 1, NULL, '2023-09-03 21:10:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `election`
--

CREATE TABLE `election` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `voters` varchar(255) NOT NULL,
  `starttime` timestamp NULL DEFAULT NULL,
  `endtime` timestamp NULL DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 - Is not active, 1 - Is Active',
  `can_vote` tinyint(4) DEFAULT NULL,
  `report_path` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `election`
--

INSERT INTO `election` (`id`, `title`, `year`, `voters`, `starttime`, `endtime`, `description`, `status`, `can_vote`, `report_path`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(19, 'GROUP 4 ELECTION', '2023', 'Students', '2023-08-29 21:00:00', '2023-10-02 21:00:00', 'hello test election', 1, 1, NULL, 1, 1, '2023-08-30 08:40:49', '2023-09-11 09:38:02'),
(20, 'TEST ELECTION', '2023', 'Students', '2023-09-02 21:00:00', '2023-09-24 21:00:00', 'This is test election', 0, 0, NULL, 1, 1, '2023-09-03 18:44:29', '2023-09-04 13:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `profile_picture`, `password`, `type`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Crescent Geniius', 'Crescent ', 'crescentbeatz31@gmail.com', '', 'Crescent -169261275364e33891af7f1.jpg', '$2y$10$INmvRoIZb9WkomzQ5cFJguTVeWx8nfjiND5y38kNWSoORhbz6Y.N6', 0, NULL, '2023-08-21 07:08:16', '2023-09-09 12:17:12'),
(2, 'Nunda Tz', 'Nunda', 'nundatz@yahoo.com', '0752525252', 'Nunda-169260218764e30f4bb71c1.jpg', '$2y$10$Xs6lvo6VUZP7HDPD14naWOwLWTwYEFCmB9q.F3FMwYOPcpb.M6Uca', 1, 1, '2023-08-21 07:08:35', '2023-08-24 07:16:54'),
(3, 'Enzo Fernandez', 'EnzoFd', 'EnzojFrednandez@gmail.com', '', 'EnzoFd-169260217964e30f434ae20.jpg', '$2y$10$0B.boPdQ4BNgKyQ6WatB0Otva7.p5miOgJJOrQHv9l9KX6Ozc42ly', 1, 1, '2023-08-21 07:09:00', '2023-08-21 08:21:47'),
(4, 'Stazeal', 'peacestazeal@gmail.com', 'peacestazeal@gmail.com', '0748466540', 'peacestazeal@gmail.com-169260197564e30e77e07da.jpg', '$2y$10$EyabL57d0JmhClICH.S7bea63bT5SdkLjpqVLDyOntrH9TJdgzsMy', 1, 7, '2023-08-21 07:09:28', '2023-08-21 08:18:46'),
(5, 'LIMBU MAZIBA', 'MAZIBA', 'mazibajr@gmail.com', '0764006410', 'MAZIBA-169260215364e30f299ff28.jpg', '$2y$10$ezFgq8O9oEdwgeDkZtbjnOvJDLthZA3ik./VTl7EAYJXfG9rMmSf6', 1, 7, '2023-08-21 07:09:43', '2023-08-21 08:18:59'),
(6, 'Max Peter', 'Max', 'naymax2000@gmail.com', '', 'Max-169260299964e3127762a45.jpg', '$2y$10$4BEoby.WdYO/8wxnYhKtn.dWo.2VcxW3KmEMpS94HUDVlxPfDF4YG', 1, 1, '2023-08-21 07:09:53', '2023-08-21 08:21:43'),
(7, 'Kulwa Leonard ', 'kulwa', 'kulwa@gmail.com', '0613433000', 'kulwa-169260478064e3196c6c121.jpg', '$2y$10$r4sU2FVCBfSI1zGumuiB7ui0Wslx8f6K9rUPTw.wXMEfDPwdoAsjS', 1, 1, '2023-08-21 07:57:10', '2023-08-21 08:21:38'),
(8, 'Anna', 'anna@gmail.com', NULL, NULL, NULL, '$2y$10$S.bls5n5AZtIL7Yvnt6jz.Npm8ldbh8TKlc/5EgSBN4PdHDTs7wOO', 1, NULL, '2023-08-21 08:31:05', NULL),
(9, 'Feele', 'feelee@gmail.com', NULL, NULL, NULL, '$2y$10$6O4aTwA8NQMc39zEzHTKeeySfTNc.mw/jvTxGG5QKF1tQ4.vl66Oe', 1, NULL, '2023-08-21 08:33:10', NULL),
(10, 'Samson Delila', 'samson', NULL, NULL, NULL, '$2y$10$AtDNPbsdt0QcGJVrp2tWiugl.d0.V5ZalfoNdaBzGKhGa25u62eyy', 1, NULL, '2023-08-21 10:06:21', NULL),
(11, 'Google Help', 'google', NULL, NULL, NULL, '$2y$10$IFJhECynJcgDkyjcJW1OPO0JF3RYOFLQmrbzZjxdYJUXsNHQRRixu', 1, NULL, '2023-08-21 10:08:41', NULL),
(12, 'Nyasilu', 'amos', NULL, NULL, NULL, '$2y$10$WfP3Ul5MPKd.5RPXD99L3OO7dVJFTpJO7PfHyZvhrl/w0MryUOEBK', 1, 1, '2023-08-23 12:31:26', '2023-08-23 13:06:35'),
(13, 'Mnyama Joseph', '20100534050044', NULL, NULL, NULL, '$2y$10$FXAl.tguuY/0aQMcLOEBO.bemGo9dCTlEZEa6w8BbQ5zy2nibC05G', 1, NULL, '2023-08-23 20:31:36', NULL),
(14, 'Kulwa Juma', '20100', NULL, NULL, NULL, '$2y$10$OFKjONBVlxvFwhShE0JN0OJ6Y40xHaHRBamYUyJP1bIaVsvAfEME6', 1, NULL, '2023-08-24 07:23:30', NULL),
(15, 'Stazeal', '221011', NULL, NULL, NULL, '$2y$10$7hENXLIyQlAaTDWp8ojT4uLhliRAAoL2uwTer9lxq7X5wVT9qNT4G', 1, NULL, '2023-08-24 08:02:49', NULL),
(16, 'Johy', '22210', 'johy@gmail.com', '098900-', '22210-169286448464e70fe4bec2a.jpg', '$2y$10$x6eAuqoLJOtzLnkmah57t.f5Tt0KqLT8u6Tit22VeVIdS7lvaPJra', 1, NULL, '2023-08-24 08:04:05', '2023-08-24 08:08:04'),
(17, 'MANDONGA MTU KAZI', '211044', NULL, NULL, NULL, '$2y$10$i.D6ZeOz5BpYbu4Jgrgcde0dPMsj3lvFBNuRQuVXF000yd4dSuAlC', 1, NULL, '2023-08-24 08:27:20', NULL),
(18, 'Naseeb Abdul', '20100534050001', '', '', '20100534050001-169377533464f4f5e6d1bf4.jpg', '$2y$10$Nmh5i3qDoONn5QgpmOAnx.X23MfODyJ7jkuNs85fNtMZFGnED5F1K', 1, NULL, '2023-09-03 21:07:47', '2023-09-03 21:09:08'),
(19, 'Irene Gasper', '20100534050022', NULL, NULL, NULL, '$2y$10$upj6zTaBFZXb5C93fapy/.3Hp/ZWTV91l1zaPqSkSpAnEWq9zMCHG', 1, NULL, '2023-09-11 09:56:18', NULL),
(20, 'Halima Shabani', 'halima', NULL, NULL, NULL, '$2y$10$rCc.BTOBSI/rDWGxK1wEcOMBfWGOKV.O5pMLv5jdi8bbsySMrFlZe', 1, NULL, '2023-09-12 13:18:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `election_id`, `category_id`, `candidate_id`, `voter_id`, `voted_at`) VALUES
(39, 19, 49, 54, 15, '2023-09-01 07:41:59'),
(40, 19, 52, 66, 4, '2023-09-01 07:41:59'),
(41, 19, 50, 60, 4, '2023-09-01 07:41:59'),
(42, 19, 51, 62, 10, '2023-09-01 07:41:59'),
(44, 19, 49, 54, 10, '2023-09-03 18:10:01'),
(45, 19, 52, 66, 13, '2023-09-03 18:10:01'),
(46, 19, 50, 60, 13, '2023-09-03 18:10:01'),
(48, 19, 49, 54, 17, '2023-09-03 18:10:27'),
(49, 19, 52, 66, 17, '2023-09-03 18:10:50'),
(50, 19, 50, 60, 5, '2023-09-03 18:11:01'),
(52, 19, 50, 60, 16, '2023-09-03 18:11:08'),
(54, 19, 51, 62, 14, '2023-09-03 18:11:43'),
(55, 19, 49, 54, 11, '2023-09-03 18:12:07'),
(56, 19, 52, 66, 11, '2023-09-03 18:12:07'),
(57, 19, 50, 60, 3, '2023-09-03 18:12:07'),
(58, 19, 51, 62, 9, '2023-09-03 18:12:07'),
(59, 19, 49, 56, 16, '2023-09-03 18:13:38'),
(60, 19, 52, 67, 6, '2023-09-03 18:13:38'),
(61, 19, 50, 59, 7, '2023-09-03 18:13:38'),
(62, 19, 51, 63, 3, '2023-09-03 18:13:38'),
(63, 19, 49, 56, 8, '2023-09-03 18:13:44'),
(64, 19, 52, 67, 6, '2023-09-03 18:13:44'),
(65, 19, 50, 59, 15, '2023-09-03 18:13:44'),
(66, 19, 51, 63, 1, '2023-09-03 18:13:44'),
(67, 19, 49, 54, 11, '2023-09-03 18:15:25'),
(68, 19, 52, 67, 15, '2023-09-03 18:15:25'),
(69, 19, 50, 58, 5, '2023-09-03 18:15:25'),
(70, 19, 50, 57, 14, '2023-09-03 18:15:25'),
(71, 19, 51, 63, 12, '2023-09-03 18:15:25'),
(72, 19, 49, 54, 11, '2023-09-03 18:15:32'),
(73, 19, 52, 67, 7, '2023-09-03 18:15:32'),
(74, 19, 50, 58, 10, '2023-09-03 18:15:32'),
(75, 19, 50, 57, 7, '2023-09-03 18:15:32'),
(76, 19, 51, 63, 1, '2023-09-03 18:15:32'),
(77, 19, 49, 54, 12, '2023-09-03 18:15:43'),
(78, 19, 52, 67, 1, '2023-09-03 18:15:43'),
(79, 19, 50, 58, 16, '2023-09-03 18:15:43'),
(80, 19, 51, 63, 1, '2023-09-03 18:15:43'),
(81, 19, 49, 55, 1, '2023-09-03 18:16:36'),
(82, 19, 49, 55, 16, '2023-09-03 18:16:36'),
(83, 19, 52, 67, 1, '2023-09-03 18:16:36'),
(84, 19, 50, 58, 1, '2023-09-03 18:16:36'),
(85, 19, 51, 63, 1, '2023-09-03 18:16:36'),
(86, 19, 49, 55, 1, '2023-09-03 18:17:06'),
(87, 19, 49, 55, 1, '2023-09-03 18:17:06'),
(88, 19, 52, 67, 1, '2023-09-03 18:17:07'),
(89, 19, 50, 58, 1, '2023-09-03 18:17:07'),
(90, 19, 51, 63, 1, '2023-09-03 18:17:07'),
(91, 19, 49, 55, 1, '2023-09-03 18:18:08'),
(92, 19, 49, 55, 1, '2023-09-03 18:18:08'),
(93, 19, 52, 67, 1, '2023-09-03 18:18:08'),
(94, 19, 50, 57, 1, '2023-09-03 18:18:09'),
(95, 19, 51, 61, 1, '2023-09-03 18:18:09'),
(96, 19, 50, 57, 1, '2023-09-03 18:18:09'),
(97, 19, 51, 61, 1, '2023-09-03 18:18:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`) COMMENT 'From Categories table',
  ADD KEY `election_id` (`election_id`) COMMENT 'From Election table',
  ADD KEY `added_by` (`added_by`,`updated_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`,`updated_by`),
  ADD KEY `election_id` (`election_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `election`
--
ALTER TABLE `election`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`,`updated_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `Email` (`email`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `voter_id` (`voter_id`),
  ADD KEY `election_id` (`election_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `election`
--
ALTER TABLE `election`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidates_ibfk_2` FOREIGN KEY (`election_id`) REFERENCES `election` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidates_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `candidates_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `election` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `categories_ibfk_2` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `categories_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `election`
--
ALTER TABLE `election`
  ADD CONSTRAINT `election_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `election_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`voter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `votes_ibfk_4` FOREIGN KEY (`election_id`) REFERENCES `election` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
