CREATE DATABASE IF NOT EXISTS `smarttrash_monitor2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `smarttrash_monitor2`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `trash_bins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bin_code` varchar(30) NOT NULL,
  `location_name` varchar(120) NOT NULL,
  `waste_level` tinyint unsigned NOT NULL DEFAULT '0',
  `status` enum('AMAN','WASPADA','PENUH','DIANGKUT') NOT NULL DEFAULT 'AMAN',
  `last_collection` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `bin_code` (`bin_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `waste_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `category_slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name` (`category_name`),
  UNIQUE KEY `category_slug` (`category_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `catalog_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `item_name` varchar(120) NOT NULL,
  `item_type` varchar(80) NOT NULL,
  `item_condition` enum('Layak Daur Ulang','Perlu Dibersihkan','Residu') NOT NULL DEFAULT 'Layak Daur Ulang',
  `example_photo` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_catalog_category` FOREIGN KEY (`category_id`) REFERENCES `waste_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`name`, `email`, `password`, `role`, `photo`) VALUES
('Administrator Utama', 'admin@cybervault.com', '$2y$10$HzS/gp80julhmoQaJl0x.O8PCo7g70eU0e0oK.eQk2okswdlY61ra', 'admin', NULL)
ON DUPLICATE KEY UPDATE `role` = VALUES(`role`);

INSERT INTO `trash_bins` (`bin_code`, `location_name`, `waste_level`, `status`, `last_collection`, `notes`) VALUES
('BIN-001', 'Lobby Kampus', 35, 'AMAN', NULL, 'Kondisi normal.'),
('BIN-002', 'Area Parkir Timur', 78, 'WASPADA', NULL, 'Perlu dicek sore ini.'),
('BIN-003', 'Kantin Utama', 95, 'PENUH', NULL, 'Prioritas pengangkutan.')
ON DUPLICATE KEY UPDATE
`location_name` = VALUES(`location_name`),
`waste_level` = VALUES(`waste_level`),
`status` = VALUES(`status`),
`notes` = VALUES(`notes`);

INSERT INTO `waste_categories` (`category_name`, `category_slug`, `description`) VALUES
('Organik', 'organik', 'Sampah yang mudah terurai seperti sisa makanan, daun, dan kulit buah.'),
('Non Organik', 'non-organik', 'Sampah anorganik seperti botol plastik, kaleng, dan kemasan.'),
('B3', 'b3', 'Sampah bahan berbahaya dan beracun seperti baterai dan lampu bekas.')
ON DUPLICATE KEY UPDATE
`description` = VALUES(`description`);

INSERT INTO `catalog_items` (`category_id`, `item_name`, `item_type`, `item_condition`, `notes`)
SELECT c.id, 'Sisa Nasi', 'Sisa Makanan', 'Layak Daur Ulang', 'Bisa masuk komposter.'
FROM `waste_categories` c
WHERE c.category_name = 'Organik'
AND NOT EXISTS (
  SELECT 1 FROM `catalog_items` i WHERE i.category_id = c.id AND i.item_name = 'Sisa Nasi'
);

INSERT INTO `catalog_items` (`category_id`, `item_name`, `item_type`, `item_condition`, `notes`)
SELECT c.id, 'Botol Plastik', 'Plastik PET', 'Perlu Dibersihkan', 'Bilas sebelum didaur ulang.'
FROM `waste_categories` c
WHERE c.category_name = 'Non Organik'
AND NOT EXISTS (
  SELECT 1 FROM `catalog_items` i WHERE i.category_id = c.id AND i.item_name = 'Botol Plastik'
);

INSERT INTO `catalog_items` (`category_id`, `item_name`, `item_type`, `item_condition`, `notes`)
SELECT c.id, 'Baterai Bekas', 'Limbah Elektronik', 'Residu', 'Simpan di wadah khusus B3.'
FROM `waste_categories` c
WHERE c.category_name = 'B3'
AND NOT EXISTS (
  SELECT 1 FROM `catalog_items` i WHERE i.category_id = c.id AND i.item_name = 'Baterai Bekas'
);
