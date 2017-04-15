-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2016 at 08:57 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_timesheet`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_access_right_details`
--

CREATE TABLE IF NOT EXISTS `tbl_access_right_details` (
`id` int(4) NOT NULL,
  `parent_id` tinyint(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(150) NOT NULL,
  `page_url` varchar(200) DEFAULT NULL,
  `menu_order` int(4) NOT NULL DEFAULT '0',
  `menu_icon` varchar(200) DEFAULT NULL,
  `is_disabled` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_access_right_master`
--

CREATE TABLE IF NOT EXISTS `tbl_access_right_master` (
`id` tinyint(3) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'access type = "admin / employee "',
  `heading` varchar(100) NOT NULL,
  `page_url` varchar(200) DEFAULT NULL,
  `heading_order` tinyint(4) DEFAULT '0',
  `menu_icon` varchar(100) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_access_right_details`
--
ALTER TABLE `tbl_access_right_details`
 ADD PRIMARY KEY (`id`), ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tbl_access_right_master`
--
ALTER TABLE `tbl_access_right_master`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_access_right_details`
--
ALTER TABLE `tbl_access_right_details`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_access_right_master`
--
ALTER TABLE `tbl_access_right_master`
MODIFY `id` tinyint(3) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
