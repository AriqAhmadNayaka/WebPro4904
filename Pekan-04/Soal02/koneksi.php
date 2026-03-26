<?php
$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>