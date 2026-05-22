-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 22, 2026 at 06:02 PM
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
-- Database: `ci3_project1`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `article` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `author`, `article`, `image`, `created_at`, `updated_at`) VALUES
(4, 'Report', 'Zalfaa Ghaitsaa', 'This is a report', 'posts/1777713478_Screenshot__1_.png', '2026-05-02 04:17:58', '2026-05-02 04:17:58'),
(7, 'Report', 'Zalfaa Ghaitsaa ', 'This is a report', 'posts/post_1777715546_69f5c95a4e27b.png', '2026-05-02 04:52:26', '2026-05-02 04:52:26'),
(8, 'Report 2', 'Zalfaa Ghaitsaa ', 'This is a report', 'posts/post_1777715577_69f5c979a15e1.png', '2026-05-02 04:52:57', '2026-05-02 04:52:57'),
(9, 'Checkout Asrama Tahun 2026', 'Zalfaa Ghaitsaa ', 'This is a report', 'posts/post_1777715660_69f5c9cc7478e.png', '2026-05-02 04:54:20', '2026-05-22 09:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Zalfaa Ghaitsaa Arrahma Bahtiar', 'zalfaaghaitsaa310@gmail.com', '$2y$10$8T1Y.PmwtQ/1Kju8h8T/jO.PlDHVcVG15KNxrHrX6DQ.gq.FXeQJi', '2026-05-02 09:17:08', '2026-05-02 09:17:08'),
(2, 'Zalfaa Ghaitsaa Arrahma Bahtiar', 'zalfaaghaitsaa@gmail.com', '$2y$10$O25pKl2KdjvPVgHGAl/oEO2OXz4zJknVmRdHRr8HXoUkTi.in5ix2', '2026-05-21 06:13:02', '2026-05-21 06:13:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
