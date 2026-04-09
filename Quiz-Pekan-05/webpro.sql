-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2026 at 05:29 PM
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
-- Database: `webpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id_peseera` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id_peseera`, `nama`, `umur`) VALUES
(1, 'Dryhus', 18);

-- --------------------------------------------------------

--
-- Table structure for table `quiz5`
--

CREATE TABLE `quiz5` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz5`
--

INSERT INTO `quiz5` (`id_user`, `username`, `password`) VALUES
(1, 'miaw', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `stok_barang`
--

CREATE TABLE `stok_barang` (
  `id` int(11) NOT NULL,
  `no` int(11) DEFAULT NULL,
  `nama barang` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `harga` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_barang`
--

INSERT INTO `stok_barang` (`id`, `no`, `nama barang`, `qty`, `harga`) VALUES
(2, NULL, 'test1', 1, 1),
(4, NULL, 'RTX 4090', 1, 10000000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `pelatihan` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `password`, `username`, `nama`, `umur`, `jenis_kelamin`, `pelatihan`, `foto`) VALUES
(20, 'dzacky98ku@gmail.com', '938b3b4406bddffc1aadff17e801dd1c', 'Dryhus Dzacky Damingtyas.S', NULL, NULL, NULL, NULL, NULL),
(24, 'davingay@gmail.com', 'cb9e6df0cbbe63c13181f472b3a68f3b', 'Davin Miaw', NULL, NULL, NULL, NULL, NULL),
(28, 'dryhus@gmail.com', '8f1470f08c9caf5cc8113de188b66207', 'Dryhus Ganteng', NULL, NULL, NULL, NULL, NULL),
(30, 'dryhus@gmail.com', '8f1470f08c9caf5cc8113de188b66207', 'Dryhus', NULL, NULL, NULL, NULL, NULL),
(31, NULL, NULL, '', 'Dryhus Dzacky Damingtyas.S', 18, 'Laki-laki', 'Icikiwir', 'peserta_69cdf960046dd9.62627595.png'),
(32, 'davinicikiwir@gmail.com', '8b900377fc9a207bfcb21c0e4ee88cff', 'Davin Icikiwir', NULL, NULL, NULL, NULL, NULL),
(33, NULL, NULL, '', 'Davin', 80, 'Laki-laki', 'Moshing', 'peserta_69ce00f05729d2.22620006.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id_peseera`);

--
-- Indexes for table `quiz5`
--
ALTER TABLE `quiz5`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_peseera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz5`
--
ALTER TABLE `quiz5`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stok_barang`
--
ALTER TABLE `stok_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
