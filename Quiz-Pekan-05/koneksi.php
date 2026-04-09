<?php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'webpro';

$koneksi = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
