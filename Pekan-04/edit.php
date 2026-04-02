<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM beranda WHERE id=$id");// Query untuk mengambil data kuliner 
$row = mysqli_fetch_assoc($data);// Mengambil data kuliner sebagai array asosiatif

if (isset($_POST['submit'])) {// Cek apakah tombol submit ditekan
    $nama = $_POST['nama'];
    $desk = $_POST['deskripsi'];
    $rate = $_POST['rating'];

    mysqli_query($conn, "UPDATE beranda SET nama='$nama', deskripsi='$desk', rating='$rate' WHERE id=$id");// Query untuk memperbarui data kuliner berdasarkan ID
    header("location:index.php");
}
?>