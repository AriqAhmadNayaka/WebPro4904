CREATE DATABASE IF NOT EXISTS tgs4;
USE tgs4;

CREATE TABLE IF NOT EXISTS stok (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    qty INT NOT NULL DEFAULT 0,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0
);

INSERT INTO stok (nama_barang, qty, harga) VALUES
('Keyboard', 10, 150000),
('Mouse', 15, 75000),
('Monitor', 5, 1250000);
