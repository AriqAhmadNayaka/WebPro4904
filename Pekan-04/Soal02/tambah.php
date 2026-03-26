<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";

if(isset($_POST['simpan'])){
    mysqli_query($conn, "INSERT INTO catatan (judul, isi) 
VALUES ('$_POST[judul]', '$_POST[isi]')");
    header("Location: dashboard.php");
}
?>

<link rel="stylesheet" href="login.css">

<div class="form-box" style="margin:120px auto;">

<h2>Tambah Data</h2>

<form method="POST">
    <input type="text" name="judul" class="input" placeholder="Judul">
    <textarea name="isi" class="input" placeholder="Isi"></textarea>
    <button name="simpan" class="btn-login">Simpan</button>
</form>

</div>