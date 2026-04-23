/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `nuids_db`;
CREATE DATABASE IF NOT EXISTS `nuids_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `nuids_db`;

CREATE TABLE IF NOT EXISTS `catatan_medis` (
  `id_catatan` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keluhan` text,
  PRIMARY KEY (`id_catatan`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `catatan_medis_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `catatan_medis` (`id_catatan`, `id_user`, `tanggal`, `keluhan`) VALUES
	(1, 4, '2026-03-26', 'adwadawdadwa');

CREATE TABLE IF NOT EXISTS `dokter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `spesialisasi` enum('umum','anak','penyakit-dalam','jantung','gigi','kulit') NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `nomor_sip` varchar(50) DEFAULT NULL,
  `kode_dokter` varchar(50) DEFAULT NULL,
  `bio` text,
  `sertifikat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `kode_dokter` (`kode_dokter`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','doctor') NOT NULL DEFAULT 'user',
  `cert_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `cert_path`, `created_at`) VALUES
	(4, 'dhodho', 'dhodho@gmail.com', '$2y$10$947ghkEA.xgiCH79wbuuteElMPhnwFurKrYgrbvB41vROeY2k0O7O', 'user', NULL, '2026-03-26 04:26:30'),
	(5, 'dhodho', 'andhikayuridho05@gmail.com', '$2y$10$r3OyD9Dj29K9wxufdfJr3uF1wKbFGf1sppZjRNH1Q42kv6mobdRxK', 'user', NULL, '2026-03-26 05:21:57');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
