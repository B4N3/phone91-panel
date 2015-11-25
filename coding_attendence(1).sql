-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 19, 2012 at 05:30 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `coding_attendence`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendence`
--

CREATE TABLE IF NOT EXISTS `attendence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_today` varchar(50) NOT NULL DEFAULT '00-00-0000',
  `emp_id` varchar(30) NOT NULL,
  `arrival_time` varchar(50) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 for P 2 for A 3 for L',
  `leave_reason_id` int(11) NOT NULL,
  `marked_by` int(2) NOT NULL DEFAULT '0' COMMENT 'user-0,admin-1',
  `date` date NOT NULL COMMENT 'for which date user mark the attendence',
  `arrival_date_time` datetime NOT NULL,
  `reason` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=333 ;

--
-- Dumping data for table `attendence`
--

INSERT INTO `attendence` (`id`, `date_today`, `emp_id`, `arrival_time`, `type`, `leave_reason_id`, `marked_by`, `date`, `arrival_date_time`, `reason`) VALUES
(15, '10-12-2012', '5099f0d967f8d09ba01bbb1d', '11:24:33 am', 1, 3, 0, '2012-12-06', '0000-00-00 00:00:00', ''),
(26, '13-12-2012', '5099f0d967f8d09ba01bbb1d', '10:21:24 am', 1, 4, 0, '2012-12-11', '0000-00-00 00:00:00', ''),
(30, '08-12-2012', '4ff6bf9746923e9e3a000022', '17:09:06 pm', 3, 5, 0, '2012-12-12', '0000-00-00 00:00:00', ''),
(31, '13-12-2012', '5099f0d967f8d09ba01bbb1d', '10:21:15 am', 3, 8, 0, '2012-12-10', '0000-00-00 00:00:00', ''),
(35, '10-12-2012', '5099f0d967f8d09ba01bbb1d', '11:24:32 am', 1, 3, 0, '2012-12-05', '0000-00-00 00:00:00', ''),
(36, '10-12-2012', '5099f0d967f8d09ba01bbb1d', '02:28:55 pm', 1, 10, 0, '2012-12-07', '0000-00-00 00:00:00', ''),
(37, '10-12-2012', '5099f0d967f8d09ba01bbb1d', '12:17:14 pm', 4, 3, 0, '2012-12-08', '0000-00-00 00:00:00', '45'),
(41, '10-12-2012', '50b07d9067f8e13516aa9454', '12:25:27 pm', 1, 0, 0, '2012-12-08', '0000-00-00 00:00:00', ''),
(42, '10-12-2012', '50b07d9067f8e13516aa9454', '12:25:57 pm', 4, 0, 0, '2012-12-07', '0000-00-00 00:00:00', 'My own Choice'),
(43, '10-12-2012', '50b07d9067f8e13516aa9454', '12:25:29 pm', 1, 0, 0, '2012-12-06', '0000-00-00 00:00:00', ''),
(45, '10-12-2012', '4ff6bf9746923e9e3a000022', '01:17:53 pm', 1, 0, 0, '2012-12-08', '0000-00-00 00:00:00', ''),
(46, '19-12-2012', '4ff6bf9746923e9e3a000022', '04:26:00 pm', 4, 0, 0, '2012-12-07', '0000-00-00 00:00:00', ''),
(47, '19-12-2012', '4ff6bf9746923e9e3a000022', '04:22:08 pm', 1, 0, 0, '2012-12-06', '0000-00-00 00:00:00', ''),
(48, '10-12-2012', '4ff6bf9746923e9e3a000022', '01:17:57 pm', 1, 0, 0, '2012-12-05', '0000-00-00 00:00:00', ''),
(49, '10-12-2012', '4ff6bf9746923e9e3a000022', '01:17:58 pm', 1, 0, 0, '2012-12-04', '0000-00-00 00:00:00', ''),
(50, '10-12-2012', '4ff6bf9746923e9e3a000022', '01:18:01 pm', 2, 0, 0, '2012-12-10', '0000-00-00 00:00:00', ''),
(52, '18-12-2012', '5099f0d967f8d09ba01bbb1d', '02:34:38 pm', 1, 11, 0, '2012-12-04', '0000-00-00 00:00:00', ''),
(58, '11-12-2012', '4ffbb6c646923e9a08000001', '05:22:04 pm', 1, 0, 0, '2012-12-06', '0000-00-00 00:00:00', ''),
(59, '19-12-2012', '5020d4af46923efb4c00000d', '04:21:31 pm', 2, 0, 0, '2012-12-07', '0000-00-00 00:00:00', ''),
(60, '11-12-2012', '50b5b35067f882334e4bb125', '06:56:02 pm', 2, 0, 0, '2012-12-06', '0000-00-00 00:00:00', ''),
(61, '11-12-2012', '50b8580167f8561ea3ddf9ac', '06:56:29 pm', 1, 0, 0, '2012-12-06', '0000-00-00 00:00:00', ''),
(62, '11-12-2012', '4ff6bf3746923e773a000001', '06:56:31 pm', 1, 0, 0, '2012-12-06', '0000-00-00 00:00:00', ''),
(68, '19-12-2012', '4ff6bf9746923e9e3a000022', '04:21:30 pm', 2, 13, 0, '2012-12-11', '0000-00-00 00:00:00', ''),
(69, '12-12-2012', '4ff6bf2b46923ed339000005', '05:37:47 pm', 1, 0, 0, '2012-12-16', '0000-00-00 00:00:00', ''),
(70, '12-12-2012', '4ff6bf3746923e773a000001', '05:37:48 pm', 1, 0, 0, '2012-12-15', '0000-00-00 00:00:00', ''),
(71, '12-12-2012', '5059668d46923e8837000013', '05:37:49 pm', 1, 0, 0, '2012-12-14', '0000-00-00 00:00:00', ''),
(72, '12-12-2012', '506d367346923eb41700000b', '05:37:49 pm', 1, 0, 0, '2012-12-13', '0000-00-00 00:00:00', ''),
(73, '12-12-2012', '4ffbb6e446923e5f09000003', '05:37:50 pm', 1, 0, 0, '2012-12-13', '0000-00-00 00:00:00', ''),
(74, '12-12-2012', '4ff6bf2b46923ed339000005', '05:37:51 pm', 1, 0, 0, '2012-12-12', '0000-00-00 00:00:00', ''),
(75, '12-12-2012', '4ff6bf3746923e773a000001', '05:37:52 pm', 1, 0, 0, '2012-12-11', '0000-00-00 00:00:00', ''),
(76, '12-12-2012', '4ff6bf2b46923ed339000005', '05:37:53 pm', 1, 0, 0, '2012-12-08', '0000-00-00 00:00:00', ''),
(141, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-01-28', '0000-00-00 00:00:00', ''),
(142, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-01-29', '0000-00-00 00:00:00', ''),
(143, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-01-30', '0000-00-00 00:00:00', ''),
(144, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-01-31', '0000-00-00 00:00:00', ''),
(145, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-01', '0000-00-00 00:00:00', ''),
(146, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-02', '0000-00-00 00:00:00', ''),
(147, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-03', '0000-00-00 00:00:00', ''),
(148, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-04', '0000-00-00 00:00:00', ''),
(149, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-05', '0000-00-00 00:00:00', ''),
(150, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-06', '0000-00-00 00:00:00', ''),
(151, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-07', '0000-00-00 00:00:00', ''),
(152, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-08', '0000-00-00 00:00:00', ''),
(153, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-09', '0000-00-00 00:00:00', ''),
(154, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-10', '0000-00-00 00:00:00', ''),
(155, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-11', '0000-00-00 00:00:00', ''),
(156, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-12', '0000-00-00 00:00:00', ''),
(157, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-13', '0000-00-00 00:00:00', ''),
(158, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-14', '0000-00-00 00:00:00', ''),
(159, '14-12-2012', '5099f0d967f8d09ba01bbb1d', '10:06:14 am', 3, 15, 0, '2013-02-15', '0000-00-00 00:00:00', ''),
(160, '14-12-2012', '4ff6bf2b46923ed339000005', '15:44:01 pm', 3, 18, 0, '2012-12-23', '0000-00-00 00:00:00', ''),
(161, '14-12-2012', '4ff6bf2b46923ed339000005', '07:40:29 pm', 1, 20, 0, '2012-12-14', '0000-00-00 00:00:00', ''),
(162, '14-12-2012', '4ff6bf2b46923ed339000005', '19:37:58 pm', 3, 24, 0, '2012-12-25', '0000-00-00 00:00:00', ''),
(163, '14-12-2012', '4ff6bf2b46923ed339000005', '19:38:36 pm', 3, 24, 0, '2012-12-26', '0000-00-00 00:00:00', ''),
(164, '14-12-2012', '4ff6bf2b46923ed339000005', '19:38:36 pm', 3, 24, 0, '2012-12-27', '0000-00-00 00:00:00', ''),
(165, '14-12-2012', '4ff6bf2b46923ed339000005', '19:38:36 pm', 3, 24, 0, '2012-12-28', '0000-00-00 00:00:00', ''),
(166, '14-12-2012', '4ff6bf2b46923ed339000005', '19:38:48 pm', 3, 25, 0, '2013-01-01', '0000-00-00 00:00:00', ''),
(167, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-17', '0000-00-00 00:00:00', ''),
(168, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-18', '0000-00-00 00:00:00', ''),
(169, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-19', '0000-00-00 00:00:00', ''),
(170, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-20', '0000-00-00 00:00:00', ''),
(171, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-21', '0000-00-00 00:00:00', ''),
(172, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-22', '0000-00-00 00:00:00', ''),
(173, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-23', '0000-00-00 00:00:00', ''),
(174, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-24', '0000-00-00 00:00:00', ''),
(175, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-25', '0000-00-00 00:00:00', ''),
(176, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-26', '0000-00-00 00:00:00', ''),
(177, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-27', '0000-00-00 00:00:00', ''),
(178, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-28', '0000-00-00 00:00:00', ''),
(179, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-29', '0000-00-00 00:00:00', ''),
(180, '17-12-2012', '4ff6bf9746923e9e3a000022', '11:49:25 am', 3, 26, 0, '2012-12-30', '0000-00-00 00:00:00', ''),
(181, '17-12-2012', '50b07d9067f8e13516aa9454', '02:49:30 pm', 1, 0, 0, '2012-12-13', '0000-00-00 00:00:00', ''),
(182, '17-12-2012', '50b07d9067f8e13516aa9454', '02:49:31 pm', 1, 0, 0, '2012-12-15', '0000-00-00 00:00:00', ''),
(185, '17-12-2012', '5099f0d967f8d09ba01bbb1d', '03:47:01 pm', 3, 28, 0, '2012-10-05', '0000-00-00 00:00:00', ''),
(191, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:06:05 pm', 3, 29, 0, '2012-12-25', '0000-00-00 00:00:00', ''),
(192, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:06:05 pm', 3, 29, 0, '2012-12-26', '0000-00-00 00:00:00', ''),
(193, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:06:05 pm', 3, 29, 0, '2012-12-27', '0000-00-00 00:00:00', ''),
(204, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:08:17 pm', 3, 29, 0, '2012-12-28', '0000-00-00 00:00:00', ''),
(205, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:08:17 pm', 3, 29, 0, '2012-12-29', '0000-00-00 00:00:00', ''),
(206, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:08:23 pm', 3, 29, 0, '2012-12-30', '0000-00-00 00:00:00', ''),
(207, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:08:23 pm', 3, 29, 0, '2012-12-31', '0000-00-00 00:00:00', ''),
(208, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:08:23 pm', 3, 29, 0, '2013-01-01', '0000-00-00 00:00:00', ''),
(209, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:08:23 pm', 3, 29, 0, '2013-01-02', '0000-00-00 00:00:00', ''),
(210, '17-12-2012', '50b8580167f8561ea3ddf9ac', '17:08:23 pm', 3, 29, 0, '2013-01-03', '0000-00-00 00:00:00', ''),
(211, '17-12-2012', '50892e1f67f89f72f2dcb92e', '05:50:00 pm', 1, 31, 0, '2012-12-17', '0000-00-00 00:00:00', ''),
(212, '19-12-2012', '4ff6b5e246923e0d1b000008', '04:21:32 pm', 2, 0, 0, '2012-12-17', '0000-00-00 00:00:00', ''),
(214, '18-12-2012', '50b07d9067f8e13516aa9454', '09:37:22 am', 3, 9, 0, '2012-12-05', '0000-00-00 00:00:00', ''),
(261, '18-12-2012', '5099f0d967f8d09ba01bbb1d', '16:02:55 pm', 3, 42, 0, '2012-12-19', '0000-00-00 00:00:00', ''),
(262, '18-12-2012', '5099f0d967f8d09ba01bbb1d', '16:02:55 pm', 3, 73, 0, '2012-12-20', '0000-00-00 00:00:00', ''),
(267, '18-12-2012', '5099f0d967f8d09ba01bbb1d', '16:08:15 pm', 3, 45, 0, '2012-12-23', '0000-00-00 00:00:00', ''),
(268, '18-12-2012', '5099f0d967f8d09ba01bbb1d', '16:08:15 pm', 3, 45, 0, '2012-12-24', '0000-00-00 00:00:00', ''),
(270, '18-12-2012', '5099f0d967f8d09ba01bbb1d', '16:08:57 pm', 3, 46, 0, '2012-12-30', '0000-00-00 00:00:00', ''),
(321, '19-12-2012', '50b07d9067f8e13516aa9454', '13:14:41 pm', 3, 93, 0, '2012-12-20', '0000-00-00 00:00:00', ''),
(322, '19-12-2012', '50b07d9067f8e13516aa9454', '13:14:41 pm', 3, 93, 0, '2012-12-21', '0000-00-00 00:00:00', ''),
(324, '19-12-2012', '5099f0d967f8d09ba01bbb1d', '13:16:03 pm', 3, 94, 0, '2012-12-26', '0000-00-00 00:00:00', ''),
(325, '19-12-2012', '5099f0d967f8d09ba01bbb1d', '13:16:03 pm', 3, 94, 0, '2012-12-27', '0000-00-00 00:00:00', ''),
(326, '19-12-2012', '4ff6bf9746923e9e3a000022', '04:18:12 pm', 4, 0, 0, '2013-01-07', '0000-00-00 00:00:00', ''),
(329, '19-12-2012', '50b07d9067f8e13516aa9454', '16:35:37 pm', 3, 27, 0, '2012-12-24', '0000-00-00 00:00:00', ''),
(330, '19-12-2012', '50b07d9067f8e13516aa9454', '16:35:37 pm', 3, 27, 0, '2012-12-25', '0000-00-00 00:00:00', ''),
(331, '19-12-2012', '50b07d9067f8e13516aa9454', '16:35:37 pm', 3, 27, 0, '2012-12-26', '0000-00-00 00:00:00', ''),
(332, '19-12-2012', '50b07d9067f8e13516aa9454', '16:35:37 pm', 3, 27, 0, '2012-12-27', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE IF NOT EXISTS `designation` (
  `id` varchar(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `desig_name` varchar(15) NOT NULL,
  `pay_scale` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `dept_id`, `desig_name`, `pay_scale`) VALUES
('1', 1, 'Jr. Developer', 20000),
('2', 1, 'developer', 25000),
('3', 2, 'Jr. Designer', 8000),
('4', 2, 'Sr. Designer', 15000),
('5', 5, 'Sr Admin', 20000),
('6', 5, 'Jr Admin', 15000),
('7', 6, 'CEO', 25000),
('8', 2, 'Web Designer', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `earn_salary`
--

CREATE TABLE IF NOT EXISTS `earn_salary` (
  `earn_salary_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_plan_id` int(11) NOT NULL,
  `earn_type` varchar(50) NOT NULL,
  `earn_amount` decimal(10,0) NOT NULL,
  `percentage` decimal(11,0) NOT NULL,
  PRIMARY KEY (`earn_salary_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

