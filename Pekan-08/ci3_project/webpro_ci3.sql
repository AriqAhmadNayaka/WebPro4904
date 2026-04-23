CREATE TABLE IF NOT EXISTS `ci3_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'sekolah',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ci3_participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  `umur` int(11) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `pelatihan` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ci3_users` (`username`, `email`, `password`, `role`)
SELECT 'Admin InkluSkill', 'admin@inkluskill.test', '$2y$10$QRAHljKQoY7M.nqFCyJ.5O./tc6vAl/SNS9UK3hjenqKJ48L7Mqo.', 'sekolah'
WHERE NOT EXISTS (
  SELECT 1 FROM `ci3_users` WHERE `email` = 'admin@inkluskill.test'
);
