<?php
$conn = mysqli_connect("localhost", "root", "", "navibiz"); 
// membuat koneksi antara PHP dengan database MySQL
// "localhost" merupakan server database (biasanya lokal/XAMPP)
// "root" merupakan username database
// "" merupakan password (kosong karena default XAMPP)
// "navibiz" merupakan nama database yang digunakan

if (!$conn) {
// mengecek apakah koneksi ke database berhasil atau gagal
    die("Koneksi gagal: " . mysqli_connect_error());
    // jika koneksi gagal, program akan berhenti dan menampilkan pesan error
}
?>