CREATE DATABASE IF NOT EXISTS db_webandoo_data
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE db_webandoo_data;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    remember_selector VARCHAR(64) DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    remember_expires DATETIME DEFAULT NULL,
    nama_lengkap VARCHAR(100) DEFAULT NULL,
    kota VARCHAR(100) DEFAULT NULL,
    kebutuhan_utama VARCHAR(150) DEFAULT NULL,
    minat TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS map_locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    lat DECIMAL(10, 7) NOT NULL,
    lng DECIMAL(10, 7) NOT NULL,
    icon VARCHAR(80) NOT NULL,
    color VARCHAR(20) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    menu_image VARCHAR(255) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    ticket VARCHAR(100) DEFAULT NULL,
    hours VARCHAR(100) DEFAULT NULL,
    cost VARCHAR(100) DEFAULT NULL,
    rating VARCHAR(20) DEFAULT NULL,
    popular VARCHAR(150) DEFAULT NULL,
    tags TEXT DEFAULT NULL,
    details TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_map_locations_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS content_blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(80) NOT NULL,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    link_url VARCHAR(255) DEFAULT NULL,
    link_label VARCHAR(80) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_content_blocks_page (page)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users (username, email, password, role, nama_lengkap, kota, kebutuhan_utama, minat)
SELECT
    'admin',
    'admin@webandoo.test',
    '$2y$10$lNCz9lFfpM/181OFr5okbeg41Bk8pXMgMqkItwlXY/8Krr/l6U6Ze',
    'admin',
    'Administrator WeBandoo+',
    'Bandung',
    'Mengelola data lokasi dan review',
    'Admin, Moderasi'
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin@webandoo.test'
);

CREATE TABLE IF NOT EXISTS place_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    place_name VARCHAR(150) NOT NULL,
    reviewer_name VARCHAR(100) NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    visit_date DATE NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_place_name (place_name),
    INDEX idx_user_id (user_id),
    CONSTRAINT fk_place_reviews_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
