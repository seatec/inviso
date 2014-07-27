-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2014 at 08:01 PM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inviso`
--

-- --------------------------------------------------------

--
-- Table structure for table `cameras`
--

CREATE TABLE IF NOT EXISTS `cameras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `wan_ip` varchar(250) NOT NULL,
  `port` int(11) NOT NULL,
  `lan_ip` varchar(250) NOT NULL,
  `description` varchar(50) NOT NULL,
  `cam_user` varchar(50) NOT NULL,
  `cam_pwd` varchar(50) NOT NULL,
  `cam_num` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cameras`
--

INSERT INTO `cameras` (`id`, `user_id`, `site_id`, `wan_ip`, `port`, `lan_ip`, `description`, `cam_user`, `cam_pwd`, `cam_num`, `stream_id`, `active`, `lastUpdated`) VALUES
(1, 1, 1, '50.133.34.33', 9191, '10.1.1.11', 'Pelco PTZ', 'invisoadmin', 'inviso', 1, 1, 1, '2014-05-31 23:57:10');

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
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `user_id`, `description`, `active`, `lastUpdated`) VALUES
(1, 1, 'Site1', 1, '0000-00-00 00:00:00'),
(2, 1, 'Site2', 1, '0000-00-00 00:00:00'),
(3, 1, 'Site3', 1, '0000-00-00 00:00:00'),
(4, 2, 'JakesSite1', 1, '0000-00-00 00:00:00'),
(5, 2, 'JakesSite2', 1, '0000-00-00 00:00:00'),
(6, 2, 'JakesSite3', 1, '0000-00-00 00:00:00');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `lastUpdated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `firstname`, `lastname`, `active`, `lastUpdated`) VALUES
(1, 'mcaldwell@seatecsecurity.com', 'mcaldwell', 1, 'Matt', 'Caldwell', 1, '2014-05-31 04:00:00');

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
