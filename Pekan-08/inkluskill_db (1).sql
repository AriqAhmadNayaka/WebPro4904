-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 07:25 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inkluskill_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_uploads`
--

CREATE TABLE `chat_uploads` (
  `id` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `pesan` text DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_uploads`
--

INSERT INTO `chat_uploads` (`id`, `nama_file`, `pesan`, `tanggal`) VALUES
(1, '1.pdf', 'laporan ini bagaimana', '2026-04-02 11:28:02'),
(2, '2.pdf', 'Bagaimana status siswa?', '2026-04-02 16:07:15'),
(3, '3.pdf', 'rekomendasi pelatihan dong', '2026-04-09 11:24:19');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `nisn_siswa` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `nisn_siswa`, `nama_siswa`, `judul`, `keterangan`, `tanggal`, `status`) VALUES
(1, '1001', 'Ahmad Fauzi', 'Perkembangan Motorik Meningkat', 'Kemampuan koordinasi tangan-mata lebih baik', '2025-03-10', 'Baru'),
(2, '1002', 'Siti Rahayu', 'Perlu Perhatian Khusus Komunikasi', 'Kesulitan dalam komunikasi verbal kelompok', '2025-03-12', 'Baru'),
(3, '1003', 'Budi Santoso', 'Prestasi Pelatihan Menjahit', 'Berhasil menyelesaikan produk pertama', '2025-03-15', 'Ditangani'),
(4, '1005', 'Rizky Pratama', 'Absensi Perlu Ditingkatkan', 'Tiga kali tidak hadir tanpa keterangan', '2025-03-18', 'Baru'),
(5, '1004', 'Dewi Lestari', 'Evaluasi Status Keaktifan', 'Perlu evaluasi lanjutan terkait kehadiran', '2025-03-20', 'Ditangani');

-- --------------------------------------------------------

--
-- Table structure for table `pelatihan`
--

CREATE TABLE `pelatihan` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(150) NOT NULL,
  `instruktur` varchar(100) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelatihan`
--

INSERT INTO `pelatihan` (`id`, `nama_program`, `instruktur`, `tanggal_mulai`, `tanggal_selesai`, `status`) VALUES
(1, 'Keterampilan Menjahit', 'Ibu Sari', '2025-01-10', '2025-06-10', 'Aktif'),
(2, 'Budidaya Tanaman Sayur', 'Pak Hendra', '2025-02-01', '2025-07-01', 'Aktif'),
(3, 'Kerajinan Tangan & Anyaman', 'Ibu Dewi', '2025-03-05', '2025-08-05', 'Aktif'),
(4, 'Memasak dan Tata Boga', 'Ibu Ratna', '2025-01-15', '2025-04-15', 'Selesai'),
(5, 'Sablon dan Percetakan', 'Pak Agus', '2024-11-01', '2025-02-01', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nisn`, `nama`, `kelas`, `jenis_kelamin`, `email`, `no_telepon`, `alamat`, `status`, `foto`) VALUES
(1, '1001', 'Fariza Novianti', 'X', 'Perempuan', 'farifari@email.com', '081234567890', 'Bandung', 'Aktif', '8.png'),
(2, '1002', 'Aman Sentosa', 'XI', 'Laki-laki', 'aman@email.com', '082345678901', 'Cimahi', 'Aktif', '7.jpg'),
(4, '1004', 'Zalfaa Ghaitsaa', 'XI', 'Perempuan', 'zalfaa@email.com', '084567890123', 'Bandung', 'Aktif', '6.png'),
(5, '1005', 'Tinkie Winkie', 'XI', 'Laki-laki', 'tinkie@email.com', '085678901234', 'Cirebon', 'Aktif', '5.jpg'),
(11, '08773693', 'Fariza Novianti', 'XI', 'Laki-laki', 'frznovianti11@gmail.com', '0895397183849', 'Jl. Batu Ampar V', 'Aktif', '11.jpg');

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
(1, 'Iza', 'iza@gmail.com', '$2y$10$7OAgdC2N0RhSu4NuuStdGelAWB8atiUBB2j2Xk/30vg.37qu9zd/i', 'sekolah'),
(2, 'Izaza', 'izaza@gmail.com', '$2y$10$Ipf0K2MBuj8xH.s7Vc1uo.WNgsddEjJw3XiH6f6PFAnXU526EkzfG', 'sekolah'),
(3, 'izazaza', 'izazaza@gmail.com', '$2y$10$YmUCG9M3WLWjX1ZT3X5Nv.K59BmpcRckisUGvjpBptoBAJdKw/fwK', 'sekolah'),
(4, 'SLB G XXX Bojongsoang', 'slbxxx@gmail.com', '$2y$10$Cd5bkopzmeTtMK9RbBN5vuab2OndFiEfGC5MHvTfeho1qpMnrLh22', 'sekolah'),
(5, 'SLB ABC Bandung', 'slbabc@gmail.com', '$2y$10$GqFxr0X2Y7lA8Mik4m.9/upwBnSUl8mNwr76qxxhHae3z1Ki8rXua', 'sekolah');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_uploads`
--
ALTER TABLE `chat_uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelatihan`
--
ALTER TABLE `pelatihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
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
-- AUTO_INCREMENT for table `chat_uploads`
--
ALTER TABLE `chat_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pelatihan`
--
ALTER TABLE `pelatihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
