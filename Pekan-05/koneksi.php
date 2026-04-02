<?php
// Koneksi ke database MySQL menggunakan mysqli
$host = "localhost";
$user = "root";
$password = "";
$database = "navibiz";

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $password, $database);

// Mengecek koneksi, jika gagal maka akan menampilkan pesan error dan menghentikan eksekusi script
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika konesi berhasil, maka akan menampilkan pesan bahwa koneksi berhasil
echo "Koneksi berhasil";
?>