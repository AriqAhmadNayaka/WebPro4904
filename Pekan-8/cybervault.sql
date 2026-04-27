-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2026 at 10:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cybervault`
--

-- --------------------------------------------------------

--
-- Table structure for table `datauser`
--

CREATE TABLE `datauser` (
  `id` int(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datauser`
--

INSERT INTO `datauser` (`id`, `nama`, `email`, `password`, `foto`) VALUES
(8, 'Naruto', 'naruto@gmail.com', '$2y$10$/T.sPpLcI.F0G8pAgH5cBuKrKd4WVb70/ydnSZS/kzYAQA27Fwvua', '69cdf95db4ac3.jpeg'),
(9, 'Sasuke', 'sasuke@gmail.com', '$2y$10$.5NZd3V3vUPvyVM1sgoi6.5wghM2uzAxx6MiUy1SsP.tL1yh4FhMy', '69cdf97d91a37.jpeg'),
(10, 'Hanzo', 'hanzo@gmail.com', '$2y$10$HffdDXZLrjKZbAt8OtDIx.NSY.wQqhaSLuD3qTuU2/KH1Kyef3sNy', '69cdf9961219e.jpeg'),
(11, 'Pain', 'pain@gmail.com', '$2y$10$.Po8cs.LUeecLZ4nDApsZu/PLCZVM2tASkA5Fbhe9D.VmG2/5gTU2', '69cdf9aa52ec8.jpeg'),
(16, 'zulka', 'zulka@gmail.com', '$2y$10$ns6fozeHtj1nIlvH6zIsReXfLqrULDCP0PmjqUduZFc1SuloTDL4y', '69d75bc8e7db9.png');

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
