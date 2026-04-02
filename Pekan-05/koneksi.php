<?php
// Konfigurasi database, sesuaikan dengan konfigurasi XAMPP
// Jika masih localhost, host tetap "localhost"
// Username default XAMPP adalah "root" dengan password kosong
$host     = "localhost";
$username = "root";
$password = "";
$database = "inkluskill_db";

// Membuat koneksi ke database menggunakan mysqli
// Parameter: hostname, username, password, nama database
$conn = new mysqli(
    hostname: $host,
    username: $username,
    password: $password,
    database: $database
);

// Cek apakah koneksi berhasil atau tidak
// Jika connect_error tidak null, berarti koneksi gagal dan program dihentikan
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke utf8 agar karakter Indonesia (huruf dan simbol) tersimpan dengan benar
$conn->set_charset("utf8");