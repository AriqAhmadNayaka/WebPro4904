-- ============================================
-- ci3_project - DATABASE LENGKAP (users + posts)
-- Untuk Modul 12 React Blog Post
-- ============================================

CREATE DATABASE IF NOT EXISTS `ci3_project`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ci3_project`;

-- ---------- Tabel users ----------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Akun login (password sudah di-hash bcrypt)
-- email    : admin@blog.com
-- password : password123
INSERT INTO `users` (`name`, `email`, `password`) VALUES
('Admin', 'admin@blog.com', '$2y$10$RKAFvU.gYcDsV3QcV18A/uB8Qrmut/2HUPJQePrrCoKBl4dPsoyKy');

-- ---------- Tabel posts ----------
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `article` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Contoh data post
INSERT INTO `posts` (`title`, `author`, `article`, `image`) VALUES
('Pengenalan React JS', 'Admin', 'React adalah library JavaScript untuk membangun user interface. React dikembangkan oleh Facebook dan telah menjadi salah satu library frontend paling populer saat ini.\nDengan pendekatan berbasis komponen, React memudahkan developer membangun aplikasi yang kompleks dengan kode yang lebih terstruktur dan mudah dipelihara.', NULL),
('Memahami State dan Props di React', 'Admin', 'State dan Props adalah dua konsep fundamental dalam React yang perlu dipahami dengan baik. Props (Properties) adalah data yang dikirim dari parent component ke child component dan bersifat read-only.\nSementara itu, State adalah data internal yang dikelola oleh komponen itu sendiri dan dapat berubah seiring interaksi pengguna.', NULL),
('React Hooks: useState dan useEffect', 'Admin', 'Hooks adalah fitur baru di React 16.8 yang memungkinkan kita menggunakan state dan fitur React lainnya tanpa menulis class component. useState digunakan untuk mengelola state, sedangkan useEffect untuk menangani side effect seperti pengambilan data dari API.', NULL),
('Routing di React dengan React Router', 'Admin', 'React Router adalah library standar untuk routing di aplikasi React. Dengan React Router, kita bisa membuat Single Page Application (SPA) dengan multiple halaman tanpa melakukan reload penuh pada browser.', NULL);
