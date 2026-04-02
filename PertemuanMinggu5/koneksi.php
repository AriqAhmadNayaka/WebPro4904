<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "cybervault";

$conn = mysqli_connect($dbHost, $dbUser, $dbPass);

if (!$conn) {
    die("Koneksi MySQL gagal: " . mysqli_connect_error());
}

if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$dbName`")) {
    die("Gagal membuat database: " . mysqli_error($conn));
}

if (!mysqli_select_db($conn, $dbName)) {
    die("Gagal memilih database: " . mysqli_error($conn));
}

// Tambahkan kolom 'role' di sini
$createTableQuery = "
    CREATE TABLE IF NOT EXISTS datauser (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        foto VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
";

if (!mysqli_query($conn, $createTableQuery)) {
    die("Gagal menyiapkan tabel datauser: " . mysqli_error($conn));
}

// Tambahkan pengecekan kolom 'role' agar otomatis terbuat jika belum ada
$requiredColumns = [
    "name" => "ALTER TABLE datauser ADD COLUMN name VARCHAR(100) NOT NULL AFTER id",
    "role" => "ALTER TABLE datauser ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user' AFTER password",
    "foto" => "ALTER TABLE datauser ADD COLUMN foto VARCHAR(255) DEFAULT NULL AFTER role"
];

foreach ($requiredColumns as $columnName => $alterQuery) {
    $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM datauser LIKE '$columnName'");
    if (mysqli_num_rows($checkColumn) === 0) {
        mysqli_query($conn, $alterQuery);
    }
}
?>