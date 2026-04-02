<?php
// Aktifkan error reporting agar semua error muncul
error_reporting(E_ALL);
ini_set('display_errors', 1);

// File koneksi ke database MySQL
$host = "localhost";   // Host server
$user = "root";        // Username default XAMPP
$pass = "";            // Password default kosong
$db   = "webdesa2";    // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Jika gagal, tampilkan pesan error
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>