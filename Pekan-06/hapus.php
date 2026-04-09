<?php
session_start();
if(!isset($_SESSION['user_login'])){
    header("Location: login.php"); 
    exit;
}
include 'koneksi.php';
$id = $_GET['id'];// ambil id dari URL (data yang mau dihapus)
$query = mysqli_query($conn, "SELECT * FROM wishlist_wisata WHERE id='$id'");// ambil data berdasarkan id (buat ambil nama file gambar)
$row = mysqli_fetch_assoc($query);
// cek apakah file gambar ada di folder upload
if(file_exists("upload/".$row['gambar'])){
    unlink("upload/".$row['gambar']);// cek apakah file gambar ada di folder upload jika ada maka akan di hapus 
}
mysqli_query($conn,"DELETE FROM wishlist_wisata WHERE id='$id'");// hapus data dari database berdasarkan id
header("Location: whislist.php");//kembali ke halaman wisslhis 
exit;
?>