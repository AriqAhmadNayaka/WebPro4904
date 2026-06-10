CREATE DATABASE IF NOT EXISTS db_pekan09_ci3 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE db_pekan09_ci3;

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    file VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO posts (title, content, file) VALUES
('Post Pertama', 'Contoh isi post pertama untuk Pekan 09.', NULL),
('Post Kedua', 'Contoh isi post kedua untuk demo CRUD AJAX.', NULL);
