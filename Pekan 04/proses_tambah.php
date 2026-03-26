<?php
include 'koneksi.php';

$nama = $_POST['nama_anak'];
$kategori = $_POST['kategori'];

$conn->query("INSERT INTO anak (nama_anak, kategori) VALUES ('$nama', '$kategori')");

header("Location: tampil_anak.php");
?>