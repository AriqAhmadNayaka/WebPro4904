<?php
include "koneksi.php";

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM data WHERE id=$id"); //Menjalankan query DELETE ke database

header("Location: tampil.php"); //Setelah data dihapus → diarahkan ke halaman tampil.php
?>