--
-- Dumping data for table `earn_salary`
--

INSERT INTO `earn_salary` (`earn_salary_id`, `salary_plan_id`, `earn_type`, `earn_amount`, `percentage`) VALUES
(9, 4, 'Gross Conveyance', '0', '10'),
(8, 4, 'HRA', '0', '10'),
(3, 2, 'HRA', '0', '40'),
(4, 3, 'HRA', '0', '10'),
(5, 3, 'Gross Conveyance', '0', '10'),
(6, 3, 'Conv.', '0', '10'),
(10, 4, 'Conv.', '0', '10'),
(65, 19, 'PF', '0', '10'),
(12, 5, 'HRA', '0', '10'),
(13, 5, 'Gross Conveyance', '0', '10'),
(14, 6, 'Conv.', '0', '50'),
(67, 20, '', '0', '0'),
(66, 19, 'TEST', '0', '15');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `emp_id` varchar(30) NOT NULL,
  `admin_id` varchar(30) NOT NULL,
  `attendence_approved_till` datetime NOT NULL,
  `last_approval_admin` datetime NOT NULL,
  `paid_leaves_remaining` varchar(2) NOT NULL,
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_deduction`
--

CREATE TABLE IF NOT EXISTS `employee_deduction` (
  `employee_deduction_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_plan_id` int(11) NOT NULL,
  `employee_deduction_type` varchar(50) NOT NULL,
  `employee_deduction_amount` decimal(10,0) NOT NULL,
  `employee_deduction_percentage` decimal(10,0) NOT NULL,
  PRIMARY KEY (`employee_deduction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `employee_deduction`
--

