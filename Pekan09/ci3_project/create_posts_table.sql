-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS ci3_project;

-- Gunakan database
USE ci3_project;

-- Buat tabel posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL DEFAULT 'Anonymous',
    article TEXT NOT NULL,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert data contoh
INSERT INTO posts (title, author, article) VALUES
('Judul Pertama', 'Anonymous', 'Ini adalah konten pertama untuk testing.'),
('Judul Kedua', 'Anonymous', 'Ini adalah konten kedua untuk testing.');