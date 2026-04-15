-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2016 at 09:51 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `sms_addressbooks`
--

CREATE TABLE IF NOT EXISTS `sms_addressbooks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(300) NOT NULL,
  `sms` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_addressbook_contacts`
--

CREATE TABLE IF NOT EXISTS `sms_addressbook_contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT 'No Name',
  `phone` varchar(20) NOT NULL,
  `addressbooks_id` int(11) NOT NULL,
  `birthday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_channel`
--

CREATE TABLE IF NOT EXISTS `sms_channel` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `operator_id` tinyint(4) NOT NULL,
  `url` text NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `port` int(10) NOT NULL,
  `created_by` tinyint(4) NOT NULL,
  `created` date NOT NULL,
  `updated_by` tinyint(4) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('PENDING','ACTIVE','INACTIVE') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_countrylist`
--

CREATE TABLE IF NOT EXISTS `sms_countrylist` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_draftmessages`
--

CREATE TABLE IF NOT EXISTS `sms_draftmessages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT 'Untitled',
  `message` text NOT NULL,
  `recipient` text NOT NULL,
  `is_unicode` int(11) NOT NULL DEFAULT '0',
  `is_mms` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_group`
--

CREATE TABLE IF NOT EXISTS `sms_group` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `created_by` tinyint(4) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_group_members`
--

