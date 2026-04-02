<?php
global $koneksi; // agar koneksi bisa digunakan di file lain yang menginclude koneksi.php

$host       = "localhost"; //nama host database
$username   = "root"; //username database
$password   = "";   //password database
$namadb     = "srikandi"; //nama database

$koneksi = mysqli_connect($host, $username, $password, $namadb); //membuat koneksi dengan fungsi mysqli_connect dengan parameter host, username, password, dan nama database

if(!$koneksi) { //melakukan pengecekan apakah koneksi jalan atau tidak
    die ("koneksi gagal" . mysqli_connect_error()); //kalau tidak maka memunculkan pesan error dengan fungsi die dan mysqli_connect_error untuk menampilkan error yang terjadi
}

?>