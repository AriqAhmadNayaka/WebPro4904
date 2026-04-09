-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 03:12 PM
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
-- Database: `loginuser`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`) VALUES
(1, 'udin', 'udin@gmail.com', '$2y$10$OXG3NFS8Rk0xRMxMBnBXpOlKu/VdQZFFBYhup4uYdA2DIiWdz3zBa'),
(2, 'udin', 'udin@gmail.com', '$2y$10$6r1xQsp4TI3iTg8tvw/2ZuNIaDBwgF.Nq53im.v5xtR46dmq38Xqi'),
(3, 'udin', 'udin@gmail.com', '$2y$10$Vd4is1JEoVcUtBYN3LUwg.9bBvUAEfrwpq4TqcJuzANQgPs4Fnw1a'),
(4, 'miley', 'miley@gmail.com', '$2y$10$vXz3znnHwquHSrtzjTEwN.MyohsXq0v0ut.WrjxYGB9Q5yhtAQ5ga'),
(5, 'Subarjo', 'NonongDongser@gmail.com', '$2y$10$HTMZB2HNJ1KOxy/QQytes.qPaeSXae9aJlcumj1A8wyNEHP.RLWfO');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_wisata`
--

CREATE TABLE `wishlist_wisata` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `harga` varchar(100) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist_wisata`
--

INSERT INTO `wishlist_wisata` (`id`, `nama`, `deskripsi`, `lokasi`, `harga`, `gambar`) VALUES
(2, 'Situ Patenggang', 'Situ Patenggang adalah pilihan pas buat kamu yang ingin bersantai menikmati alam dan udara yang sejuk dengan pemandangan danau seluas 48 hektar di ketinggian 1.628 meter. Di sini, selain kamu bisa duduk santai menikmati suasana tenang, kamu juga bisa menaiki perahu keliling danau yang keren banget, lho.', 'Desa Patenggang, Rancabali, Kabupaten Bandung, Jawa Barat 40973.', '25000', '1775110437_9336.webp'),
(3, 'Glamping Lakeside Rancabali', 'Glamping Lakeside Rancabali adalah destinasi glamping premium di kawasan Rancabali, Bandung Selatan, yang menggabungkan kemewahan akomodasi modern dengan keindahan alam pegunungan dan danau yang asri. ', 'Jl. Raya Ciwidey - Patengan No. Km. 39, Rancabali, Bandung, Jawa Barat 40973.', '25000', '1775737605_4032.jpg'),
(4, 'Ranca Upas', ' Kampung Cai Ranca Upas adalah destinasi wisata alam dan bumi perkemahan populer di Bandung Selatan, berlokasi di Jl. Raya Ciwidey-Patengan KM 11, Alam Endah. Tempat ini menawarkan penangkaran rusa, pemandian air panas (onsen), camping ground, serta berbagai aktivitas outdoor di ketinggian 1.700 mdpl dengan udara sejuk. ', 'Ciwidey, Kabupaten Bandung (±50 km dari pusat Kota Bandung).', '25000', '1775737777_7374.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist_wisata`
--
ALTER TABLE `wishlist_wisata`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlist_wisata`
--
ALTER TABLE `wishlist_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
