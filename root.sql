-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2019 at 05:00 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `root`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `FIRSTNAME` varchar(30) NOT NULL,
  `LASTNAME` varchar(30) NOT NULL,
  `USERNAME` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `PASSWORD` varchar(35) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `EMAIL` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `ABOUT` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`FIRSTNAME`, `LASTNAME`, `USERNAME`, `PASSWORD`, `EMAIL`, `ABOUT`) VALUES
('Devansh', 'Singhal', 'devansh5398', 'c46e3bd92568c414fcd6aa6fbf53bca5', 'devansh5398@gmail.com', 'host'),
('Praveen', 'Kumar', 'praveen.chicku', '5519a0f4afad267617c91cb946276589', 'praveen.chicku4895@gmail.com', 'Bachelor of Technology in Mining Engineering'),
('Reena', '', 'reena12345', '7656bc20abadc355d865dbef1096a4da', 'reena12345@gmail.com', 'host\'s MOM');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `FILENAME` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `SUBJECT` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `ADMIN` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `UPLOADTIME` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`FILENAME`, `SUBJECT`, `ADMIN`, `UPLOADTIME`) VALUES
('./uploadedNotices/39e803efa390bd41c9a90e8f02c6cf29.txt', 'Dummy notice', 'reena12345', '0000-00-00 00:00:00'),
('./uploadedNotices/6f7cda57a3bea010cb9e65839cae8c01.txt', 'Dummy notice', 'reena12345', '0000-00-00 00:00:00'),
('./uploadedNotices/62d3e63206efcbb3e0a731ec8e487d64.txt', 'Dummy notice 3', 'reena12345', '2019-07-22 18:30:00'),
('./uploadedNotices/7a259cf08e9acbb9dc4e93d2200a18c9.txt', 'Dummy notice 4', 'reena12345', '2019-07-22 18:30:00'),
('./uploadedNotices/21c109cc9a7d8540a563b517893ac2e2.txt', 'Dummy notice 5', 'reena12345', '2019-07-22 18:30:00'),
('./uploadedNotices/7782ced877582d217b057e3d9fc8653a.txt', 'Dummy notice 9', 'reena12345', '2019-07-22 18:30:00'),
('./uploadedNotices/c3dcb2fe5e833b7f9500a1be45b83032.txt', 'Dummy notice 10', 'reena12345', '2019-07-22 18:30:00'),
('./uploadedNotices/de7aea05e87e7d8d0c7c66f83aafa8d7.txt', 'Dummy notice 8', 'reena12345', '2019-07-22 20:21:39'),
('./uploadedNotices/6d992fa25cd6387ea9c8abdc74f54c75.txt', 'Dummy notice 12', 'reena12345', '2019-07-22 23:59:32'),
('./uploadedNotices/42a7f25bc0aee0c4d9dbcf48fc25e88e.txt', 'Dummy notice 13', 'reena12345', '2019-07-23 12:00:41'),
('./uploadedNotices/22be5c716c467577317964b1ec2d6bd7.txt', 'Increase by 10', 'reena12345', '2019-07-23 19:32:59'),
('./uploadedNotices/2f4f4b3acf68ee6919507801ccaecec8.txt', '15_01_2019 ques 1', 'reena12345', '2019-07-23 19:35:14'),
('./uploadedNotices/f553ace7d3b201208a6f31c1f8f37c2b.txt', 'Bootstrap 4 Popover', 'reena12345', '2019-07-23 19:36:51'),
('./uploadedNotices/e13ed259ea4bba85b49fcbe246a74ec8.txt', 'Mera phela post', 'devansh5398', '2019-07-23 20:22:31'),
('./uploadedNotices/493c3b05e331ac8342cf28df1fcdd862.txt', 'mining', 'praveen.chicku', '2019-07-27 09:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `FIRSTNAME` varchar(30) NOT NULL,
  `LASTNAME` varchar(30) NOT NULL,
  `USERNAME` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `PASSWORD` varchar(35) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `EMAIL` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`FIRSTNAME`, `LASTNAME`, `USERNAME`, `PASSWORD`, `EMAIL`) VALUES
('Devansh', 'Singhal', '17jeoo3254', '7f7ebd1920e50e2291f9159f43d8d0c1', 'devansh53981@gmail.com'),
('Kavya', '', '17jeoo32541', '2a93c47086059dcdb8d0342023cece02', 'dev5398@gmail.com'),
('Reena', '', 'reena', '691ebffa40a1c3422ee230eb692e8bf6', 'dev98@gmail.com'),
('Kavya', 'Agarwal', 'kavya281099', 'e02b2af694073358c7ecd18c112f649a', 'kavya281099@gmail.com'),
('Reena', 'Agarwal', 'mujhe_kya_pata', 'e94bfec9163470575f6e4fe85e9911bf', 'devansh539@gmail.com'),
('Devansh', 'Singhal', '17JE003346', '014dc725f02466a2685efe17f9dc7814', 'devansh5398@gmail.com'),
('Devansh', 'Singhal', '17JE0033460', '878b5dc7dfe2a6360975c0f98ebf9eb0', 'devansh7448@gmail.com'),
('Devansh', 'Singhal', 'devansh7448', 'fcb4d13ac1cd160c39e8f4cb4eb125e9', 'devansh74481@gmail.com'),
('Praveen', 'Kumar', 'chicku', '5519a0f4afad267617c91cb946276589', 'praveen.chicku1452@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD UNIQUE KEY `USERNAME` (`USERNAME`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
