CREATE TABLE IF NOT EXISTS `kontak` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `nomor` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kontak` (`nama`, `nomor`) VALUES
('Bintang', '081222077960'),
('WeBandoo Admin', '08123456789');
