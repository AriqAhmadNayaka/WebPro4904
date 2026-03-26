<?php
$host = "localhost";
$user = "root";      // default user XAMPP
$pass = "";          // default password kosong
$db   = "web_desa";  // ganti sesuai nama database kamu

$conn = new mysqli($host, $user, $pass, $db);   //  buat koneksi

if($conn->connect_error){
    die("Koneksi gagal: " . $conn->connect_error);
} // cek koneksi, jika error tampilkan pesan
?>