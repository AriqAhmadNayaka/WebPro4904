<?php
include 'PemeriksaKoneksi.php';
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM data_barang WHERE id=$id");
header("Location: data.php");
?>