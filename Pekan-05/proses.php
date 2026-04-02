<?php
$conn = mysqli_connect("localhost", "root", "", "db_pasien");

$nama = $_POST['nama']; //ambil data dari html
$tanggal = $_POST['tanggal']; 
$jk = $_POST['jk'];
$berat = $_POST['berat'];
$tinggi = $_POST['tinggi'];
$lingkar = $_POST['lingkar'];

$namaFile = $_FILES['file']['name']; //ambil nama file yang diupload
$tmp = $_FILES['file']['tmp_name']; //ambil lokasi file sementara

move_uploaded_file($tmp, "upload/" . $namaFile); //untuk memindahkan file ke folder

$query = "INSERT INTO pasien 
(nama, tanggal, jk, berat, tinggi, lingkar, file) 
VALUES 
('$nama','$tanggal','$jk','$berat','$tinggi','$lingkar','$namaFile')"; //query untuk menyimpan data ke database

mysqli_query($conn, $query); //untuk menjalankan query ke database

echo "sukses"; //kirim respon ke JavaScript
?>