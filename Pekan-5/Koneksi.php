<?php

//membuat konfigurasi koneksi ke database//
$host = "localhost";
$username = "root";
$password = "";
$database = "web_pro";

//membuat koneksi ke MySQL//
$conn = new mysqli($host, $username, $password, $database);

//mengecak apakah konfigurasi sudah berhasil//
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>