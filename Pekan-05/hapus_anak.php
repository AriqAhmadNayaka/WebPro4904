<?php
include 'koneksi.php'; // Koneksi ke database

$id = $_GET['id']; //untuk mengambil id dari url

mysqli_query($conn, "DELETE FROM anak WHERE id='$id'"); //untuk menjalankan query delete ke database dengan id yang diambil dari url

header("Location: index.php"); //untuk mengalihkan halaman ke index.php setelah data berhasil dihapus
?>