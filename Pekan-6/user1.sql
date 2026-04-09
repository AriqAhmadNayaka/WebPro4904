-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 07:10 PM
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
-- Database: `web_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `user1`
--

CREATE TABLE `user1` (
  `id` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `notlp` varchar(12) NOT NULL,
  `jeniskelamin` varchar(10) NOT NULL,
  `tanggallahir` date NOT NULL,
  `beratbadan` varchar(3) NOT NULL,
  `tinggibadan` varchar(3) NOT NULL,
  `jeniskanker` varchar(30) NOT NULL,
  `file_pasien` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user1`
--

INSERT INTO `user1` (`id`, `nama`, `email`, `password`, `alamat`, `notlp`, `jeniskelamin`, `tanggallahir`, `beratbadan`, `tinggibadan`, `jeniskanker`, `file_pasien`) VALUES
(24, 'user', NULL, NULL, 'Indonesia', '082117325458', 'Laki-laki', '2026-04-09', '65', '160', 'kanker', NULL),
(25, 'admin', 'admin@gmail.com', 'admin123', '', '', '', '0000-00-00', '', '', '', NULL),
(26, 'user', 'user@gmail.com', 'user123', '', '', '', '0000-00-00', '', '', '', NULL),
(27, 'pengguna', 'pengguna@gmail.com', 'pengguna123', '', '', '', '0000-00-00', '', '', '', NULL),
(28, 'bilqis', 'bilqisnabilahh@gmail.com', 'abilqis', '', '', '', '0000-00-00', '', '', '', NULL),
(29, 'bilqis', 'bilqisnabilahh@gmail.com', 'abilqis', '', '', '', '0000-00-00', '', '', '', NULL),
(30, 'user', NULL, NULL, 'Indonesia', '082117325458', 'Perempuan', '2026-04-09', '54', '160', 'kanker', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user1`
--
ALTER TABLE `user1`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user1`
--
ALTER TABLE `user1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
