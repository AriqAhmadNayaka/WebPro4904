-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2026 at 05:59 PM
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
-- Database: `ci3_project`
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `author`, `article`, `image`, `created_at`, `updated_at`) VALUES
(2, 'The Vampire Diaries', ' L.J. Smith', 'The Vampire Diaries adalah serial novel remaja bertema vampir, romansa, dan horor yang diciptakan dan ditulis oleh L. J. Smith. ', 'posts/1777364882_Screenshot_2026-04-28_152749.png', '2026-04-28 03:28:02', '2026-04-28 03:28:02'),
(3, 'The Originals', 'Julie Plec', 'Sebuah keluarga vampir Tertua saat sang vampir/manusia serigala hibrid yang berbahaya, Klaus (Joseph Morgan), kembali ke New Orleans, kota yang turut ia bangun berabad-abad lalu.', 'posts/1777739626_Screenshot_2026-05-02_233330.png', '2026-05-02 11:33:46', '2026-05-02 11:33:46');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(3, 'Selvinda Lestari Sitanggang', 'selvindasitanggang@gmail.com', '$2y$10$R8jSmZjht3mwCxeJmGu2VO7UhJAmdtxAX1sku/11fQiu2i3KJozGy', '2026-05-02 17:31:45', '2026-05-02 17:31:45'),
(4, 'Selvin', 'selvinda@gmail.com', '$2y$10$nYEfOA8zVC/kbIGn1MM9J.ZoOOzSOWfJ5BsgAyTHVEJvwCgIY5.OC', '2026-05-02 18:47:00', '2026-05-21 12:31:33'),
(5, 'Selvindaaaa', 'selvindaaaa@gmail.com', '$2y$10$GqX2.QP98Obz6HbRkq/EyOh2rcA4V1fUkEtzbaiEy4Sx2n7HmWHdC', '2026-05-21 07:08:12', '2026-05-21 07:08:12');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
