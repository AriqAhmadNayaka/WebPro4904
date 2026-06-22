-- Membuat database utama aplikasi EcoTaste.
CREATE DATABASE IF NOT EXISTS `ecotaste_db`
	CHARACTER SET utf8mb4
	COLLATE utf8mb4_unicode_ci;

USE `ecotaste_db`;

DROP TABLE IF EXISTS `waste_reports`;
DROP TABLE IF EXISTS `restaurant_ratings`;
DROP TABLE IF EXISTS `users`;

-- Tabel users menyimpan akun konsumen dan mitra resto.
CREATE TABLE `users` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(120) NOT NULL,
	`email` VARCHAR(160) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`role` ENUM('konsumen', 'mitra') NOT NULL,
	`is_active` TINYINT(1) NOT NULL DEFAULT 1,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel restaurant_ratings menyimpan CRUD rating restoran dari konsumen.
CREATE TABLE `restaurant_ratings` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT UNSIGNED NOT NULL,
	`restaurant_name` VARCHAR(160) NOT NULL,
	`rating` TINYINT UNSIGNED NOT NULL,
	`review` TEXT NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `restaurant_ratings_user_id_index` (`user_id`),
	CONSTRAINT `restaurant_ratings_user_id_foreign`
		FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `restaurant_ratings_rating_check`
		CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel waste_reports menyimpan CRUD pelaporan limbah dari mitra resto.
CREATE TABLE `waste_reports` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT UNSIGNED NOT NULL,
	`report_date` DATE NOT NULL,
	`waste_type` VARCHAR(80) NOT NULL,
	`weight_kg` DECIMAL(10,2) NOT NULL,
	`disposal_method` VARCHAR(160) NOT NULL,
	`notes` TEXT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `waste_reports_user_id_index` (`user_id`),
	CONSTRAINT `waste_reports_user_id_foreign`
		FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `waste_reports_weight_check`
		CHECK (`weight_kg` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Akun demo untuk login awal.
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Konsumen EcoTaste', 'konsumen@ecotaste.test', 'konsumen123', 'konsumen'),
(2, 'Mitra Resto Hijau', 'mitra@ecotaste.test', 'mitra123', 'mitra');

-- Contoh data awal rating restoran milik konsumen demo.
INSERT INTO `restaurant_ratings` (`user_id`, `restaurant_name`, `rating`, `review`) VALUES
(1, 'Warung Daun Kota', 5, 'Porsi pas, kemasan ramah lingkungan, dan sisa makanan bisa dipilah.'),
(1, 'Resto Saji Hijau', 4, 'Menu sehat dan pelayanan cepat, area refill air minum sangat membantu.');

-- Contoh data awal laporan limbah milik mitra demo.
INSERT INTO `waste_reports` (`user_id`, `report_date`, `waste_type`, `weight_kg`, `disposal_method`, `notes`) VALUES
(2, CURDATE(), 'Organik', 12.50, 'Kompos internal', 'Sisa sayur dan kulit buah dari dapur.'),
(2, CURDATE(), 'Minyak jelantah', 4.00, 'Vendor limbah tersertifikasi', 'Dikumpulkan dalam jeriken tertutup.');
