-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2014 at 07:16 PM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `seatec`
--

-- --------------------------------------------------------

--
-- Table structure for table `speedtest`
--

CREATE TABLE IF NOT EXISTS `speedtest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '1',
  `ip` int(11) NOT NULL DEFAULT '0',
  `ip_string` varchar(15) NOT NULL DEFAULT '',
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `downspeed` varchar(15) NOT NULL DEFAULT '',
  `upspeed` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81 ;

--
-- Dumping data for table `access_log`
--

INSERT INTO `speedtest` (`id`, `user_id`, `ip`, `ip_string`, `timestamp`, `downspeed`, `upspeed`) VALUES

-- --------------------------------------------------------

--
-- Table structure for table `cameras`
--

CREATE TABLE IF NOT EXISTS `cameras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `wan_ip` varchar(500) NOT NULL,
  `port` int(11) NOT NULL,
  `lan_ip` int(11) NOT NULL,  
  `description` varchar(50) NOT NULL,
  `cam_user` varchar(50) NOT NULL,
  `cam_pwd` varchar(50) NOT NULL,  
  `cam_num` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `session_store`
--

CREATE TABLE IF NOT EXISTS `session_store` (
  `sessionId` int(11) NOT NULL,
  `sessionUserId` int(11) NOT NULL,
  KEY `sessionId` (`sessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `user_id`, `description`) VALUES
(1, 1, 'Site1'),
(2, 1, 'Site2'),
(3, 1, 'Site3'),
(4, 2, 'JakesSite1'),
(5, 2, 'JakesSite2'),
(6, 2, 'JakesSite3');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `lastUpdated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `firstname`, `lastname`, `activated`, `lastUpdated`) VALUES
(1, 'mcaldwell@seatecsecurity.com', 'matt', 1, 'Matt', 'Caldwell', 1, '2014-03-02 05:00:50'),
(2, 'jarney@seatecsecurity.com', 'jake', 1, 'Jake', 'Arney', 1, '2014-03-02 05:03:03'),

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE IF NOT EXISTS `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `paramater` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
