-- Membuat database jika belum tersedia.
CREATE DATABASE IF NOT EXISTS db_portofolio_ci3;

-- Menggunakan database portofolio yang baru dibuat.
USE db_portofolio_ci3;

-- Hapus tabel terlebih dahulu agar script bisa dijalankan ulang tanpa konflik.
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS profiles;

-- Tabel profiles menyimpan data identitas utama pemilik portofolio.
CREATE TABLE profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    profession VARCHAR(100) NOT NULL,
    about TEXT NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(150) NOT NULL,
    skills VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL
);

-- Tabel projects menyimpan daftar project portofolio yang dapat dikelola dengan CRUD.
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    category VARCHAR(80) NOT NULL,
    description TEXT NOT NULL,
    project_link VARCHAR(255) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Data awal profil agar website langsung memiliki isi setelah import database.
INSERT INTO profiles (full_name, profession, about, email, phone, address, skills, profile_photo) VALUES
('Dryhus Dzacky Damingtyas', 'Web Developer', 'Saya adalah pengembang web yang senang membangun aplikasi sederhana, rapi, dan mudah dipakai. Bagian ini bisa Anda ubah dari dashboard CRUD.', 'dryhus@example.com', '081234567890', 'Jakarta, Indonesia', 'HTML, CSS, JavaScript, PHP, CodeIgniter 3', NULL);

-- Data awal project contoh untuk ditampilkan di halaman portofolio.
INSERT INTO projects (title, category, description, project_link, image) VALUES
('Website Company Profile', 'Website', 'Project website company profile dengan tampilan responsif dan halaman informasi bisnis.', 'https://example.com/company-profile', NULL),
('Aplikasi Kasir Sederhana', 'Web App', 'Aplikasi CRUD untuk pencatatan produk, transaksi, dan laporan penjualan harian.', 'https://example.com/aplikasi-kasir', NULL);
