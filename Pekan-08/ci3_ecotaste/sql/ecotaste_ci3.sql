CREATE DATABASE IF NOT EXISTS ecotaste_ci3;
USE ecotaste_ci3;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (nama_lengkap, username, password)
VALUES ('Muhammad Rizqi', 'admin', '$2y$10$kW0LXCBV4fRnYK5mpvPP5uIkxOjNuVqBrNIHjIjHn2PNEmDOXf6zy');

INSERT INTO menus (nama, deskripsi, rating, gambar)
VALUES ('Nasi Liwet Eco', 'Menu contoh awal untuk dashboard EcoTaste.', 4.5, '');
