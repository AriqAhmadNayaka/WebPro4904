-- Database lama dari pekan 6 yang dipakai ulang untuk pekan 7
CREATE DATABASE IF NOT EXISTS `db_webprodata`;
USE `db_webprodata`;

CREATE TABLE IF NOT EXISTS `laporan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `jenis` varchar(20) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `laporan` (`id`, `tanggal`, `keterangan`, `jenis`, `jumlah`, `foto`) VALUES
(2, '2026-03-26', 'perampokan warung', 'Pengeluaran', 1000000, NULL),
(3, '2026-03-26', 'perampokan', 'Pengeluaran', 125000, NULL),
(4, '2007-02-14', 'gaji', 'Pemasukan', 2000000, '20260402072710-Background-aldan--1-.png')
ON DUPLICATE KEY UPDATE
`tanggal` = VALUES(`tanggal`),
`keterangan` = VALUES(`keterangan`),
`jenis` = VALUES(`jenis`),
`jumlah` = VALUES(`jumlah`),
`foto` = VALUES(`foto`);

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `username`, `telepon`, `alamat`) VALUES
(1, 'Administrator NaviBiz', 'admin@navibiz.local', '12345', 'admin', '081234567890', 'Dashboard NaviBiz')
ON DUPLICATE KEY UPDATE
`nama` = VALUES(`nama`),
`email` = VALUES(`email`),
`password` = VALUES(`password`),
`telepon` = VALUES(`telepon`),
`alamat` = VALUES(`alamat`);
