<?php
$host     = "localhost";
$username = "root";
$password = ""; // Kosongkan jika pakai XAMPP bawaan
$database = "tugas_besar"; // Sesuaikan dengan nama database di phpMyAdmin kamu

// Proses koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>