CREATE TABLE IF NOT EXISTS `sms_group_members` (
  `id` int(11) NOT NULL,
  `group_id` tinyint(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_menu`
--

CREATE TABLE IF NOT EXISTS `sms_menu` (
  `id` int(10) unsigned NOT NULL,
  `parent_id` int(11) NOT NULL,
  `user_group_id` varchar(40) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `module_link` varchar(100) NOT NULL,
  `order` tinyint(4) NOT NULL,
  `status` enum('PUBLISH','UNPUBLISH') NOT NULL,
  `icon` varchar(50) NOT NULL,
  `icon_color` varchar(20) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sms_menu`
--

INSERT INTO `sms_menu` (`id`, `parent_id`, `user_group_id`, `title`, `module_link`, `order`, `status`, `icon`, `icon_color`, `create_date`) VALUES
(8, 0, '1', 'Menu', 'menu', 4, 'PUBLISH', 'fa-tasks', '#2056ae', '2016-01-18 18:46:12'),
(9, 0, '1,5,6', 'Home', 'home', 2, 'PUBLISH', 'fa-home', '#0a83a1', '2016-01-18 18:46:16'),
(11, 8, '1', 'Add Menu', 'menu/add', 4, 'PUBLISH', 'fa-plus-square', '#1a8f0a', '2016-01-05 16:50:33'),
(12, 8, '1', 'Menu List', 'menu/index', 2, 'PUBLISH', 'fa-list-alt', '#e8ca10', '2016-01-05 16:50:33'),
(73, 8, '1', 'Menu Permission', 'menu/menu_permission', 6, 'PUBLISH', 'fa-cog', '#0a8699', '2016-01-05 16:50:33'),
(194, 0, '1,5,6', 'User Manager', 'user', 5, 'PUBLISH', 'fa-user', '0', '2015-12-19 12:45:50'),
(195, 194, '1,5,6', 'User List', 'user/index', 1, 'PUBLISH', 'fa-user', '#1aafae', '2015-12-04 18:44:18'),
(196, 194, '1,5,6', 'New User', 'user/add', 2, 'PUBLISH', 'fa-plus-square', '#0faaa0', '2015-12-04 18:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `sms_operator`
--

CREATE TABLE IF NOT EXISTS `sms_operator` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `country_id` tinyint(4) NOT NULL,
  `created_by` tinyint(4) NOT NULL,
  `created` date NOT NULL,
  `updated_by` tinyint(4) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('PENDING','ACTIVE','INACTIVE') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_scheduledmessages`
--

CREATE TABLE IF NOT EXISTS `sms_scheduledmessages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `senderID` varchar(15) NOT NULL,
  `recipient` text NOT NULL,
  `date` datetime NOT NULL,
  `pages` int(11) NOT NULL,
  `is_unicode` int(11) NOT NULL DEFAULT '0',
  `is_mms` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `error` varchar(300) NOT NULL DEFAULT '',
  `scheduleDate` datetime NOT NULL,
  `IP` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_scheduledmessages`
--

INSERT INTO `sms_scheduledmessages` (`id`, `user_id`, `message`, `senderID`, `recipient`, `date`, `pages`, `is_unicode`, `is_mms`, `status`, `error`, `scheduleDate`, `IP`) VALUES
(1, 0, 'kjbkjgjhg hfjhfjhvfhj', '898jihk', '9979797,9899,2348080', '2016-09-21 22:27:40', 1, 0, 0, 'Pending', '', '2016-09-21 22:26:00', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `sms_senderid`
--

CREATE TABLE IF NOT EXISTS `sms_senderid` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `senderID` varchar(20) NOT NULL,
  `status` enum('Not_Approve','Approved') NOT NULL DEFAULT 'Not_Approve'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_sentmessages`
--

CREATE TABLE IF NOT EXISTS `sms_sentmessages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `senderID` varchar(15) NOT NULL,
  `recipient` text NOT NULL,
  `date` datetime NOT NULL,
  `pages` int(11) NOT NULL,
  `status` enum('Sending','Sent','Not_sent') NOT NULL DEFAULT 'Sending',
  `units` decimal(11,2) NOT NULL,
  `sentFrom` varchar(50) NOT NULL DEFAULT 'Panel',
  `is_mms` int(11) NOT NULL DEFAULT '0',
  `is_unicode` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(20) NOT NULL DEFAULT 'Unknown',
  `gateway_id` varchar(500) NOT NULL DEFAULT '',
  `error` varchar(100) NOT NULL DEFAULT 'Message Status Not Available'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_sentmessages`
--

INSERT INTO `sms_sentmessages` (`id`, `user_id`, `message`, `senderID`, `recipient`, `date`, `pages`, `status`, `units`, `sentFrom`, `is_mms`, `is_unicode`, `IP`, `gateway_id`, `error`) VALUES
(1, 0, 'kjgjhfhhj', '898jihk', '7677', '2016-09-21 22:28:01', 1, '', '0.00', 'Portal', 0, 0, '::1', '', 'Insufficient Balance'),
(2, 0, 'hvjhfjhgkjgkj', '898jihk', '33245425,987987987,23480808', '2016-09-21 22:37:00', 1, '', '0.00', 'Portal', 0, 0, '::1', '', 'Insufficient Balance');

-- --------------------------------------------------------

--
-- Table structure for table `sms_smsprice`
--

CREATE TABLE IF NOT EXISTS `sms_smsprice` (
  `id` int(11) NOT NULL,
  `minValue` decimal(11,2) NOT NULL,
  `maxValue` decimal(11,2) NOT NULL,
  `cost` decimal(11,4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_smsprice`
--

INSERT INTO `sms_smsprice` (`id`, `minValue`, `maxValue`, `cost`) VALUES
(1, '0.00', '1000.00', '1.0000'),
(2, '1001.00', '5000.00', '1.0000'),
(3, '5001.00', '10000.00', '1.0000');

-- --------------------------------------------------------

--
-- Table structure for table `sms_user`
--

CREATE TABLE IF NOT EXISTS `sms_user` (
  `id` int(11) NOT NULL,
  `id_user_group` int(2) unsigned NOT NULL,
  `username` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `passwd` varchar(70) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `last_login_time` datetime NOT NULL,
  `status` enum('ACTIVE','INACTIVE','PENDING') NOT NULL,
  `random_number` varchar(20) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `currency_code` int(11) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `allow_2_way` enum('NO','YES') DEFAULT NULL,
  `phone_verified` varchar(50) DEFAULT NULL,
  `email_verified` varchar(50) DEFAULT NULL,
  `verification_code` varchar(100) DEFAULT NULL,
  `order_limit` int(3) DEFAULT NULL,
  `credit_limit` double DEFAULT NULL,
  `gateway` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sms_user`
--

INSERT INTO `sms_user` (`id`, `id_user_group`, `username`, `name`, `passwd`, `address`, `mobile`, `email`, `create_date`, `last_login_time`, `status`, `random_number`, `photo`, `country_id`, `currency_code`, `timezone`, `allow_2_way`, `phone_verified`, `email_verified`, `verification_code`, `order_limit`, `credit_limit`, `gateway`) VALUES
(1, 1, 'admin', 'mukul', '21232f297a57a5a743894a0e4a801fc3', 'Demo Address', '+8801122334455', 'admin@ntech-solution.com', '2016-05-04 00:07:28', '2016-09-26 01:47:06', 'ACTIVE', '14702143894073', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'mukul', NULL, 'b107e9a7ba2cd6d9e878c1a1c277554c', 'nnnn', '01734183130', 'engr.mukul@hotmail.com', '0000-00-00 00:00:00', '2016-09-26 01:46:50', 'ACTIVE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_user_access_record`
--

CREATE TABLE IF NOT EXISTS `sms_user_access_record` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `login_time` varchar(20) NOT NULL,
  `logout_time` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `access_ip` varchar(20) NOT NULL,
  `random_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_user_group`
--

CREATE TABLE IF NOT EXISTS `sms_user_group` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sms_user_group`
--

INSERT INTO `sms_user_group` (`id`, `title`, `comment`, `status`) VALUES
(1, 'Superadmin', 'access over every options', 'Active'),
(2, 'Reseller', 'Reseller', 'Active'),
(3, 'Customer', 'customer', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `sms_wallet`
--

CREATE TABLE IF NOT EXISTS `sms_wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sms_purches` int(11) NOT NULL,
  `sms_balance` int(11) NOT NULL,
  `sms_borrowed` int(11) NOT NULL,
  `sms_success_sent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_addressbooks`
--
ALTER TABLE `sms_addressbooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_addressbook_contacts`
--
ALTER TABLE `sms_addressbook_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_channel`
--
ALTER TABLE `sms_channel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_countrylist`
--
ALTER TABLE `sms_countrylist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_draftmessages`
--
ALTER TABLE `sms_draftmessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_group`
--
ALTER TABLE `sms_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_group_members`
--
ALTER TABLE `sms_group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_menu`
--
ALTER TABLE `sms_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_operator`
--
ALTER TABLE `sms_operator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_scheduledmessages`
--
ALTER TABLE `sms_scheduledmessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_senderid`
--
ALTER TABLE `sms_senderid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_sentmessages`
--
ALTER TABLE `sms_sentmessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_smsprice`
--
ALTER TABLE `sms_smsprice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_user`
--
ALTER TABLE `sms_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_user_access_record`
--
ALTER TABLE `sms_user_access_record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_user_group`
--
ALTER TABLE `sms_user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_wallet`
--
ALTER TABLE `sms_wallet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_addressbooks`
--
ALTER TABLE `sms_addressbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_addressbook_contacts`
--
ALTER TABLE `sms_addressbook_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_channel`
--
ALTER TABLE `sms_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_countrylist`
--
ALTER TABLE `sms_countrylist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_draftmessages`
--
ALTER TABLE `sms_draftmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_group`
--
ALTER TABLE `sms_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_group_members`
--
ALTER TABLE `sms_group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_menu`
--
ALTER TABLE `sms_menu`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=234;
--
-- AUTO_INCREMENT for table `sms_operator`
--
ALTER TABLE `sms_operator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_scheduledmessages`
--
ALTER TABLE `sms_scheduledmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sms_senderid`
--
ALTER TABLE `sms_senderid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_sentmessages`
--
ALTER TABLE `sms_sentmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sms_smsprice`
--
ALTER TABLE `sms_smsprice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sms_user`
--
ALTER TABLE `sms_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sms_user_access_record`
--
ALTER TABLE `sms_user_access_record`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_user_group`
--
ALTER TABLE `sms_user_group`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sms_wallet`
--
ALTER TABLE `sms_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
