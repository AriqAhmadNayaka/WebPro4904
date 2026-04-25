-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 07:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smarttrash_monitor`
--
CREATE DATABASE IF NOT EXISTS `smarttrash_monitor` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `smarttrash_monitor`;

-- --------------------------------------------------------

--
-- Table structure for table `datauser`
--

CREATE TABLE `datauser` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'user',
  `Gambar` varchar(255) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datauser`
--

INSERT INTO `datauser` (`id`, `name`, `email`, `password`, `role`, `Gambar`, `Foto`) VALUES
(1, 'Pablo Morino', 'pablomorino32@gmail.com', '$2y$10$s8/Gn.ho5t7BqFfiF3mga.8PvLEeo1Bx5uSRZKxmhg2A6Y5XxkET2', 'user', '69e06f27a795b.png', NULL),
(2, 'Eykies Bintang', 'EykiesBintang@gmail.com', '$2y$10$9MMZf10EUYd3biWUHSnMzOYSMvTmT9HJQ8AZLbw8HaRFOn2xvTFUW', 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datauser`
--
ALTER TABLE `datauser`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `datauser`
--
ALTER TABLE `datauser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
