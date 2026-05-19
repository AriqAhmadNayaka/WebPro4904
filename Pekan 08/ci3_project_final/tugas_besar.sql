-- ============================================================
-- DATABASE: tugas_besar
-- Gabungan pekan8/tugas_besar.sql + pekan8/proyek.sql
-- Digunakan oleh: ci3_project_final (CodeIgniter 3 MVC)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `tugas_besar`
    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tugas_besar`;

-- ============================================================
-- Tabel: users
-- Konversi dari: pekan8/tugas_besar.sql (tabel users)
-- Di ci3: diakses oleh Auth_model.php via $this->db
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id`       int(11)      NOT NULL AUTO_INCREMENT,
  `username` varchar(50)  DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User bawaan: admin / admin123
-- Password di-hash dengan password_hash() (bcrypt)
INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$nyMXSJ/tKTUIhHbRh1ieIuFmTLf0YlN.mhMjj0ENsD5Dy9gSGojxO')
ON DUPLICATE KEY UPDATE `id` = `id`;

-- ============================================================
-- Tabel: proyek
-- Konversi dari: pekan8/proyek.sql (tabel proyek)
-- Di ci3: diakses oleh Proyek_model.php via $this->db
-- ============================================================
CREATE TABLE IF NOT EXISTS `proyek` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `nama_proyek` varchar(100) DEFAULT NULL,
  `deskripsi`   text         DEFAULT NULL,
  `nama_file`   varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data contoh
INSERT INTO `proyek` (`id`, `nama_proyek`, `deskripsi`, `nama_file`) VALUES
(1, 'Pembangunan jalan tol', 'Jarak 120.000 km melewati 3 provinsi', 'download (7).jpg')
ON DUPLICATE KEY UPDATE `id` = `id`;
