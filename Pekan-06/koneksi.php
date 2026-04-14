<?php
$conn = mysqli_connect("localhost", "root", "", "nama_db_anda");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }
?>