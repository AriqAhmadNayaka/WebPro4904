-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 05:10 PM
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
-- Database: `db_inkluskills_pw`
--

-- --------------------------------------------------------

--
-- Table structure for table `anak`
--

CREATE TABLE `anak` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jadwal` varchar(100) DEFAULT NULL,
  `progress` int(11) DEFAULT NULL,
  `komunikasi` int(11) DEFAULT NULL,
  `kemandirian` int(11) DEFAULT NULL,
  `vokasional` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anak`
--

INSERT INTO `anak` (`id`, `user_id`, `nama`, `jadwal`, `progress`, `komunikasi`, `kemandirian`, `vokasional`, `gender`, `tanggal_lahir`, `kelas`, `alamat`, `catatan`, `foto`) VALUES
(1, 9, 'Ujang Saepudin', '15 Des 2025', 76, 70, 85, 60, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 9, 'tatang', NULL, NULL, NULL, NULL, NULL, 'Laki-laki', '1945-08-17', '3 SD', 'Bojong Santos', '-', 'uploads/anak_69d76be28226c1.99473103.png'),
(3, 9, 'bella', NULL, NULL, NULL, NULL, NULL, 'Perempuan', '2026-04-02', '3 SD', 'Bojong Santos', '', 'uploads/anak_69d791c8af4a32.78264066.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `kegiatan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `hari`, `kegiatan`) VALUES
(1, 'Senin', 'Desain Grafis - 09.00'),
(2, 'Rabu', 'Kemandirian - 10.00'),
(3, 'Jumat', 'Barista - 13.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(6, 'Admin', 'admin@gmail.com', '@123456', 'admin'),
(7, 'admin', 'admin@gmal.com', '$2y$10$P/890nn5mfbPyUWy6.S0D.iRusqJD1zrL4v8/BvxELXV/UpGNjlGu', 'Orang Tua'),
(8, 'Milo Nestle', 'milani@gmail.com', '$2y$10$.fsgjLxlLd66IkSMzH8DH.IrE34fl7ZYuz/51DLfvOPRhxIImrmOG', 'Orang Tua'),
(9, 'sasa', 'sa@gmail.com', '$2y$10$cCyhOrWwrqxkIOaQOGFA..8H42fIFCCwDw67pMz/V8v5BwHq0wlMW', 'Orang Tua');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anak`
--
ALTER TABLE `anak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anak`
--
ALTER TABLE `anak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
