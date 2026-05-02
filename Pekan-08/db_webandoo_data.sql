-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 07:26 AM
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
-- Database: `db_webandoo_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `nomor_telepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `no_hp`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `kota`, `pekerjaan`, `bio`, `foto_profil`, `nomor_telepon`) VALUES
(10, 'jaki', 'jaki@kies.com', '$2y$10$.p3drmSNldlSuUhwIvwONuWY98UoBmpXyjFmX73/OkfmyC6GnbbMa', 'jaki', '08123456789', '1982-03-09', 'Laki-laki', 'yhqayqyyq', 'Bandung', 'Mahasiswa', 'hqhahha', 'uploads/profile/profile_jaki_kies_com_1774687993.png', NULL),
(11, 'Bintang', 'kentang@kies.com', '$2y$10$KVCahIXsl03vlXJagL.GPO.u5RIYO.m.5kV7yGuPdEijLvwMwCD5y', 'Kies', '081222077960', '2006-06-06', 'Laki-laki', '-', 'Bandung', 'Mahasiswa', '-', 'uploads/profile/profile_kentang_kies_com_1775105940.jpg', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
