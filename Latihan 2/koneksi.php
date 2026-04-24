<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "smartparking_report";

$conn = new mysqli($host, $username, $password, $database, 3307);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");