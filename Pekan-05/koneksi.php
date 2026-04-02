<?php
// Konfigurasi koneksi database
$host = "localhost";     // Host database 
$username = "root";      // Username MySQL
$password = "";          // Password MySQL 
$database = "loginuser"; // Nama database yang digunakan
$conn = new mysqli($host, $username, $password, $database);// Membuat koneksi ke MySQL 
// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} else {
}

?>