<?php
$host = "localhost"; //localhost berarti database berjalan di komputer/server yang sama
$user = "root"; //Username untuk login ke database
$password = ""; //Password database
$db = "nuids"; //Nama database yang akan digunakan

$conn = mysqli_connect($host, $user, $password, $db); //Membuat koneksi ke database MySQL

if (!$conn) { //Mengecek apakah koneksi gagal
    die("Koneksi gagal: " . mysqli_connect_error()); //Menampilkan pesan error dari MySQL
}
?>