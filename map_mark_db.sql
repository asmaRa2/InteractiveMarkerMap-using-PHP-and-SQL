-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 23 يونيو 2024 الساعة 18:43
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `map_mark_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `tbl_mark`
--

CREATE TABLE `tbl_mark` (
  `tbl_mark_id` int(11) NOT NULL,
  `mark_name` varchar(255) NOT NULL,
  `mark_long` double NOT NULL,
  `mark_lat` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tbl_mark`
--

INSERT INTO `tbl_mark` (`tbl_mark_id`, `mark_name`, `mark_long`, `mark_lat`) VALUES
(1, 'Riyadh', 46.6753, 24.7136),
(2, 'Jeddah', 39.1999, 21.4858),
(3, 'Mecca', 39.8262, 21.4225),
(6, 'Medina', 39.6111, 24.5247),
(7, 'Dammam', 50.1033, 26.3927),
(8, 'Al Khobar', 50.1941, 26.2172),
(9, 'Tabuk', 36.555, 28.3838);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_mark`
--
ALTER TABLE `tbl_mark`
  ADD PRIMARY KEY (`tbl_mark_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_mark`
--
ALTER TABLE `tbl_mark`
  MODIFY `tbl_mark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