INSERT INTO `employee_deduction` (`employee_deduction_id`, `salary_plan_id`, `employee_deduction_type`, `employee_deduction_amount`, `employee_deduction_percentage`) VALUES
(24, 20, '', '0', '0'),
(3, 2, 'p tax', '0', '10'),
(4, 3, 'PF', '0', '12'),
(5, 3, 'Deposit Link Scheme', '0', '1'),
(6, 4, 'PF', '0', '12'),
(7, 4, 'Deposit Link Scheme', '0', '1'),
(8, 5, 'PF', '0', '12'),
(9, 5, 'Deposit Link Scheme', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `employer_deduction`
--

CREATE TABLE IF NOT EXISTS `employer_deduction` (
  `employer_deduction_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_plan_id` int(11) NOT NULL,
  `deduction_type` varchar(50) NOT NULL,
  `deduction_amount` decimal(10,0) NOT NULL,
  `employer_deduction_percentage` decimal(10,0) NOT NULL,
  PRIMARY KEY (`employer_deduction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `employer_deduction`
--

INSERT INTO `employer_deduction` (`employer_deduction_id`, `salary_plan_id`, `deduction_type`, `deduction_amount`, `employer_deduction_percentage`) VALUES
(4, 3, 'PF', '0', '4'),
(5, 3, 'FPF', '0', '8'),
(6, 3, 'Administration Charges', '0', '1'),
(7, 3, 'PF Inspection Charges', '0', '6'),
(8, 4, 'PF', '0', '4'),
(9, 4, 'FPF', '0', '8'),
(15, 3, 'PP', '0', '60'),
(11, 4, 'PF Inspection Charges', '0', '0'),
(12, 5, 'PF', '0', '4'),
(13, 5, 'FPF', '0', '8'),
(14, 5, 'Administration Charges', '0', '1'),
(30, 20, '', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `event_log`
--

CREATE TABLE IF NOT EXISTS `event_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL DEFAULT ' ',
  `emp_id` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=874 ;

--
-- Dumping data for table `event_log`
--

INSERT INTO `event_log` (`id`, `description`, `emp_id`) VALUES
(671, 'attendance of 4ffacb5046923e055e000005 of date 22-11-2012 is modified', ''),
(672, 'attendance of 4ffacb5046923e055e000005 of date 23-11-2012 is modified', ''),
(673, 'attendance of 4ffacb5046923e055e000005 of date 24-11-2012 is modified', ''),
(674, 'attendance of 4ffacb5046923e055e000005 of date 26-11-2012 is modified', ''),
(675, 'attendance of 4ffacb5046923e055e000005 of date 27-11-2012 is modified', ''),
(676, 'attendance of 4ffacb5046923e055e000005 of date 28-11-2012 is modified', ''),
(677, 'attendance of 4ffacb5046923e055e000005 of date 29-11-2012 is modified', ''),
(678, 'attendance of 4ffacb5046923e055e000005 of date 30-11-2012 is modified', ''),
(679, 'attendance of 4ffacb5046923e055e000005 of date 01-12-2012 is modified', ''),
(680, 'attendance of 4ffacb5046923e055e000005 of date 04-12-2012 is modified', ''),
(681, 'attendance of 4ffacb5046923e055e000005 of date 05-12-2012 is modified', ''),
(682, 'attendance of 5015076846923e131e000007 of date 29-11-2012 is modified', ''),
(683, 'attendance of 5015076846923e131e000007 of date 26-11-2012 is modified', ''),
(684, 'attendance of 4ffacb5046923e055e000005 of date 05-12-2012 is modified', ''),
(685, 'attendance of 4ffacb5046923e055e000005 of date 04-12-2012 is modified', ''),
(686, 'attendance of 4ffacb5046923e055e000005 of date 01-12-2012 is modified', ''),
(687, 'attendance of 4ffacb5046923e055e000005 of date 30-11-2012 is modified', ''),
(688, 'attendance of 4ffacb5046923e055e000005 of date 29-11-2012 is modified', ''),
(689, 'attendance of 4ffacb5046923e055e000005 of date 29-11-2012 is modified', ''),
(690, 'attendance of 4ffacb5046923e055e000005 of date 29-11-2012 is modified', ''),
(691, 'attendance of 4ffacb5046923e055e000005 of date 30-11-2012 is modified', ''),
(692, 'attendance of 4ffacb5046923e055e000005 of date 01-12-2012 is modified', ''),
(693, 'attendance of 4ffacb5046923e055e000005 of date 01-12-2012 is modified', ''),
(694, 'attendance of 4ffacb5046923e055e000005 of date 04-12-2012 is modified', ''),
(695, 'attendance of 4ffacb5046923e055e000005 of date 04-12-2012 is modified', ''),
(696, 'attendance of 5099f0d967f8d09ba01bbb1d of date 01-12-2012 is modified', ''),
(697, 'attendance of 5099f0d967f8d09ba01bbb1d of date 30-11-2012 is modified', ''),
(698, 'attendance of 5099f0d967f8d09ba01bbb1d of date 29-11-2012 is modified', ''),
(699, 'attendance of 5099f0d967f8d09ba01bbb1d of date 28-11-2012 is modified', ''),
(700, 'attendance of 5099f0d967f8d09ba01bbb1d of date 27-11-2012 is modified', ''),
(701, 'attendance of 4ffbb6c646923e9a08000001 of date 24-11-2012 is modified', ''),
(702, 'attendance of 4ffbb6c646923e9a08000001 of date 24-11-2012 is modified', ''),
(703, 'attendance of 50b07d9067f8e13516aa9454 of date 22-11-2012 is modified', ''),
(704, 'attendance of 5099f0d967f8d09ba01bbb1d of date 27-11-2012 is modified', ''),
(705, 'attendance of 4ff6bf9746923e9e3a000022 of date 01-12-2012 is modified', ''),
(706, 'attendance of 4ff6bf9746923e9e3a000022 of date 30-11-2012 is modified', ''),
(707, 'attendance of 50b07d9067f8e13516aa9454 of date 28-11-2012 is modified', ''),
(708, 'attendance of 50b07d9067f8e13516aa9454 of date 29-11-2012 is modified', ''),
(709, 'attendance of 50b07d9067f8e13516aa9454 of date 30-11-2012 is modified', ''),
(710, 'attendance of 50b07d9067f8e13516aa9454 of date 01-12-2012 is modified', ''),
(711, 'attendance of 50b07d9067f8e13516aa9454 of date 03-12-2012 is modified', ''),
(712, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(713, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(714, 'attendance of 5099f0d967f8d09ba01bbb1d of date 01-12-2012 is modified', ''),
(715, 'attendance of 5099f0d967f8d09ba01bbb1d of date 30-11-2012 is modified', ''),
(716, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(717, 'attendance of 5099f0d967f8d09ba01bbb1d of date 01-12-2012 is modified', ''),
(718, 'attendance of 5099f0d967f8d09ba01bbb1d of date 01-12-2012 is modified', ''),
(719, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(720, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(721, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(722, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(723, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(724, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(725, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(726, 'attendance of 5099f0d967f8d09ba01bbb1d of date 01-12-2012 is modified', ''),
(727, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(728, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(729, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(730, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(731, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(732, 'attendance of 5099f0d967f8d09ba01bbb1d of date 01-12-2012 is modified', ''),
(733, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(734, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(735, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(736, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(737, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(738, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(739, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(740, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(741, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(742, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(743, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(744, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(745, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(746, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(747, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(748, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(749, 'attendance of 4ff6bf9746923e9e3a000022 of date 04-12-2012 is modified', ''),
(750, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(751, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(752, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(753, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(754, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(755, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(756, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(757, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(758, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(759, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(760, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(761, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(762, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(763, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(764, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(765, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(766, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(767, 'attendance of 50b07d9067f8e13516aa9454 of date 07-12-2012 is modified', ''),
(768, 'attendance of 50b07d9067f8e13516aa9454 of date 07-12-2012 is modified', ''),
(769, 'attendance of 50b07d9067f8e13516aa9454 of date 06-12-2012 is modified', ''),
(770, 'attendance of 50b07d9067f8e13516aa9454 of date 05-12-2012 is modified', ''),
(771, 'attendance of 50b07d9067f8e13516aa9454 of date 04-12-2012 is modified', ''),
(772, 'attendance of 50b07d9067f8e13516aa9454 of date 03-12-2012 is modified', ''),
(773, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(774, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(775, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(776, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(777, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(778, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(779, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(780, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(781, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(782, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(783, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(784, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(785, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(786, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(787, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(788, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(789, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(790, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(791, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(792, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(793, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(794, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(795, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(796, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(797, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(798, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(799, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(800, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(801, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(802, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(803, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(804, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(805, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(806, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(807, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(808, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(809, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(810, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(811, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(812, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(813, 'attendance of 5099f0d967f8d09ba01bbb1d of date 03-12-2012 is modified', ''),
(814, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(815, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(816, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(817, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(818, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(819, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(820, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(821, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(822, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(823, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-12-2012 is modified', ''),
(824, 'attendance of 5099f0d967f8d09ba01bbb1d of date 06-12-2012 is modified', ''),
(825, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(826, 'attendance of 5099f0d967f8d09ba01bbb1d of date 08-12-2012 is modified', ''),
(827, 'attendance of 5099f0d967f8d09ba01bbb1d of date 08-12-2012 is modified', ''),
(828, 'attendance of 50b07d9067f8e13516aa9454 of date 08-12-2012 is modified', ''),
(829, 'attendance of 50b07d9067f8e13516aa9454 of date 07-12-2012 is modified', ''),
(830, 'attendance of 50b07d9067f8e13516aa9454 of date 06-12-2012 is modified', ''),
(831, 'attendance of 50b07d9067f8e13516aa9454 of date 05-12-2012 is modified', ''),
(832, 'attendance of 50b07d9067f8e13516aa9454 of date 07-12-2012 is modified', ''),
(833, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(834, 'attendance of 4ff6bf9746923e9e3a000022 of date 08-12-2012 is modified', ''),
(835, 'attendance of 4ff6bf9746923e9e3a000022 of date 07-12-2012 is modified', ''),
(836, 'attendance of 4ff6bf9746923e9e3a000022 of date 06-12-2012 is modified', ''),
(837, 'attendance of 4ff6bf9746923e9e3a000022 of date 05-12-2012 is modified', ''),
(838, 'attendance of 4ff6bf9746923e9e3a000022 of date 04-12-2012 is modified', ''),
(839, 'attendance of 5099f0d967f8d09ba01bbb1d of date 07-12-2012 is modified', ''),
(840, 'attendance of 4ffbb6c646923e9a08000001 of date 06-12-2012 is modified', ''),
(841, 'attendance of 5020d4af46923efb4c00000d of date 07-12-2012 is modified', ''),
(842, 'attendance of 50b5b35067f882334e4bb125 of date 06-12-2012 is modified', ''),
(843, 'attendance of 50b5b35067f882334e4bb125 of date 06-12-2012 is modified', ''),
(844, 'attendance of 50b5b35067f882334e4bb125 of date 06-12-2012 is modified', ''),
(845, 'attendance of 50b8580167f8561ea3ddf9ac of date 06-12-2012 is modified', ''),
(846, 'attendance of 4ff6bf3746923e773a000001 of date 06-12-2012 is modified', ''),
(847, 'attendance of 4ff6bf9746923e9e3a000022 of date 11-12-2012 is modified', ''),
(848, 'attendance of 4ff6bf9746923e9e3a000022 of date 11-12-2012 is modified', ''),
(849, 'attendance of 4ff6bf2b46923ed339000005 of date 16-12-2012 is modified', ''),
(850, 'attendance of 4ff6bf3746923e773a000001 of date 15-12-2012 is modified', ''),
(851, 'attendance of 5059668d46923e8837000013 of date 14-12-2012 is modified', ''),
(852, 'attendance of 506d367346923eb41700000b of date 13-12-2012 is modified', ''),
(853, 'attendance of 4ffbb6e446923e5f09000003 of date 13-12-2012 is modified', ''),
(854, 'attendance of 4ff6bf3746923e773a000001 of date 11-12-2012 is modified', ''),
(855, 'attendance of 4ff6bf2b46923ed339000005 of date 08-12-2012 is modified', ''),
(856, 'attendance of 5099f0d967f8d09ba01bbb1d of date 10-12-2012 is modified', ''),
(857, 'attendance of 5099f0d967f8d09ba01bbb1d of date 10-12-2012 is modified', ''),
(858, 'attendance of 5099f0d967f8d09ba01bbb1d of date 11-12-2012 is modified', ''),
(859, 'attendance of 50b07d9067f8e13516aa9454 of date 13-12-2012 is modified', ''),
(860, 'attendance of 50b07d9067f8e13516aa9454 of date 15-12-2012 is modified', ''),
(861, 'attendance of 50b07d9067f8e13516aa9454 of date 18-12-2012 is modified', ''),
(862, 'attendance of 5099f0d967f8d09ba01bbb1d of date 05-10-2012 is modified', ''),
(863, 'attendance of 5099f0d967f8d09ba01bbb1d of date 04-12-2012 is modified', ''),
(864, 'attendance of 4ff6bf9746923e9e3a000022 of date 07-01-2013 is modified', ''),
(865, 'attendance of 4ff6bf9746923e9e3a000022 of date 07-01-2013 is modified', ''),
(866, 'attendance of 4ff6bf9746923e9e3a000022 of date 06-12-2012 is modified', ''),
(867, 'attendance of 4ff6bf9746923e9e3a000022 of date 07-12-2012 is modified', ''),
(868, 'attendance of 4ff6bf9746923e9e3a000022 of date 11-12-2012 is modified', ''),
(869, 'attendance of 5020d4af46923efb4c00000d of date 07-12-2012 is modified', ''),
(870, 'attendance of 4ff6b5e246923e0d1b000008 of date 17-12-2012 is modified', ''),
(871, 'attendance of 4ff6bf9746923e9e3a000022 of date 06-12-2012 is modified', ''),
(872, 'attendance of 4ff6bf9746923e9e3a000022 of date 07-12-2012 is modified', ''),
(873, 'attendance of 50b07d9067f8e13516aa9454 of date 18-12-2012 is modified', '');

-- --------------------------------------------------------

--
-- Table structure for table `leave_plan`
--

CREATE TABLE IF NOT EXISTS `leave_plan` (
  `leave_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(50) NOT NULL,
  `plan_description` varchar(255) NOT NULL,
  `Leave_Carry_forward` varchar(20) NOT NULL,
  `working_time` varchar(20) NOT NULL,
  `plan_status` varchar(50) NOT NULL,
  `plan_duration` varchar(90) NOT NULL,
  PRIMARY KEY (`leave_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `leave_plan`
--

INSERT INTO `leave_plan` (`leave_plan_id`, `plan_name`, `plan_description`, `Leave_Carry_forward`, `working_time`, `plan_status`, `plan_duration`) VALUES
(6, '1 year plan', 'kjlllll', 'yes', '9', '0', '454'),
(5, '3 month', 'THIS is only for fresher', 'yes', '9HOUR', '0', '90'),
(7, '885', 'jkjjj', 'no', '9', 'monthly', '77'),
(8, '1 year plan', 'sdfdf', 'yes', '9 hour', 'monthly', '{"day":"5","month":"2","year":"1"}'),
(9, 'my plan', 'kuchh??', 'yes', '8', 'monthly', '{"day":"2","month":"5","year":"3"}'),
(10, 'hgj', '4165461Enter description...! ', 'no', '56', 'monthly', '{"day":"6","month":"6","year":"5"}'),
(20, '2 days holiday', '2 day off', 'yes', '8 hour', 'weekly', '{"day":"6","month":"6","year":"3"}'),
(19, 'sudhir paln', 'best performance', 'yes', '12 hour', 'weekly', '{"day":"5","month":"2","year":"3"}'),
(18, '5 year plan', 'dslkjkk', 'no', '8 hour', 'weekly', '{"day":"6","month":"2","year":"3"}'),
(17, '2 year plan', 'for saldfkj', 'yes', '9 hour', 'monthly', '{"day":"5","month":"6","year":"2"}'),
(21, '8 year plan', '8 year plan...', 'yes', '3 hour', 'weekly', '{"day":"7","month":"5","year":"8"}');

-- --------------------------------------------------------

--
-- Table structure for table `leave_reason`
--

CREATE TABLE IF NOT EXISTS `leave_reason` (
  `leave_reason_id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(50) NOT NULL,
  PRIMARY KEY (`leave_reason_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=96 ;

--
-- Dumping data for table `leave_reason`
--

INSERT INTO `leave_reason` (`leave_reason_id`, `reason`) VALUES
(1, 'no Reason Specified'),
(2, 'no Reason Specified'),
(3, 'no Reason Specified'),
(4, 'gf'),
(5, 'nmbnm'),
(6, 'fg'),
(7, 'f'),
(8, 'fg'),
(9, 'nothing special some secret reason'),
(10, 'No Reason Specified'),
(11, 're'),
(12, 'hgh'),
(13, 'no Reason Specified'),
(14, 'hhh'),
(15, 'personal'),
(16, 'gh'),
(17, 'r'),
(18, 'Â '),
(19, 'Â '),
(20, 'No Reason Specified'),
(21, 'xxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxx'),
(22, 'Â '),
(23, 'xxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxx'),
(24, 'xxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxx'),
(25, 'xxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxx'),
(26, 'bncvb'),
(27, '25'),
(28, '56'),
(29, 'ass'),
(30, 'sdf'),
(31, 'No Reason Specified'),
(32, 'hie'),
(33, 'df'),
(34, 'personal'),
(35, 'sdf'),
(36, 'eeee'),
(37, 'ikik'),
(38, 'gggggg'),
(39, 'shw'),
(40, '7'),
(41, '5'),
(42, 'tg'),
(43, 'uu'),
(44, 'f'),
(45, 'sed'),
(46, 'tttt'),
(47, 'yt'),
(48, 'sadf'),
(49, '54'),
(50, 'ki'),
(51, 'rt'),
(52, 'ik'),
(53, 'kk'),
(54, 'fr'),
(55, 'wer'),
(56, 'ioi'),
(57, 'ty'),
(81, 'shw'),
(68, 'we'),
(67, 'shw'),
(73, 'sdf'),
(72, 'hj'),
(71, 'gggg'),
(70, 'fgh'),
(69, 'fg'),
(80, 'e'),
(79, 'shw'),
(77, 's'),
(78, 's'),
(82, 'shw'),
(83, 'shw'),
(84, 'shw'),
(85, 'shw'),
(86, 'yyyyyy'),
(87, 'rrrr'),
(88, 'max'),
(90, 'maz'),
(93, 's'),
(94, 'sdudd');

-- --------------------------------------------------------

--
-- Table structure for table `miscellaneous`
--

CREATE TABLE IF NOT EXISTS `miscellaneous` (
  `updateable_days` int(2) DEFAULT '7',
  `paid_leaves_allowed` int(2) NOT NULL DEFAULT '1',
  `paid_leaves_carry_forward` varchar(3) NOT NULL DEFAULT 'no',
  `salary_paid_way` varchar(10) NOT NULL DEFAULT 'monthly' COMMENT 'biweekly or monthly',
  `holidays` varchar(100) NOT NULL DEFAULT '{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":1}',
  `days_deducted_for_absent` float NOT NULL,
  `start_day` varchar(10) NOT NULL DEFAULT '01-01-2012',
  `start_date` date NOT NULL,
  `end_day` varchar(10) NOT NULL,
  `end_date` date NOT NULL,
  `start_of_the_week` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `miscellaneous`
--

INSERT INTO `miscellaneous` (`updateable_days`, `paid_leaves_allowed`, `paid_leaves_carry_forward`, `salary_paid_way`, `holidays`, `days_deducted_for_absent`, `start_day`, `start_date`, `end_day`, `end_date`, `start_of_the_week`) VALUES
(7, 0, 'yes', 'biweekly', '{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":1}', 0, '02-01-2012', '0000-00-00', '', '0000-00-00', 'mon');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_plan`
--

CREATE TABLE IF NOT EXISTS `monthly_plan` (
  `monthly_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_plan_id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `working_days` int(11) NOT NULL,
  PRIMARY KEY (`monthly_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `monthly_plan`
--

INSERT INTO `monthly_plan` (`monthly_plan_id`, `leave_plan_id`, `month`, `working_days`) VALUES
(1, 0, '', 0),
(2, 14, 'Jan', 22),
(3, 14, 'Feb', 2),
(4, 14, 'Mar', 22),
(5, 14, 'Apr', 2),
(6, 14, 'May', 22),
(7, 14, 'Jun', 22),
(8, 14, 'July', 22),
(9, 14, 'Aug', 2),
(10, 14, 'Sep', 2),
(11, 14, 'Oct', 2),
(12, 14, 'Nov', 2),
(13, 14, 'Dec', 22),
(14, 17, 'Jan', 22),
(15, 17, 'Feb', 22),
(16, 17, 'Mar', 2),
(17, 17, 'Apr', 22),
(18, 17, 'May', 2),
(19, 17, 'Jun', 2),
(20, 17, 'July', 22),
(21, 17, 'Aug', 22),
(22, 17, 'Sep', 22),
(23, 17, 'Oct', 2),
(24, 17, 'Nov', 2),
(25, 17, 'Dec', 2);

-- --------------------------------------------------------

--
-- Table structure for table `salary_plan`
--

CREATE TABLE IF NOT EXISTS `salary_plan` (
  `salary_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_plan_name` varchar(150) NOT NULL,
  `salary_plan_description` varchar(255) NOT NULL,
  PRIMARY KEY (`salary_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `salary_plan`
--

INSERT INTO `salary_plan` (`salary_plan_id`, `salary_plan_name`, `salary_plan_description`) VALUES
(1, 'salary above 10000', 'plan for 10000 abve salary'),
(19, 'Shw', 'Noting '),
(3, 'default', 'default plan govt...'),
(4, 'default', 'default plan govt...'),
(5, 'default', 'default plan govt...'),
(6, 'sdf', 'Enter description...! '),
(20, 'asdf', 'Enter description...! ');

-- --------------------------------------------------------

--
-- Table structure for table `salary_transfer_log`
--

CREATE TABLE IF NOT EXISTS `salary_transfer_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(15) NOT NULL,
  `credit_period` varchar(30) NOT NULL COMMENT 'like from 1-09-2012 to 14-09-2012',
  `review_period_lasts` date NOT NULL COMMENT '//end date of review period',
  `approval_date` date NOT NULL,
  `approx_amount` int(10) NOT NULL DEFAULT '0',
  `actual_amount` int(10) NOT NULL COMMENT '//given by admin',
  `salary_status` int(3) NOT NULL COMMENT '// 1 for approve,0 for review,-1 for pending for ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `short_break`
--

CREATE TABLE IF NOT EXISTS `short_break` (
  `short_break_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(30) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `totaltime` varchar(30) NOT NULL,
  `status` int(11) NOT NULL,
  `date` varchar(30) NOT NULL,
  `other_reason` varchar(60) NOT NULL,
  PRIMARY KEY (`short_break_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=338 ;

--
-- Dumping data for table `short_break`
--

INSERT INTO `short_break` (`short_break_id`, `emp_id`, `reason_id`, `starttime`, `endtime`, `totaltime`, `status`, `date`, `other_reason`) VALUES
(37, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 10:59:19', '0000-00-00 00:00:00', '', 2, '03-12-2012', ''),
(38, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 11:00:21', '2012-12-03 11:00:26', '', 2, '03-12-2012', ''),
(39, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 11:01:13', '2012-12-03 11:59:51', '', 2, '03-12-2012', ''),
(40, '50b07d9067f8e13516aa9454', 0, '2012-12-03 11:07:35', '2012-12-03 11:57:58', '', 2, '03-12-2012', ''),
(41, '4ff6bf9746923e9e3a000022', 0, '2012-12-03 12:04:24', '2012-12-03 12:05:29', '', 2, '03-12-2012', ''),
(42, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 12:09:44', '2012-12-03 12:10:28', '', 2, '03-12-2012', ''),
(43, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 12:47:01', '2012-12-03 17:04:58', '', 2, '03-12-2012', ''),
(44, '50b07d9067f8e13516aa9454', 0, '2012-12-03 14:53:37', '2012-12-06 14:20:40', '', 2, '03-12-2012', ''),
(45, '50b07d9067f8e13516aa9454', 0, '2012-12-03 14:53:37', '2012-12-06 14:20:40', '', 2, '03-12-2012', ''),
(46, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 17:06:28', '2012-12-03 17:07:38', '', 2, '03-12-2012', ''),
(47, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 17:08:09', '2012-12-03 17:29:10', '', 2, '03-12-2012', ''),
(48, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 17:41:56', '2012-12-03 18:34:57', '', 2, '03-12-2012', ''),
(49, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 17:41:56', '2012-12-03 18:34:57', '', 2, '03-12-2012', ''),
(50, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 18:34:56', '2012-12-03 18:34:57', '', 2, '03-12-2012', ''),
(51, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 18:35:07', '2012-12-03 18:35:13', '', 2, '03-12-2012', ''),
(52, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 18:35:16', '2012-12-03 18:35:18', '', 2, '03-12-2012', ''),
(53, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 18:36:12', '2012-12-03 18:37:28', '', 2, '03-12-2012', ''),
(54, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 18:37:41', '2012-12-03 18:38:09', '', 2, '03-12-2012', ''),
(55, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-03 18:38:26', '2012-12-04 12:23:24', '', 2, '03-12-2012', ''),
(56, '50b8580167f8561ea3ddf9ac', 0, '2012-12-03 19:25:10', '2012-12-03 19:25:18', '', 2, '03-12-2012', ''),
(57, '50b8580167f8561ea3ddf9ac', 0, '2012-12-03 19:25:25', '2012-12-03 19:25:31', '', 2, '03-12-2012', ''),
(58, '50b8580167f8561ea3ddf9ac', 0, '2012-12-03 19:25:47', '2012-12-03 19:25:54', '', 2, '03-12-2012', ''),
(59, '50b8580167f8561ea3ddf9ac', 0, '2012-12-03 19:26:34', '2012-12-03 19:26:41', '', 2, '03-12-2012', ''),
(60, '50b8580167f8561ea3ddf9ac', 0, '2012-12-03 19:27:29', '2012-12-03 19:27:34', '', 2, '03-12-2012', ''),
(61, '50b8580167f8561ea3ddf9ac', 0, '2012-12-03 19:28:00', '2012-12-06 16:13:14', '', 2, '03-12-2012', ''),
(62, '4ff6bf9746923e9e3a000022', 0, '2012-12-03 19:35:50', '2012-12-03 19:36:28', '', 2, '03-12-2012', ''),
(63, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-04 12:26:37', '2012-12-04 12:26:41', '', 2, '04-12-2012', ''),
(64, '5099f0d967f8d09ba01bbb1d', 2, '2012-12-04 12:27:43', '2012-12-04 12:27:49', '', 2, '04-12-2012', ''),
(65, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 12:28:07', '2012-12-04 12:28:11', '', 2, '04-12-2012', ''),
(66, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 12:48:26', '2012-12-04 12:48:28', '', 2, '04-12-2012', 'sssss'),
(67, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 14:27:39', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(68, '50af175046923e447f00000f', 1, '2012-12-04 15:04:09', '2012-12-04 15:04:12', '', 2, '04-12-2012', ''),
(69, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 15:08:40', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(70, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 15:08:40', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(71, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 15:08:40', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(72, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 15:08:40', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(73, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 15:08:40', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(74, '50af175046923e447f00000f', 0, '2012-12-04 16:01:29', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(75, '50af175046923e447f00000f', 0, '2012-12-04 16:07:32', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(76, '50af175046923e447f00000f', 0, '2012-12-04 16:12:02', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(77, '50af175046923e447f00000f', 0, '2012-12-04 16:13:25', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(78, '50af175046923e447f00000f', 0, '2012-12-04 16:14:17', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(79, '50af175046923e447f00000f', 0, '2012-12-04 16:22:30', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(80, '50af175046923e447f00000f', 0, '2012-12-04 16:39:03', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(81, '50af175046923e447f00000f', 0, '2012-12-04 16:39:34', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(82, '50af175046923e447f00000f', 0, '2012-12-04 16:52:17', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(83, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 17:55:40', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(84, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 17:58:06', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(85, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 17:58:06', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(86, '50af175046923e447f00000f', 0, '2012-12-04 18:02:44', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(87, '4ff6bf9746923e9e3a000022', 1, '2012-12-04 18:06:48', '2012-12-04 20:07:19', '', 2, '04-12-2012', ''),
(88, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-04 18:10:56', '2012-12-04 19:07:46', '', 2, '04-12-2012', ''),
(89, '50af175046923e447f00000f', 0, '2012-12-04 18:22:20', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(90, '50af175046923e447f00000f', 0, '2012-12-04 18:25:19', '2012-12-04 18:25:36', '', 2, '04-12-2012', ''),
(91, '50af175046923e447f00000f', 0, '2012-12-04 18:25:48', '2012-12-04 18:26:10', '', 2, '04-12-2012', ''),
(92, '50af175046923e447f00000f', 0, '2012-12-04 18:25:58', '2012-12-04 18:26:10', '', 2, '04-12-2012', ''),
(93, '50af175046923e447f00000f', 0, '2012-12-04 18:26:07', '2012-12-04 18:26:10', '', 2, '04-12-2012', ''),
(94, '50af175046923e447f00000f', 0, '2012-12-04 18:26:31', '2012-12-04 18:40:24', '', 2, '04-12-2012', ''),
(95, '50af175046923e447f00000f', 0, '2012-12-04 18:39:34', '2012-12-04 18:40:24', '', 2, '04-12-2012', ''),
(96, '50af175046923e447f00000f', 0, '2012-12-04 18:39:58', '2012-12-04 18:40:24', '', 2, '04-12-2012', ''),
(97, '50af175046923e447f00000f', 0, '2012-12-04 18:40:17', '2012-12-04 18:40:24', '', 2, '04-12-2012', ''),
(98, '50af175046923e447f00000f', 0, '2012-12-04 18:40:33', '2012-12-04 18:49:51', '', 2, '04-12-2012', ''),
(99, '50af175046923e447f00000f', 0, '2012-12-04 18:56:16', '2012-12-04 18:57:24', '', 2, '04-12-2012', ''),
(100, '50af175046923e447f00000f', 0, '2012-12-04 18:57:33', '2012-12-04 19:00:25', '', 2, '04-12-2012', ''),
(101, '50af175046923e447f00000f', 0, '2012-12-04 19:13:58', '2012-12-04 19:15:11', '', 2, '04-12-2012', ''),
(102, '50af175046923e447f00000f', 1, '2012-12-04 19:44:57', '2012-12-04 19:47:07', '', 2, '04-12-2012', ''),
(103, '50af175046923e447f00000f', 1, '2012-12-04 19:47:55', '2012-12-04 19:49:27', '', 2, '04-12-2012', ''),
(104, '50af175046923e447f00000f', 1, '2012-12-04 20:05:56', '2012-12-04 20:07:07', '', 2, '04-12-2012', ''),
(105, '50af175046923e447f00000f', 1, '2012-12-04 20:08:32', '2012-12-04 20:08:35', '', 2, '04-12-2012', ''),
(106, '50af175046923e447f00000f', 2, '2012-12-04 20:09:18', '2012-12-04 20:09:29', '', 2, '04-12-2012', ''),
(107, '50af175046923e447f00000f', 1, '2012-12-04 20:10:23', '2012-12-04 20:10:34', '', 2, '04-12-2012', ''),
(108, '50af175046923e447f00000f', 1, '2012-12-04 20:12:44', '2012-12-04 20:14:43', '', 2, '04-12-2012', ''),
(109, '50af175046923e447f00000f', 2, '2012-12-04 20:16:03', '2012-12-04 20:16:10', '', 2, '04-12-2012', ''),
(110, '50af175046923e447f00000f', 2, '2012-12-04 20:16:52', '2012-12-04 20:16:55', '', 2, '04-12-2012', ''),
(111, '50af175046923e447f00000f', 1, '2012-12-04 20:18:45', '2012-12-04 20:18:50', '', 2, '04-12-2012', ''),
(112, '50af175046923e447f00000f', 1, '2012-12-04 20:19:00', '2012-12-04 20:19:15', '', 2, '04-12-2012', ''),
(113, '50af175046923e447f00000f', 2, '2012-12-04 20:19:52', '2012-12-04 20:19:58', '', 2, '04-12-2012', ''),
(114, '50af175046923e447f00000f', 2, '2012-12-04 20:22:14', '2012-12-04 20:22:25', '', 2, '04-12-2012', ''),
(115, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 09:25:00', '2012-12-05 09:31:16', '', 2, '05-12-2012', ''),
(116, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 09:46:20', '2012-12-05 10:18:47', '', 2, '05-12-2012', ''),
(117, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 09:46:20', '2012-12-05 10:18:47', '', 2, '05-12-2012', ''),
(118, '4ff6bf9746923e9e3a000022', 2, '2012-12-05 12:23:24', '2012-12-05 12:23:58', '', 2, '05-12-2012', ''),
(119, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 12:27:20', '2012-12-05 12:28:16', '', 2, '05-12-2012', ''),
(120, '4ff6bf9746923e9e3a000022', 1, '2012-12-05 12:34:04', '2012-12-05 12:46:33', '', 2, '05-12-2012', ''),
(121, '4ff6bf9746923e9e3a000022', 1, '2012-12-05 12:49:38', '2012-12-05 12:49:42', '', 2, '05-12-2012', ''),
(122, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 16:04:22', '2012-12-05 16:04:31', '', 2, '05-12-2012', ''),
(123, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 16:04:22', '2012-12-05 16:04:31', '', 2, '05-12-2012', ''),
(124, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 16:04:25', '2012-12-05 16:04:31', '', 2, '05-12-2012', ''),
(125, '4ff6bf9746923e9e3a000022', 1, '2012-12-05 17:46:40', '2012-12-05 17:46:42', '', 2, '05-12-2012', ''),
(126, '4ff6bf9746923e9e3a000022', 1, '2012-12-05 17:46:56', '2012-12-05 17:47:17', '', 2, '05-12-2012', ''),
(127, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-05 17:54:48', '2012-12-05 17:55:00', '', 2, '05-12-2012', ''),
(128, '4ff6bf9746923e9e3a000022', 1, '2012-12-05 19:34:57', '2012-12-05 19:35:01', '', 2, '05-12-2012', ''),
(129, '4ff6bf9746923e9e3a000022', 1, '2012-12-05 19:40:23', '2012-12-05 19:40:36', '', 2, '05-12-2012', ''),
(130, '4ff6bf9746923e9e3a000022', 0, '2012-12-05 19:42:26', '2012-12-05 19:51:17', '', 2, '05-12-2012', ''),
(131, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 09:49:57', '2012-12-06 09:50:19', '', 2, '06-12-2012', ''),
(132, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 09:53:38', '2012-12-06 10:03:31', '', 2, '06-12-2012', ''),
(133, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 09:53:38', '2012-12-06 10:03:31', '', 2, '06-12-2012', ''),
(134, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 09:53:38', '2012-12-06 10:03:31', '', 2, '06-12-2012', ''),
(135, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 10:28:16', '2012-12-06 10:28:51', '', 2, '06-12-2012', ''),
(136, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 10:32:43', '2012-12-06 10:32:48', '', 2, '06-12-2012', ''),
(137, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 11:09:35', '2012-12-06 11:09:37', '', 2, '06-12-2012', ''),
(138, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 11:13:50', '2012-12-06 11:14:05', '', 2, '06-12-2012', ''),
(139, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 11:14:25', '2012-12-06 11:20:37', '', 2, '06-12-2012', ''),
(140, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 11:19:51', '2012-12-06 11:47:01', '', 2, '06-12-2012', ''),
(141, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 11:47:35', '2012-12-06 11:48:55', '', 2, '06-12-2012', ''),
(142, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 11:49:02', '2012-12-06 11:49:15', '', 2, '06-12-2012', ''),
(143, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 12:10:40', '2012-12-06 13:06:09', '', 2, '06-12-2012', ''),
(144, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 12:11:20', '2012-12-06 12:12:50', '', 2, '06-12-2012', ''),
(145, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 12:13:56', '2012-12-06 12:15:11', '', 2, '06-12-2012', ''),
(146, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 12:15:28', '2012-12-06 12:15:37', '', 2, '06-12-2012', ''),
(147, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 12:22:21', '2012-12-06 12:22:27', '', 2, '06-12-2012', 'test'),
(148, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 12:48:41', '2012-12-06 12:51:53', '', 2, '06-12-2012', ''),
(149, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 12:51:49', '2012-12-06 12:51:53', '', 2, '06-12-2012', ''),
(150, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 12:52:26', '2012-12-06 14:02:06', '', 2, '06-12-2012', ''),
(151, '5099f0d967f8d09ba01bbb1d', 2, '2012-12-06 13:06:27', '2012-12-06 13:08:32', '', 2, '06-12-2012', ''),
(152, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 13:08:39', '2012-12-06 13:10:30', '', 2, '06-12-2012', ''),
(153, '5099f0d967f8d09ba01bbb1d', 2, '2012-12-06 13:10:38', '2012-12-06 14:32:32', '', 2, '06-12-2012', ''),
(154, '50b07d9067f8e13516aa9454', 1, '2012-12-06 13:13:50', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(155, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 14:04:06', '2012-12-06 14:06:45', '', 2, '06-12-2012', ''),
(156, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 14:14:07', '2012-12-06 14:14:43', '', 2, '06-12-2012', ''),
(157, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 14:16:23', '2012-12-06 14:19:30', '', 2, '06-12-2012', 'test'),
(158, '4ff6bf9746923e9e3a000022', 2, '2012-12-06 14:17:29', '2012-12-06 14:19:30', '', 2, '06-12-2012', 'test'),
(159, '4ff6bf9746923e9e3a000022', 2, '2012-12-06 14:17:33', '2012-12-06 14:19:30', '', 2, '06-12-2012', 'test'),
(160, '4ff6bf9746923e9e3a000022', 4, '2012-12-06 14:17:36', '2012-12-06 14:19:30', '', 2, '06-12-2012', 'test'),
(161, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:49', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(162, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:50', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(163, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:50', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(164, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:50', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(165, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:51', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(166, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:51', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(167, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:51', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(168, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:52', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(169, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:52', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(170, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:52', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(171, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:18:53', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(172, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:18:53', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(173, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:53', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(174, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:54', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(175, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:54', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(176, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:55', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(177, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:55', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(178, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:55', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(179, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:55', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(180, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:55', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(181, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:56', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(182, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:56', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(183, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:56', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(184, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:18:56', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(185, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:57', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(186, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:57', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(187, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:57', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(188, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:57', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(189, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:57', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(190, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:58', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(191, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:18:58', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(192, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:58', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(193, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:58', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(194, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:18:59', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(195, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:18:59', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(196, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:18:59', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(197, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:00', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(198, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:00', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(199, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:01', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(200, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:19:01', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(201, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:01', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(202, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:01', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(203, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:02', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(204, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:02', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(205, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:02', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(206, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:19:03', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(207, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:19:03', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(208, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:03', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(209, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:03', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(210, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:04', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(211, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:04', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(212, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:04', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(213, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:04', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(214, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:05', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(215, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:05', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(216, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:19:05', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(217, '50b07d9067f8e13516aa9454', 1, '2012-12-06 14:19:05', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(218, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:06', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(219, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:06', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(220, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:07', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(221, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:07', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(222, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:07', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(223, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:08', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(224, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:08', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(225, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:08', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(226, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:09', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(227, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:09', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(228, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:09', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(229, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:09', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(230, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:10', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(231, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:10', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(232, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:10', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(233, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:11', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(234, '50b07d9067f8e13516aa9454', 5, '2012-12-06 14:19:11', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(235, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:11', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(236, '50b07d9067f8e13516aa9454', 4, '2012-12-06 14:19:12', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(237, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:12', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(238, '50b07d9067f8e13516aa9454', 2, '2012-12-06 14:19:12', '2012-12-06 14:20:40', '', 2, '06-12-2012', ''),
(239, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-06 14:31:27', '2012-12-06 14:32:32', '', 2, '06-12-2012', 'sdf'),
(240, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:18:11', '2012-12-06 15:18:48', '', 2, '06-12-2012', ''),
(241, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:33:31', '2012-12-06 15:34:24', '', 2, '06-12-2012', 'Tea'),
(242, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:34:36', '2012-12-06 15:35:09', '', 2, '06-12-2012', 'Tea'),
(243, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:35:24', '2012-12-06 15:35:29', '', 2, '06-12-2012', 'tea'),
(244, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:36:31', '2012-12-06 15:36:47', '', 2, '06-12-2012', 'test'),
(245, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:36:58', '2012-12-06 15:37:02', '', 2, '06-12-2012', ''),
(246, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:37:14', '2012-12-06 15:37:17', '', 2, '06-12-2012', 'asdfasdfasdf'),
(247, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:37:31', '2012-12-06 15:38:51', '', 2, '06-12-2012', 'hello'),
(248, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:39:58', '2012-12-06 15:39:59', '', 2, '06-12-2012', ''),
(249, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:40:48', '2012-12-06 15:40:49', '', 2, '06-12-2012', 'dfgsdfg'),
(250, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:41:16', '2012-12-06 15:41:17', '', 2, '06-12-2012', 'dfgsdfg'),
(251, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:43:36', '2012-12-06 15:43:38', '', 2, '06-12-2012', ''),
(252, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:43:45', '2012-12-06 15:43:49', '', 2, '06-12-2012', ''),
(253, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:44:06', '2012-12-06 15:44:09', '', 2, '06-12-2012', 'dfgsdfg'),
(254, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:45:04', '2012-12-06 15:45:06', '', 2, '06-12-2012', ''),
(255, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:45:12', '2012-12-06 15:45:14', '', 2, '06-12-2012', 'rgdsfg'),
(256, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:45:16', '2012-12-06 15:45:17', '', 2, '06-12-2012', 'rgdsfg'),
(257, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:45:18', '2012-12-06 15:45:21', '', 2, '06-12-2012', 'rgdsfg'),
(258, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:45:51', '2012-12-06 15:45:54', '', 2, '06-12-2012', 'regsdfg'),
(259, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:45:59', '2012-12-06 15:46:01', '', 2, '06-12-2012', 'gffdghdh'),
(260, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:46:03', '2012-12-06 15:46:04', '', 2, '06-12-2012', ''),
(261, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:47:18', '2012-12-06 15:48:46', '', 2, '06-12-2012', ''),
(262, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:48:50', '2012-12-06 15:48:54', '', 2, '06-12-2012', ''),
(263, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:48:59', '2012-12-06 15:49:12', '', 2, '06-12-2012', 'fgsdfg'),
(264, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:49:30', '2012-12-06 15:49:34', '', 2, '06-12-2012', ''),
(265, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:49:39', '2012-12-06 15:49:42', '', 2, '06-12-2012', ''),
(266, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:49:45', '2012-12-06 15:49:47', '', 2, '06-12-2012', ''),
(267, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:49:48', '2012-12-06 15:49:50', '', 2, '06-12-2012', ''),
(268, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:49:54', '2012-12-06 15:49:56', '', 2, '06-12-2012', 'asdfsd'),
(269, '4ff6bf9746923e9e3a000022', 2, '2012-12-06 15:49:58', '2012-12-06 15:50:04', '', 2, '06-12-2012', ''),
(270, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:50:08', '2012-12-06 15:50:17', '', 2, '06-12-2012', 'asdfdf'),
(271, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:50:20', '2012-12-06 15:50:21', '', 2, '06-12-2012', ''),
(272, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:50:42', '2012-12-06 15:50:43', '', 2, '06-12-2012', 'asdfasd'),
(273, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:50:46', '2012-12-06 15:50:48', '', 2, '06-12-2012', 'asdf'),
(274, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:50:53', '2012-12-06 15:55:06', '', 2, '06-12-2012', ''),
(275, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:55:10', '2012-12-06 15:55:12', '', 2, '06-12-2012', ''),
(276, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:55:16', '2012-12-06 15:55:27', '', 2, '06-12-2012', ''),
(277, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:55:30', '2012-12-06 15:55:38', '', 2, '06-12-2012', ''),
(278, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:55:44', '2012-12-06 15:55:59', '', 2, '06-12-2012', ''),
(279, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:56:01', '2012-12-06 15:56:03', '', 2, '06-12-2012', ''),
(280, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:56:04', '2012-12-06 15:56:06', '', 2, '06-12-2012', ''),
(281, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:56:07', '2012-12-06 15:56:09', '', 2, '06-12-2012', ''),
(282, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:56:22', '2012-12-06 15:56:23', '', 2, '06-12-2012', ''),
(283, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:56:24', '2012-12-06 15:56:26', '', 2, '06-12-2012', ''),
(284, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 15:56:30', '2012-12-06 15:56:32', '', 2, '06-12-2012', 'sdafasdf'),
(285, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:56:45', '2012-12-06 15:57:48', '', 2, '06-12-2012', ''),
(286, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:57:50', '2012-12-06 15:58:01', '', 2, '06-12-2012', ''),
(287, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:58:03', '2012-12-06 15:58:51', '', 2, '06-12-2012', ''),
(288, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:58:53', '2012-12-06 15:58:59', '', 2, '06-12-2012', ''),
(289, '4ff6bf9746923e9e3a000022', 2, '2012-12-06 15:59:01', '2012-12-06 15:59:02', '', 2, '06-12-2012', ''),
(290, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:59:06', '2012-12-06 15:59:08', '', 2, '06-12-2012', ''),
(291, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:59:10', '2012-12-06 15:59:15', '', 2, '06-12-2012', ''),
(292, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 15:59:18', '2012-12-06 15:59:18', '', 2, '06-12-2012', ''),
(293, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 15:59:48', '2012-12-06 16:00:52', '', 2, '06-12-2012', ''),
(294, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:01:01', '2012-12-06 16:01:24', '', 2, '06-12-2012', ''),
(295, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 16:04:25', '2012-12-06 16:04:31', '', 2, '06-12-2012', 'cxvzxcv'),
(296, '4ff6bf9746923e9e3a000022', 0, '2012-12-06 16:04:37', '2012-12-06 16:04:38', '', 2, '06-12-2012', 'fgvh'),
(297, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 16:04:40', '2012-12-06 16:04:40', '', 2, '06-12-2012', ''),
(298, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:08:03', '2012-12-06 16:08:12', '', 2, '06-12-2012', ''),
(299, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:08:14', '2012-12-06 16:09:37', '', 2, '06-12-2012', ''),
(300, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 16:08:40', '2012-12-06 16:09:21', '', 2, '06-12-2012', ''),
(301, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:09:44', '2012-12-06 16:15:13', '', 2, '06-12-2012', ''),
(302, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 16:11:02', '2012-12-06 16:11:33', '', 2, '06-12-2012', ''),
(303, '4ff6bf9746923e9e3a000022', 1, '2012-12-06 16:12:47', '2012-12-06 16:17:48', '', 2, '06-12-2012', ''),
(304, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:15:24', '2012-12-06 16:19:56', '', 2, '06-12-2012', ''),
(305, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:20:07', '2012-12-06 16:22:56', '', 2, '06-12-2012', ''),
(306, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:23:02', '2012-12-06 16:24:31', '', 2, '06-12-2012', ''),
(307, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:24:33', '2012-12-06 16:25:15', '', 2, '06-12-2012', ''),
(308, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:27:50', '2012-12-06 16:35:17', '', 2, '06-12-2012', ''),
(309, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:35:30', '2012-12-06 16:35:38', '', 2, '06-12-2012', ''),
(310, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:35:39', '2012-12-06 16:35:42', '', 2, '06-12-2012', ''),
(311, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:35:44', '2012-12-06 16:36:47', '', 2, '06-12-2012', ''),
(312, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-06 16:36:49', '2012-12-06 16:42:51', '', 2, '06-12-2012', ''),
(313, '5099f0d967f8d09ba01bbb1d', 5, '2012-12-07 10:52:03', '2012-12-07 10:52:10', '', 2, '07-12-2012', ''),
(314, '5099f0d967f8d09ba01bbb1d', 5, '2012-12-07 11:02:34', '2012-12-07 11:02:42', '', 2, '07-12-2012', ''),
(315, '5099f0d967f8d09ba01bbb1d', 4, '2012-12-07 11:02:56', '2012-12-07 11:03:00', '', 2, '07-12-2012', ''),
(316, '5099f0d967f8d09ba01bbb1d', 5, '2012-12-07 11:03:02', '2012-12-07 11:03:07', '', 2, '07-12-2012', ''),
(317, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-07 11:03:11', '2012-12-07 11:03:34', '', 2, '07-12-2012', ''),
(318, '5099f0d967f8d09ba01bbb1d', 4, '2012-12-07 18:06:21', '2012-12-07 18:06:54', '', 2, '07-12-2012', ''),
(319, '4ff6bf9746923e9e3a000022', 1, '2012-12-07 18:46:10', '2012-12-07 18:46:13', '', 2, '07-12-2012', ''),
(320, '50b07d9067f8e13516aa9454', 26, '2012-12-10 12:28:37', '0000-00-00 00:00:00', '', 1, '10-12-2012', ''),
(321, '5099f0d967f8d09ba01bbb1d', 2, '2012-12-10 18:36:24', '2012-12-11 09:51:41', '', 2, '10-12-2012', ''),
(322, '5099f0d967f8d09ba01bbb1d', 2, '2012-12-12 13:02:35', '2012-12-12 13:02:44', '', 2, '12-12-2012', ''),
(323, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-12 15:29:46', '2012-12-12 15:30:58', '', 2, '12-12-2012', 'personal'),
(324, '5099f0d967f8d09ba01bbb1d', 0, '2012-12-12 19:33:11', '2012-12-12 19:35:26', '', 2, '12-12-2012', '52125'),
(325, '5099f0d967f8d09ba01bbb1d', 4, '2012-12-13 17:25:18', '2012-12-13 17:25:21', '', 2, '13-12-2012', ''),
(326, '4ff6bf2b46923ed339000005', 2, '2012-12-14 19:34:46', '2012-12-14 19:34:48', '', 2, '14-12-2012', ''),
(327, '4ff6bf2b46923ed339000005', 0, '2012-12-14 19:34:55', '2012-12-14 19:34:57', '', 2, '14-12-2012', 'Â '),
(328, '4ff6bf2b46923ed339000005', 4, '2012-12-14 19:34:59', '2012-12-14 19:35:00', '', 2, '14-12-2012', ''),
(329, '4ff6bf2b46923ed339000005', 0, '2012-12-14 19:35:09', '2012-12-14 19:35:11', '', 2, '14-12-2012', 'xxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxX'),
(330, '4ff6bf2b46923ed339000005', 0, '2012-12-14 19:35:14', '2012-12-14 19:35:16', '', 2, '14-12-2012', 'xxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxXxxX'),
(331, '50892e1f67f89f72f2dcb92e', 1, '2012-12-17 17:49:34', '2012-12-17 17:49:42', '', 2, '17-12-2012', ''),
(332, '5099f0d967f8d09ba01bbb1d', 4, '2012-12-19 09:06:15', '2012-12-19 09:06:21', '', 2, '19-12-2012', ''),
(333, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-19 09:06:35', '2012-12-19 09:06:45', '', 2, '19-12-2012', ''),
(334, '5099f0d967f8d09ba01bbb1d', 2, '2012-12-19 09:07:37', '2012-12-19 09:07:38', '', 2, '19-12-2012', ''),
(335, '5099f0d967f8d09ba01bbb1d', 1, '2012-12-19 09:08:03', '2012-12-19 09:08:18', '', 2, '19-12-2012', ''),
(336, '5099f0d967f8d09ba01bbb1d', 4, '2012-12-19 09:08:34', '2012-12-19 09:08:56', '', 2, '19-12-2012', ''),
(337, '4ff6bf9746923e9e3a000022', 1, '2012-12-19 11:41:37', '2012-12-19 11:41:41', '', 2, '19-12-2012', '');

-- --------------------------------------------------------

--
-- Table structure for table `short_break_reason`
--

CREATE TABLE IF NOT EXISTS `short_break_reason` (
  `s_b_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_b_reason` varchar(255) NOT NULL,
  `s_b_time` varchar(50) NOT NULL,
  PRIMARY KEY (`s_b_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `short_break_reason`
--

INSERT INTO `short_break_reason` (`s_b_id`, `s_b_reason`, `s_b_time`) VALUES
(1, 'tea', '10'),
(2, 'lunch', '20'),
(4, 'time', '');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_plan`
--

CREATE TABLE IF NOT EXISTS `weekly_plan` (
  `weekly_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_plan_id` int(11) NOT NULL,
  `week_days` varchar(150) NOT NULL,
  PRIMARY KEY (`weekly_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `weekly_plan`
--

INSERT INTO `weekly_plan` (`weekly_plan_id`, `leave_plan_id`, `week_days`) VALUES
(1, 18, '{"1":0,"2":0,"3":0,"4":0,"5":0,"6":1,"7":1}'),
(2, 19, '{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":1}'),
(3, 20, '{"1":0,"2":0,"3":0,"4":0,"5":0,"6":1,"7":1}'),
(4, 21, '{"1":0,"2":1,"3":0,"4":1,"5":0,"6":1,"7":1}'),
(5, 22, '{"1":1,"2":1,"3":1,"4":1,"5":1,"6":1,"7":1}'),
(6, 23, '{"1":1,"2":1,"3":1,"4":1,"5":1,"6":1,"7":1}'),
(7, 24, '{"1":1,"2":1,"3":1,"4":1,"5":1,"6":1,"7":1}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
