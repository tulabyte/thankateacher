-- Adminer 3.4.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(100) NOT NULL,
  `admin_level` enum('REGULAR','SUPER') NOT NULL DEFAULT 'REGULAR',
  `admin_date_created` date NOT NULL,
  `admin_is_disabled` int(1) DEFAULT NULL,
  `admin_last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_password`, `admin_level`, `admin_date_created`, `admin_is_disabled`, `admin_last_login`) VALUES
(1,	'Yemi Adetula',	'yemitula@gmail.com',	'test123',	'SUPER',	'2017-01-08',	NULL,	'2017-04-28 11:07:43'),
(2,	'Tula Admin',	'yemi@tulabyte.net',	'test123',	'REGULAR',	'2017-01-08',	NULL,	'2017-04-28 11:07:01'),
(3,	'Segun Kesington',	'kess@tulabyte.net',	'test123',	'REGULAR',	'2017-01-08',	NULL,	NULL),
(4,	'Comfort Adetula',	'comfort@tulabyte.net',	'test123',	'REGULAR',	'2017-01-17',	NULL,	NULL),
(5,	'Fidelis Ejechi',	'fidelejechi@yahoo.com',	'f1d3@thankateacher',	'SUPER',	'2017-01-19',	NULL,	'2017-03-27 09:25:51'),
(6,	'Opeyemi',	'openimo@gmail.com',	'test123',	'SUPER',	'2017-01-19',	NULL,	'2017-01-19 01:38:46');

DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE `admin_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_admin_id` int(11) NOT NULL,
  `log_admin_name` varchar(100) NOT NULL,
  `log_details` varchar(200) NOT NULL,
  `log_time` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_admin_id` (`log_admin_id`),
  CONSTRAINT `admin_log_ibfk_1` FOREIGN KEY (`log_admin_id`) REFERENCES `admin` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `admin_log` (`log_id`, `log_admin_id`, `log_admin_name`, `log_details`, `log_time`) VALUES
(1,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(2,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(5,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(6,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(7,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(8,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(9,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(10,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(11,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(12,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(13,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(14,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(15,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(16,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(17,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(18,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(19,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(20,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(21,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(22,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(23,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(24,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(25,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(26,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(27,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(28,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(29,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(30,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(31,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(32,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(33,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(34,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(35,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(36,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(37,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(38,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(39,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(40,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(41,	1,	'Yemi Tula',	'Logged Out',	'0000-00-00 00:00:00'),
(42,	1,	'Yemi Tula',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(43,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(44,	1,	'Yemi Tula',	'Created Admin: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(45,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(46,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(47,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(48,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(49,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(50,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(51,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(52,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(53,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(54,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(55,	2,	'Tula Admin',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(56,	2,	'Tula Admin',	'Logged Out',	'0000-00-00 00:00:00'),
(57,	2,	'Tula Admin',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(58,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(59,	2,	'Tula Admin',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(60,	2,	'Tula Admin',	'Accessed Admin Details: Yemi Tula (ID: 1)',	'0000-00-00 00:00:00'),
(61,	2,	'Tula Admin',	'Logged Out',	'0000-00-00 00:00:00'),
(62,	1,	'Yemi Tula',	'Disabled Admin: Tula Admin (2)',	'0000-00-00 00:00:00'),
(63,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(64,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(65,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(66,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(67,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(68,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(69,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(70,	1,	'Yemi Tula',	'Created Admin: Segun Kesington (ID: 3)',	'0000-00-00 00:00:00'),
(71,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(72,	1,	'Yemi Tula',	'Accessed Admin Details: Segun Kesington (ID: 3)',	'0000-00-00 00:00:00'),
(73,	1,	'Yemi Tula',	'Accessed Admin Details: Segun Kesington (ID: 3)',	'0000-00-00 00:00:00'),
(74,	1,	'Yemi Tula',	'Accessed Admin Details: Segun Kesington (ID: 3)',	'0000-00-00 00:00:00'),
(75,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(76,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(77,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(78,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(79,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(80,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(81,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(82,	1,	'Yemi Tula',	'Enabled Admin: Tula Admin (2)',	'0000-00-00 00:00:00'),
(83,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(84,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(85,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(86,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(87,	1,	'Yemi Tula',	'Enabled Admin: Tula Admin (2)',	'0000-00-00 00:00:00'),
(88,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(89,	1,	'Yemi Tula',	'Disabled Admin: Tula Admin (2)',	'0000-00-00 00:00:00'),
(90,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(91,	1,	'Yemi Tula',	'Enabled Admin: Tula Admin (2)',	'0000-00-00 00:00:00'),
(92,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(93,	1,	'Yemi Tula',	'Accessed Admin Details: Segun Kesington (ID: 3)',	'0000-00-00 00:00:00'),
(94,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(95,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(96,	1,	'Yemi Tula',	'Disabled Admin: Tula Admin (2)',	'0000-00-00 00:00:00'),
(97,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(98,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(99,	1,	'Yemi Tula',	'Accessed Admin Details: Tula Admin (ID: 2)',	'0000-00-00 00:00:00'),
(100,	1,	'Yemi Tula',	'Enabled Admin: Tula Admin (2)',	'0000-00-00 00:00:00'),
(101,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(102,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(103,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(104,	2,	'Tula Admin',	'Logged In: Successful',	'0000-00-00 00:00:00'),
(105,	1,	'Yemi Tula',	'Accessed Admin List',	'0000-00-00 00:00:00'),
(106,	2,	'Tula Admin',	'Logged Out',	'2017-01-08 07:51:11'),
(107,	2,	'Tula Admin',	'Logged In: Successful',	'2017-01-08 07:51:17'),
(108,	1,	'Yemi Tula',	'Accessed Admin List',	'2017-01-08 07:51:43'),
(109,	2,	'Tula Admin',	'Logged Out',	'2017-01-08 07:57:26'),
(110,	1,	'Yemi Tula',	'Logged In: Successful',	'2017-01-08 07:57:33'),
(111,	1,	'Yemi Tula',	'Changed Password',	'2017-01-08 07:59:23'),
(112,	1,	'Yemi Tula',	'Changed Password',	'2017-01-08 07:59:38'),
(113,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Tula (ID: 1)',	'2017-01-08 08:07:24'),
(114,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Tula (ID: 1)',	'2017-01-08 08:09:29'),
(115,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Tula (ID: 1)',	'2017-01-08 08:11:02'),
(116,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Tula (ID: 1)',	'2017-01-08 08:13:02'),
(117,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Tula (ID: 1)',	'2017-01-08 08:13:38'),
(118,	1,	'Yemi Tula',	'Edited Admin: Yemi Adetula (ID: 1)',	'2017-01-08 08:13:45'),
(119,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Adetula (ID: 1)',	'2017-01-08 08:13:52'),
(120,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Adetula (ID: 1)',	'2017-01-08 08:14:00'),
(121,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Adetula (ID: 1)',	'2017-01-08 08:18:33'),
(122,	1,	'Yemi Tula',	'Edited Admin: Yemi Tula (ID: 1)',	'2017-01-08 08:18:38'),
(123,	1,	'Yemi Tula',	'Edited Admin: Yemi Adetula (ID: 1)',	'2017-01-08 08:18:45'),
(124,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Adetula (ID: 1)',	'2017-01-08 08:19:41'),
(125,	1,	'Yemi Tula',	'Edited Admin: Yemi Tula (ID: 1)',	'2017-01-08 08:19:46'),
(126,	1,	'Yemi Tula',	'Edited Admin: Yemi Adetula (ID: 1)',	'2017-01-08 08:19:56'),
(127,	1,	'Yemi Tula',	'Approved Message: (ID: 2)',	'2017-01-09 01:59:29'),
(128,	1,	'Yemi Tula',	'Approved Message: (ID: 2)',	'2017-01-09 02:09:08'),
(129,	1,	'Yemi Tula',	'Disabled message: ID (2)',	'2017-01-09 03:14:01'),
(130,	1,	'Yemi Tula',	'Enabled message: ID (2)',	'2017-01-09 03:25:30'),
(131,	1,	'Yemi Tula',	'Disabled message: ID (2)',	'2017-01-09 03:25:50'),
(132,	1,	'Yemi Tula',	'Enabled message: ID (2)',	'2017-01-09 03:26:35'),
(133,	1,	'Yemi Tula',	'Disabled message: ID (2)',	'2017-01-09 03:26:39'),
(134,	1,	'Yemi Tula',	'Enabled message: ID (2)',	'2017-01-09 03:26:43'),
(135,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Adetula (ID: 1)',	'2017-01-09 03:34:22'),
(136,	1,	'Yemi Tula',	'Accessed Admin Details: Yemi Adetula (ID: 1)',	'2017-01-09 03:34:33'),
(137,	1,	'Yemi Tula',	'Logged Out',	'2017-01-09 03:34:36'),
(138,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-01-09 03:34:47'),
(139,	1,	'Yemi Tula',	'Logged Out',	'2017-01-16 10:08:12'),
(140,	2,	'Tula Admin',	'Logged In: Successful',	'2017-01-16 10:08:17'),
(141,	2,	'Tula Admin',	'Logged Out',	'2017-01-17 07:21:52'),
(142,	2,	'Tula Admin',	'Logged In: Successful',	'2017-01-17 07:21:54'),
(143,	2,	'Tula Admin',	'Logged Out',	'2017-01-17 08:40:21'),
(144,	2,	'Tula Admin',	'Logged In: Successful',	'2017-01-17 08:40:22'),
(145,	2,	'Tula Admin',	'Logged Out',	'2017-01-17 08:40:31'),
(146,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-01-17 08:40:35'),
(147,	1,	'Yemi Adetula',	'Accessed Admin List',	'2017-01-17 08:40:57'),
(148,	1,	'Yemi Adetula',	'Created Admin: Comfort Adetula (ID: 4)',	'2017-01-17 08:41:18'),
(149,	1,	'Yemi Adetula',	'Accessed Admin List',	'2017-01-17 08:41:18'),
(150,	1,	'Yemi Adetula',	'Accessed Admin Details: Yemi Adetula (ID: 1)',	'2017-01-18 06:05:17'),
(151,	1,	'Yemi Adetula',	'Approved Message: (ID: 17)',	'2017-01-18 06:54:50'),
(152,	1,	'Yemi Adetula',	'Approved Message: (ID: 16)',	'2017-01-18 06:54:53'),
(153,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-01-19 12:45:08'),
(154,	1,	'Yemi Adetula',	'Logged Out',	'2017-01-19 12:46:28'),
(155,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-01-19 12:46:42'),
(156,	1,	'Yemi Adetula',	'Created Admin: Fidel Ejechi (ID: 5)',	'2017-01-19 12:49:45'),
(157,	1,	'Yemi Adetula',	'Created Admin: Opeyemi (ID: 6)',	'2017-01-19 12:50:14'),
(158,	6,	'Opeyemi',	'Logged In: Successful',	'2017-01-19 01:38:46'),
(159,	6,	'Opeyemi',	'Approved Message: (ID: 20)',	'2017-01-19 01:39:15'),
(160,	6,	'Opeyemi',	'Logged Out',	'2017-01-19 01:44:51'),
(161,	5,	'Fidel Ejechi',	'Logged In: Successful',	'2017-01-20 02:51:44'),
(162,	5,	'Fidel Ejechi',	'Edited Admin: Fidelis Ejechi (ID: 5)',	'2017-01-20 02:53:46'),
(163,	5,	'Fidel Ejechi',	'Logged Out',	'2017-01-20 02:54:53'),
(164,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-01-20 03:15:09'),
(165,	5,	'Fidelis Ejechi',	'Logged Out',	'2017-01-20 03:18:18'),
(166,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-01-20 04:42:42'),
(167,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-01-21 02:26:39'),
(168,	5,	'Fidelis Ejechi',	'Approved Message: (ID: 22)',	'2017-01-21 02:28:27'),
(169,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-01-23 07:09:32'),
(170,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-02-08 02:18:28'),
(171,	2,	'Tula Admin',	'Logged In: Successful',	'2017-02-08 02:36:18'),
(172,	1,	'Yemi Adetula',	'Logged Out',	'2017-02-08 02:41:46'),
(173,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-02-08 02:41:48'),
(174,	2,	'Tula Admin',	'Logged Out',	'2017-02-08 03:10:11'),
(175,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-02-08 03:10:20'),
(176,	5,	'Fidelis Ejechi',	'Approved Message: (ID: 18)',	'2017-02-08 03:10:57'),
(177,	5,	'Fidelis Ejechi',	'Approved Message: (ID: 24)',	'2017-02-08 03:47:27'),
(178,	5,	'Fidelis Ejechi',	'Approved Message: (ID: 32)',	'2017-02-08 03:50:03'),
(179,	1,	'Yemi Adetula',	'Removed Featured Message: ()',	'2017-02-10 12:18:06'),
(180,	1,	'Yemi Adetula',	'Marked Featured Message: (32)',	'2017-02-10 12:32:41'),
(181,	1,	'Yemi Adetula',	'Logged Out',	'2017-02-13 01:53:02'),
(182,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-02-13 01:53:08'),
(183,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-02-15 02:12:06'),
(184,	5,	'Fidelis Ejechi',	'Logged Out',	'2017-02-15 02:19:38'),
(185,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-02-15 02:29:18'),
(186,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-02-17 05:32:32'),
(187,	5,	'Fidelis Ejechi',	'Removed Featured Message: (32)',	'2017-02-17 05:36:27'),
(188,	5,	'Fidelis Ejechi',	'Marked Featured Message: (22)',	'2017-02-17 05:37:20'),
(189,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-03-07 04:13:38'),
(190,	1,	'Yemi Adetula',	'Deleted Message From: sad (31)',	'2017-03-08 09:19:04'),
(191,	1,	'Yemi Adetula',	'Deleted Message From: sadsa (30)',	'2017-03-08 09:19:08'),
(192,	1,	'Yemi Adetula',	'Deleted Message From: fidel (23)',	'2017-03-08 09:19:13'),
(193,	1,	'Yemi Adetula',	'Deleted Message From: fidel (25)',	'2017-03-08 09:19:17'),
(194,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-03-09 08:46:05'),
(195,	1,	'Yemi Adetula',	'Logged Out',	'2017-03-09 08:46:12'),
(196,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-03-10 05:47:10'),
(197,	1,	'Yemi Adetula',	'Logged Out',	'2017-03-10 05:47:20'),
(198,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-03-10 05:52:04'),
(199,	1,	'Yemi Adetula',	'Changed Password',	'2017-03-10 05:52:19'),
(200,	1,	'Yemi Adetula',	'Logged Out',	'2017-03-10 05:52:26'),
(201,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-03-10 05:52:30'),
(202,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-03-13 02:35:45'),
(203,	5,	'Fidelis Ejechi',	'Changed Password',	'2017-03-13 02:37:38'),
(204,	5,	'Fidelis Ejechi',	'Deleted Message From: fidel (26)',	'2017-03-13 02:38:03'),
(205,	5,	'Fidelis Ejechi',	'Deleted Message From: fidel (27)',	'2017-03-13 02:38:14'),
(206,	5,	'Fidelis Ejechi',	'Deleted Message From: fidel (28)',	'2017-03-13 02:38:33'),
(207,	5,	'Fidelis Ejechi',	'Deleted Message From: Dickson (15)',	'2017-03-13 02:39:04'),
(208,	5,	'Fidelis Ejechi',	'Deleted Message From: Uk (29)',	'2017-03-13 02:39:11'),
(209,	5,	'Fidelis Ejechi',	'Deleted Message From: Comfort Olotu (3)',	'2017-03-13 02:39:15'),
(210,	5,	'Fidelis Ejechi',	'Deleted Message From: Opeyemi Alaran (19)',	'2017-03-13 02:39:19'),
(211,	5,	'Fidelis Ejechi',	'Disabled message: ID (22)',	'2017-03-13 02:40:32'),
(212,	5,	'Fidelis Ejechi',	'Enabled message: ID (22)',	'2017-03-13 02:40:44'),
(213,	5,	'Fidelis Ejechi',	'Deleted Message From: fidel (24)',	'2017-03-13 02:41:20'),
(214,	5,	'Fidelis Ejechi',	'Deleted Message From: Ejechi Fidelis (21)',	'2017-03-13 02:41:44'),
(215,	5,	'Fidelis Ejechi',	'Removed Featured Message: (17)',	'2017-03-13 02:47:19'),
(216,	5,	'Fidelis Ejechi',	'Marked Featured Message: (20)',	'2017-03-13 02:47:43'),
(217,	2,	'Tula Admin',	'Logged In: Successful',	'2017-03-20 05:50:48'),
(218,	1,	'Yemi Adetula',	'Logged Out',	'2017-03-20 05:50:51'),
(219,	2,	'Tula Admin',	'Edited Message: 2  (ID: 2)',	'2017-03-20 05:52:23'),
(220,	2,	'Tula Admin',	'Edited Message: 2  (ID: 2)',	'2017-03-20 05:52:42'),
(221,	2,	'Tula Admin',	'Logged In: Successful',	'2017-03-20 05:58:26'),
(222,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-03-20 06:33:08'),
(223,	5,	'Fidelis Ejechi',	'Edited Message: 22  (ID: 5)',	'2017-03-20 06:34:43'),
(224,	5,	'Fidelis Ejechi',	'Edited Message: 22  (ID: 5)',	'2017-03-20 06:37:59'),
(225,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-03-27 05:30:27'),
(226,	5,	'Fidelis Ejechi',	'Deleted Message From: Yemi Tula (32)',	'2017-03-27 05:32:04'),
(227,	5,	'Fidelis Ejechi',	'Deleted Message From: Femi Kuti (17)',	'2017-03-27 05:32:32'),
(228,	5,	'Fidelis Ejechi',	'Deleted Message From: Apostle John (16)',	'2017-03-27 05:32:40'),
(229,	5,	'Fidelis Ejechi',	'Deleted Message From: Yemi Tula (2)',	'2017-03-27 05:33:11'),
(230,	5,	'Fidelis Ejechi',	'Deleted Message From: Francis Awuya (18)',	'2017-03-27 05:33:29'),
(231,	5,	'Fidelis Ejechi',	'Logged In: Successful',	'2017-03-27 09:25:51'),
(232,	5,	'Fidelis Ejechi',	'Edited Message: 33  (ID: 5)',	'2017-03-27 09:26:29'),
(233,	5,	'Fidelis Ejechi',	'Approved Message: (ID: 33)',	'2017-03-27 09:26:45'),
(234,	5,	'Fidelis Ejechi',	'Marked Featured Message: (33)',	'2017-03-27 09:27:22'),
(235,	5,	'Fidelis Ejechi',	'Edited Message: 33  (ID: 5)',	'2017-03-27 09:29:18'),
(236,	2,	'Tula Admin',	'Logged In: Successful',	'2017-04-28 11:07:01'),
(237,	1,	'Yemi Adetula',	'Logged In: Successful',	'2017-04-28 11:07:43');

DROP TABLE IF EXISTS `card_design`;
CREATE TABLE `card_design` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_title` varchar(100) NOT NULL,
  `card_description` varchar(250) NOT NULL,
  `card_image` varchar(100) NOT NULL,
  `card_themeColor` enum('LIGHT','DARK') NOT NULL,
  `card_value` double(10,2) NOT NULL,
  `card_is_disabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_teacher_name` varchar(100) NOT NULL,
  `msg_teacher_phone` varchar(20) DEFAULT NULL,
  `msg_teacher_email` varchar(100) DEFAULT NULL,
  `msg_message` text NOT NULL,
  `msg_school` varchar(100) NOT NULL,
  `msg_class` varchar(50) DEFAULT NULL,
  `msg_state` varchar(100) NOT NULL,
  `msg_city` varchar(100) NOT NULL,
  `msg_sender_name` varchar(100) NOT NULL,
  `msg_sender_phone` varchar(20) DEFAULT NULL,
  `msg_sender_email` varchar(100) DEFAULT NULL,
  `msg_time_submitted` datetime NOT NULL,
  `msg_status` enum('PENDING','APPROVED','DECLINED') NOT NULL,
  `msg_time_approved` datetime DEFAULT NULL,
  `msg_approver_id` int(11) DEFAULT NULL,
  `msg_is_featured` int(1) DEFAULT NULL,
  `msg_is_disabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`msg_id`),
  KEY `msg_approver_id` (`msg_approver_id`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`msg_approver_id`) REFERENCES `admin` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `message` (`msg_id`, `msg_teacher_name`, `msg_teacher_phone`, `msg_teacher_email`, `msg_message`, `msg_school`, `msg_class`, `msg_state`, `msg_city`, `msg_sender_name`, `msg_sender_phone`, `msg_sender_email`, `msg_time_submitted`, `msg_status`, `msg_time_approved`, `msg_approver_id`, `msg_is_featured`, `msg_is_disabled`) VALUES
(20,	'Mrs. Daramola',	'08128995242',	'yemi.alaran@strictlydev.org',	'Thank you Ma for being the foundation of learning...',	'St. John\'s Nur/Pry School',	'Primary 3',	'Lagos',	'Oshoid',	'Opeyemi Alaran',	'08037175446',	'openimo@gmail.com',	'2017-01-19 01:38:13',	'APPROVED',	'2017-01-19 01:39:15',	6,	1,	NULL),
(22,	'Sir Thomas Nwozubu  (aka Omotosho)',	'08033022723',	'',	'Our school was one of those public schools where we didn\'t have teachers in some subject areas. You saw the gap and gave us the requisite foundation needed even when it was not part of your job. We are what we are today because you cared. Thank you sir.',	'Utagba Ogbe Grammar School',	'SS3',	'Delta',	'Kwale',	'Ejechi Fidelis',	'08068774221',	'fidelejechi@yahoo.com',	'2017-01-20 03:12:32',	'APPROVED',	'2017-01-21 02:28:27',	5,	1,	NULL),
(33,	'Mrs Ifeoma Afamefuna',	'',	'',	'Ma, you\'re the reason why i looked forward to coming to school every morning because you made us feel comfortable around you. You teach with such compassion that brought me so much joy and zeal in wanting to read more and solve mathematical problems. You inspired me to be an engineer! Thank you ma.',	'Girls Secondary School Alor',	'JS3/SS1',	'Anambra',	'Alor',	'Ogbuefi Eucharia Anulika',	'08033022723',	'talk2ukalex@yahoo.co.uk',	'2017-03-27 09:22:00',	'APPROVED',	'2017-03-27 09:26:45',	5,	1,	NULL);

DROP TABLE IF EXISTS `pincode`;
CREATE TABLE `pincode` (
  `pin_id` int(11) NOT NULL AUTO_INCREMENT,
  `pin_code` varchar(100) NOT NULL,
  `pin_date_generated` date NOT NULL,
  `pin_value` double(10,2) NOT NULL,
  `pin_is_used` int(1) DEFAULT NULL,
  `pin_msg_id` int(11) DEFAULT NULL,
  `pin_date_used` date DEFAULT NULL,
  `pin_is_disabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`pin_id`),
  KEY `pin_msg_id` (`pin_msg_id`),
  CONSTRAINT `pincode_ibfk_1` FOREIGN KEY (`pin_msg_id`) REFERENCES `message` (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `video`;
CREATE TABLE `video` (
  `vid_id` int(11) NOT NULL AUTO_INCREMENT,
  `vid_student` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vid_teacher` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vid_school` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vid_city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vid_embed_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vid_date` datetime NOT NULL,
  PRIMARY KEY (`vid_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2017-04-30 16:41:39
