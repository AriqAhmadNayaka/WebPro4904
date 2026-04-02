<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_konten";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Optional (boleh dihapus kalau sudah yakin)
echo "Koneksi berhasil";
?>