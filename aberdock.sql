-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 05, 2023 at 09:59 PM
-- Server version: 10.11.2-MariaDB-1:10.11.2+maria~ubu2204
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aberdock`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_index` int(11) NOT NULL,
  `category_subof` int(11) NOT NULL,
  `category_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_index`, `category_subof`, `category_name`) VALUES
(1, 0, 'Test parent category'),
(2, 0, 'Test second parent category'),
(3, 1, 'Test child category'),
(4, 1, 'Another child'),
(5, 2, 'Second child');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `config_id` int(11) NOT NULL,
  `config_name` text NOT NULL,
  `config_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`config_id`, `config_name`, `config_value`) VALUES
(1, 'database_version', '1.2'),
(2, 'login_required', '1'),
(3, 'registration_enabled', '1'),
(4, 'api_enabled', '0'),
(5, 'default_language', '1'),
(6, 'default_theme', 'default'),
(7, 'registration_req_invite', '0'),
(8, 'announcement_interval', '300'),
(9, 'announcement_url', 'http://127.0.0.1'),
(10, 'announcement_allow_guest', '0');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `gid` int(11) NOT NULL,
  `group_name` text NOT NULL,
  `group_color` text NOT NULL,
  `is_admin` int(11) NOT NULL,
  `is_guest` int(11) NOT NULL,
  `is_new` int(11) NOT NULL,
  `is_disabled` int(11) NOT NULL,
  `can_upload` int(11) NOT NULL,
  `can_download` int(11) NOT NULL,
  `can_delete` int(11) NOT NULL,
  `can_modify` int(11) NOT NULL,
  `can_viewprofile` int(11) NOT NULL,
  `can_viewstats` int(11) NOT NULL,
  `can_comment` int(11) NOT NULL,
  `can_invite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`gid`, `group_name`, `group_color`, `is_admin`, `is_guest`, `is_new`, `is_disabled`, `can_upload`, `can_download`, `can_delete`, `can_modify`, `can_viewprofile`, `can_viewstats`, `can_comment`, `can_invite`) VALUES
(1, 'Administrator', 'ffffff', 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `lid` int(11) NOT NULL,
  `language_short` text NOT NULL,
  `language_long` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`lid`, `language_short`, `language_long`) VALUES
(1, 'eng', 'English'),
(2, 'cym', 'Cymraeg');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `sid` int(11) NOT NULL,
  `session_token` text NOT NULL,
  `uid` int(11) NOT NULL,
  `last_seen` bigint(20) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  `remember` int(11) NOT NULL,
  `agent` text NOT NULL,
  `ip_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `pid` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `recovery_key` text NOT NULL,
  `last_seen` bigint(20) NOT NULL,
  `lid` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  `show_downloads` int(11) NOT NULL,
  `show_uploads` int(11) NOT NULL,
  `uploaded` bigint(20) NOT NULL,
  `downloaded` bigint(20) NOT NULL,
  `picture` text NOT NULL,
  `uid_long` text NOT NULL,
  `join_date` bigint(20) NOT NULL,
  `banned_reason` text NOT NULL,
  `invited_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `gid`, `pid`, `username`, `password`, `recovery_key`, `last_seen`, `lid`, `private`, `show_downloads`, `show_uploads`, `uploaded`, `downloaded`, `picture`, `uid_long`, `join_date`, `banned_reason`, `invited_by`) VALUES
(1, 1, '', 'testAccount', '$2y$12$OyQ5l6We54nWsKnPCbFll.namniojNKD14EZAF4Pagu1YOyCZaNUO', '', 0, 1, 0, 0, 0, 0, 0, '', 'asdjasoiyysaoiudoisaudouoisaudasd', 0, '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_index`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
