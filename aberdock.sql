-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 20, 2023 at 02:59 AM
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
(1, 0, 'Parent 1'),
(2, 0, 'Parent 2'),
(3, 1, 'Child of Parent 1');

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
(1, 'database_version', '1.4'),
(2, 'login_required', '1'),
(3, 'registration_enabled', '1'),
(4, 'api_enabled', '0'),
(5, 'default_language', 'eng'),
(6, 'default_theme', 'default'),
(7, 'registration_req_invite', '1'),
(8, 'announcement_interval', '60'),
(9, 'announcement_url', 'http://127.0.0.1/announce.php'),
(10, 'announcement_allow_guest', '1');

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
  `can_invite` int(11) NOT NULL,
  `can_useapi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`gid`, `group_name`, `group_color`, `is_admin`, `is_guest`, `is_new`, `is_disabled`, `can_upload`, `can_download`, `can_delete`, `can_modify`, `can_viewprofile`, `can_viewstats`, `can_comment`, `can_invite`, `can_useapi`) VALUES
(1, 'Administrator', 'ff00ff', 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'Moderator', 'ffffff', 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(3, 'User', 'ffffff', 0, 0, 1, 0, 1, 1, 0, 0, 1, 1, 1, 1, 0),
(4, 'Guest', 'ffffff', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sid`, `session_token`, `uid`, `last_seen`, `expiration`, `remember`, `agent`, `ip_address`) VALUES
(1, '3487c2790980bf7c7e8feb59a14a78704bf3d64b383315a22cdcc882188bafd6', 1, 1681937597, 1684615997, 1, 'Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/111.0', '11.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `torrents`
--

CREATE TABLE `torrents` (
  `torrent_id` int(11) NOT NULL,
  `torrent_id_long` uuid NOT NULL,
  `uid` int(11) NOT NULL,
  `anonymous` int(11) NOT NULL,
  `category_index` int(11) NOT NULL,
  `info_hash` text NOT NULL,
  `file_name` text NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_size_calc` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `cover` text NOT NULL,
  `upload_time` bigint(20) NOT NULL,
  `published` int(11) NOT NULL,
  `staff_recommended` int(11) NOT NULL,
  `torrent_data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `torrents`
--

INSERT INTO `torrents` (`torrent_id`, `torrent_id_long`, `uid`, `anonymous`, `category_index`, `info_hash`, `file_name`, `file_size`, `file_size_calc`, `title`, `description`, `cover`, `upload_time`, `published`, `staff_recommended`, `torrent_data`) VALUES
(4, '89474814-df1a-11ed-9203-02420b000003', 1, 0, 3, '8aaaf760130bbd1914b2a991b01a3375a988db6a', 'sdasdads', 32768, '100GiB', 'sdasdads', 'asdasdasd', '', 1681954058, 1, 0, 0x64383a616e6e6f756e636533373a68747470733a2f2f746f7272656e74746573742e6c6f63616c2f616e6e6f756e332e706870373a636f6d6d656e7433363a4120746f7272656e7420746f2074657374207468652062656e636f646520636c6173732e31303a6372656174656420627931363a4a61636b205279616e2044617669657331333a6372656174696f6e2064617465693136383139343531343565383a656e636f64696e67353a5554462d38343a696e666f64353a66696c65736c64363a6c656e67746869323065343a706174686c31333a746573744469726563746f727933303a6469726563746f7279496e736964654f66546573744469726563746f7279333a636f77656564363a6c656e67746869313365343a706174686c31333a746573744469726563746f727933303a6469726563746f7279496e736964654f66546573744469726563746f7279353a736861726b656564363a6c656e677468693765343a706174686c31333a746573744469726563746f727932353a66696c65496e736964656f66546573744469726563746f7279656564363a6c656e677468693765343a706174686c31333a746573744469726563746f727932363a66696c65496e736964656f66546573744469726563746f727932656564363a6c656e677468693565343a706174686c393a7465737446696c6531656564363a6c656e677468693565343a706174686c393a7465737446696c6532656565343a6e616d6531313a74657374546f7272656e7431323a7069656365206c656e67746869333237363865363a70696563657332303aa217cec861ba1a183270ef2140354be0e15ef430373a707269766174656931656565);

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
  `uid_long` uuid NOT NULL,
  `join_date` bigint(20) NOT NULL,
  `banned_reason` text NOT NULL,
  `invited_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `gid`, `pid`, `username`, `password`, `recovery_key`, `last_seen`, `lid`, `private`, `show_downloads`, `show_uploads`, `uploaded`, `downloaded`, `picture`, `uid_long`, `join_date`, `banned_reason`, `invited_by`) VALUES
(1, 1, '', 'testAccount', '$2y$12$OyQ5l6We54nWsKnPCbFll.namniojNKD14EZAF4Pagu1YOyCZaNUO', '', 0, 1, 0, 0, 0, 0, 0, '', '3df902aa-df1a-11ed-9203-02420b000003', 0, '', 0);

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
-- Indexes for table `torrents`
--
ALTER TABLE `torrents`
  ADD PRIMARY KEY (`torrent_id`);

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
  MODIFY `category_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `torrents`
--
ALTER TABLE `torrents`
  MODIFY `torrent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
