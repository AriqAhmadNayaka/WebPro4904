-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 02:08 PM
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
-- Database: `lifetrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `informasi_medis`
--

CREATE TABLE `informasi_medis` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `informasi_medis`
--

INSERT INTO `informasi_medis` (`id`, `judul`, `kategori`, `deskripsi`, `file`) VALUES
(12, '.', 'Perawatan & Medis', '.', 'poto.jpg'),
(13, '.', 'Obat & Terapi', '.', 'pp.jpg'),
(14, '.', 'Gaya Hidup Sehat', '.', 'anjing imut.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`) VALUES
(3, 'tenaga_medis', 'jeno', 'jeno@gmail.com', '$2y$10$f/.WMJGfxDE5tnbQ5qGx8uywbuOcK.Fp0DjT5WyCtGNuNuKj3YIaO'),
(4, 'tenaga_medis', 'jaemin', 'jaemin@gmail.com', '$2y$10$EB..QxZQ7dIzN8xwyOXRTOrD8WnZnOpHUkZuggkS7a5q/BRbwv0ky'),
(5, 'tenaga_medis', 'mark', 'mark@gmail.com', '$2y$10$PGa/5cjfB0A65ncOL5lBY.QkzjdzltI0ndRfRYzujGDB8gSoKzVO2'),
(6, 'tenaga_medis', 'haechan', 'haechan@gmail.com', '$2y$10$XOW8TFrZvUUvc1mMsUTeduEVImnk9cqVl2SKH0glBGloO0a4UZaKG'),
(7, 'tenaga_medis', 'haechan', 'haechan@gmail.com', '$2y$10$M.u3GLy/FQf6PQqhdDZzKOa74ztty45ZzY0JfFO18jttWWT6er4zO'),
(8, 'tenaga_medis', 'haechan', 'haechan@gmail.com', '$2y$10$AAfNacLWfBY5S5fvDXa3ZuBBwKSghZ6jjvcgoIUCAYi//aO3Q505K'),
(9, 'tenaga_medis', 'ian', 'ian@gmail.com', '$2y$10$XxZmxLnFPSAYiiI/IyIOyO47CWlFl5vKMV0UUD9Nontm2hK0/YUii');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `informasi_medis`
--
ALTER TABLE `informasi_medis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `informasi_medis`
--
ALTER TABLE `informasi_medis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
