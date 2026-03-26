<?php
include 'koneksi.php';

// ambil id dari URL
$id = $_GET['id'];

// hapus data dari database
mysqli_query($conn, "DELETE FROM wishlist WHERE id=$id");

// kembali ke dashboard
header("Location: dasboard.php");
exit;
?>