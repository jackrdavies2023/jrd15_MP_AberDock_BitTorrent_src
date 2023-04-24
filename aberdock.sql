-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 24, 2023 at 01:27 PM
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
(1, 'database_version', '1.6'),
(2, 'login_required', '0'),
(3, 'registration_enabled', '1'),
(4, 'api_enabled', '0'),
(5, 'default_language', 'eng'),
(6, 'default_theme', 'default'),
(7, 'registration_req_invite', '1'),
(8, 'announcement_interval', '300'),
(9, 'announcement_url', 'http://11.0.0.2/announce.php'),
(10, 'announcement_allow_guest', '0');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `download_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `torrent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`download_id`, `uid`, `torrent_id`) VALUES
(2, 1, 21);

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
(1, 'Administrator', '#e01b24', 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'Moderator', '#ff7800', 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(3, 'User', '#33d17a', 0, 0, 1, 0, 1, 1, 0, 0, 1, 1, 1, 1, 0),
(4, 'Guest', '#ffffff', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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
-- Table structure for table `peers`
--

CREATE TABLE `peers` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `torrent_id` int(11) NOT NULL,
  `ip_address` text NOT NULL,
  `port` int(11) NOT NULL,
  `seeding` int(11) NOT NULL,
  `first_seen` bigint(20) NOT NULL,
  `last_seen` bigint(20) NOT NULL,
  `agent` text NOT NULL,
  `uploaded` bigint(20) NOT NULL,
  `downloaded` bigint(20) NOT NULL,
  `remaining` bigint(20) NOT NULL,
  `corrupt` bigint(20) NOT NULL,
  `client_id` text NOT NULL,
  `client_key` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peers`
--

INSERT INTO `peers` (`id`, `uid`, `torrent_id`, `ip_address`, `port`, `seeding`, `first_seen`, `last_seen`, `agent`, `uploaded`, `downloaded`, `remaining`, `corrupt`, `client_id`, `client_key`) VALUES
(5, 1, 21, '11.0.0.1', 64417, 0, 1682333468, 1682342578, 'Deluge/2.1.1 libtorrent/2.0.8.0', 0, 0, 57, 0, '8447b60633f30d61383d14d9538c213600170b525d02185d71b773969284c591', '6241AAAE');

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
(8, '573eade2829120191852f9549e524b6616d4dd10abd3a0f5ee1412a3858545eb', 1, 1682159078, 1682166278, 0, 'Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/111.0', '11.0.0.1'),
(10, '62dca47b0f8a6e4a13f46c7a611a1ae243860fc7926b20002ce51898ef378b15', 1, 1682180110, 1682187310, 0, 'Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/111.0', '11.0.0.1'),
(11, '75e518b83a7ea880c84f2c4599c23ec3576eb31ae4ae645527db10253df1161a', 1, 1682342812, 1682350012, 0, 'Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/111.0', '11.0.0.1');

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
(21, '9f085ef6-df95-11ed-9203-02420b000003', 1, 1, 3, '8aaaf760130bbd1914b2a991b01a3375a988db6a', 'asdasdasdasd', 32768, '32 KiB', 'asdasdasdasd', 'a            print_r($this->getTorrent(infoHash: $infoHash)); exit();', '', 1682084537, 1, 0, 0x64373a636f6d6d656e7433363a4120746f7272656e7420746f2074657374207468652062656e636f646520636c6173732e31303a6372656174656420627931363a4a61636b205279616e2044617669657331333a6372656174696f6e2064617465693136383139343531343565383a656e636f64696e67353a5554462d38343a696e666f64353a66696c65736c64363a6c656e67746869323065343a706174686c31333a746573744469726563746f727933303a6469726563746f7279496e736964654f66546573744469726563746f7279333a636f77656564363a6c656e67746869313365343a706174686c31333a746573744469726563746f727933303a6469726563746f7279496e736964654f66546573744469726563746f7279353a736861726b656564363a6c656e677468693765343a706174686c31333a746573744469726563746f727932353a66696c65496e736964656f66546573744469726563746f7279656564363a6c656e677468693765343a706174686c31333a746573744469726563746f727932363a66696c65496e736964656f66546573744469726563746f727932656564363a6c656e677468693565343a706174686c393a7465737446696c6531656564363a6c656e677468693565343a706174686c393a7465737446696c6532656565343a6e616d6531313a74657374546f7272656e7431323a7069656365206c656e67746869333237363865363a70696563657332303aa217cec861ba1a183270ef2140354be0e15ef430373a707269766174656931656565);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `pid` uuid NOT NULL,
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
(1, 1, '60ac1f0c-df54-11ed-9203-02420b000003', 'testAccount', '$2y$12$OyQ5l6We54nWsKnPCbFll.namniojNKD14EZAF4Pagu1YOyCZaNUO', '', 0, 1, 0, 0, 0, 0, 0, '', '3df902aa-df1a-11ed-9203-02420b000003', 0, '', 0),
(2, 4, '00000000-0000-0000-0000-000000000000', 'Guest', '$2y$12$ke0H4iklRKWYzzQxNNwT2Oac5yLPSs1FBw5pEM6B7ePDUl3CVonXS', '', 0, 1, 1, 0, 0, 0, 0, '', '00000000-0000-0000-0000-000000000000', 0, '', 0);

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
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`download_id`);

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
-- Indexes for table `peers`
--
ALTER TABLE `peers`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `peers`
--
ALTER TABLE `peers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `torrents`
--
ALTER TABLE `torrents`
  MODIFY `torrent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
