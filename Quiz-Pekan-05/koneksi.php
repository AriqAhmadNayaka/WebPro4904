<?php
// Konfigurasi dasar koneksi ke database MySQL.
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'webpro';

// Membuat koneksi ke database menggunakan mysqli.
$koneksi = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

// Jika koneksi gagal, hentikan program dan tampilkan pesan error.
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
