<?php
$conn = mysqli_connect("localhost", "root", "", "stok_barang");

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error9());
}
?>