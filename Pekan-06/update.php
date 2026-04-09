<?php
include "koneksi.php"; //Menghubungkan file koneksi.php
include "Pasien.php"; //Menghubungkan file Pasien.php

$db = new Koneksi(); //Membuat objek koneksi ke database
$pasien = new Pasien($db->conn); //Membuat objek Pasien dengan parameter koneksi database

$id = $_GET['id']; //Mengambil nilai id dari URL

$pasien->update($id, $_POST, $_FILES['file']); //Mengupdate data pasien berdasarkan id

header("Location: inputdata.php"); //Setelah update selesai, halaman dialihkan ke inputdata.php
?>