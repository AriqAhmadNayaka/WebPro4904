<?php
session_start(); // Mulai session untuk menyimpan status login

// Jika belum login, arahkan ke login.php
// Mengecek apakah session 'login' sudah ada
if (!isset($_SESSION['login'])) { 
    header("Location: login.php"); // Jika belum login, redirect ke halaman login
    exit;                          // Hentikan eksekusi script
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Halaman Utama</title>
  <!-- Panggil file CSS agar tampilan lebih rapi -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <!-- Judul halaman utama -->
  <h2>Halaman Utama Aplikasi Desa</h2>
  
  <!-- Link menuju halaman warga untuk CRUD data -->
  <a href="warga.php">Kelola Data Warga</a>
</div>
</body>
</html>