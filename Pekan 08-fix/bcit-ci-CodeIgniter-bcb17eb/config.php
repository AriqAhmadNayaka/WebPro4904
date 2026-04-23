<?php
// Baris ini tugasnya 'nyambungin' kode PHP kita ke database MySQL.
// "localhost" (lokasi server), "root" (user database), "" (password kosong karna pakai xampp), "tugas_besar" (nama database).
$conn = mysqli_connect("localhost", "root", "", "tugas_besar");
// Kalau koneksi gagal (misal: nama database salah), aplikasi bakal 'mati' (die) dan kasih tau errornya apa.
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());
?>