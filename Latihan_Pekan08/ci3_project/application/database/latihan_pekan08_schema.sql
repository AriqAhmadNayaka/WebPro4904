CREATE DATABASE IF NOT EXISTS db_latihan_pekan08_crud
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE db_latihan_pekan08_crud;

CREATE TABLE IF NOT EXISTS produk (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    kode_produk VARCHAR(30) NOT NULL,
    nama_produk VARCHAR(120) NOT NULL,
    kategori VARCHAR(80) NOT NULL,
    harga INT UNSIGNED NOT NULL DEFAULT 0,
    stok INT UNSIGNED NOT NULL DEFAULT 0,
    status ENUM('Tersedia', 'Pre Order', 'Habis') NOT NULL DEFAULT 'Tersedia',
    deskripsi TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_kode_produk (kode_produk)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok, status, deskripsi)
SELECT 'PRD-001', 'Notebook Planner', 'ATK', 45000, 24, 'Tersedia', 'Buku catatan hardcover untuk kebutuhan sekolah dan kerja.'
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE kode_produk = 'PRD-001');

INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok, status, deskripsi)
SELECT 'PRD-002', 'Lampu Meja Minimalis', 'Elektronik', 185000, 6, 'Pre Order', 'Lampu meja dengan tiga mode cahaya untuk ruang belajar.'
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE kode_produk = 'PRD-002');

INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok, status, deskripsi)
SELECT 'PRD-003', 'Kotak Bekal Bento', 'Rumah Tangga', 68000, 4, 'Habis', 'Kotak bekal food grade dengan sekat praktis dan rapat.'
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE kode_produk = 'PRD-003');
