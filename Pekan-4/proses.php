<?php
include 'koneksi.php'; //mengambil file koneksi database
    //mengambil data dari HTML
    $name = $_POST["name"];
    $addres = $_POST["addres"];
    $number = $_POST["number"];
    $gender = $_POST["gender"];
    $date = $_POST["date"];
    $weight = $_POST["weight"];
    $height = $_POST["height"];
    $jeniskanker = $_POST["text"];

    //menyimpan data ke tabel pasien
$conn->query("INSERT INTO pasien 
(name, addres, number, gender, date, weight, height, jeniskanker) 
VALUES 
(('$name', '$addres', '$number', '$gender', '$date', '$weight', '$height', '$jeniskanker'))");

header("Location: dashboard.php"); //redirect halaman
?>
