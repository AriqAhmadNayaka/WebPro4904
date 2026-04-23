CREATE DATABASE IF NOT EXISTS smartparking_report;
USE smartparking_report;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lokasi VARCHAR(150) NOT NULL,
    waktu_laporan DATETIME NOT NULL,
    jumlah_kendaraan INT NOT NULL,
    status_pelanggaran ENUM('Ringan', 'Sedang', 'Berat') NOT NULL,
    deskripsi TEXT NOT NULL,
    foto_bukti VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_laporan_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

INSERT INTO users (nama, email, password)
VALUES (
    'Operator Demo',
    'operator@smartpark.com',
    '$2y$10$cpYafyGmqZa.Ol0CGC4mrO4mPVJ0nre75YPLHBPG798Rgze6F1OKe'
);
