<?php
$conn = new mysqli("localhost", "root", "", "inkluskill");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>