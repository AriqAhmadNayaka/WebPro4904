<?php
include 'koneksi.php';
$id = $_GET['id']; 
// Query SQL untuk Hapus (Delete)
mysqli_query($conn, "DELETE FROM beranda WHERE id = '$id'");
header("location:homepage.php");
?>