-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS ci3_project;

-- Gunakan database
USE ci3_project;

-- Buat tabel posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert data contoh
INSERT INTO posts (title, content) VALUES
('Judul Pertama', 'Ini adalah konten pertama untuk testing.'),
('Judul Kedua', 'Ini adalah konten kedua untuk testing.');