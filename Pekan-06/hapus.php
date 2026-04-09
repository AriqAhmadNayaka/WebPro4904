<?php
include "koneksi.php"; //Menghubungkan file koneksi.php
include "Pasien.php"; //Menghubungkan file Pasien.php

$db = new Koneksi(); //Membuat objek dari class Koneksi untuk membuka koneksi ke database
$pasien = new Pasien($db->conn); //Membuat objek Pasien dan mengirimkan koneksi database ke dalamnya

$id = $_GET['id']; //Mengambil nilai id dari URL
$pasien->hapus($id); //Memanggil method hapus() pada class Pasien untuk menghapus data pasien berdasarkan id

header("Location: inputdata.php"); //Setelah data dihapus, halaman akan diarahkan kembali ke inputdata.php
?>