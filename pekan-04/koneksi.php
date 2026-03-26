<?php
global $conn;

$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "srikandi";

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn ) {
    die("Koneksi gagal: " . mysqli_connect_error());
}