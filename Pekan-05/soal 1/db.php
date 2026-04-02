<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "nuids_db";

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Koneksi Gagal! " . $conn->connect_error);
}
