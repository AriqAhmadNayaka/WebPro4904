<?php
include "koneksi.php";

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM catatan WHERE id='$id'");
$d = mysqli_fetch_array($data);

if(isset($_POST['update'])){
    mysqli_query($conn, "UPDATE catatan SET 
        judul='$_POST[judul]',
        isi='$_POST[isi]'
        WHERE id='$id'");
    header("Location: dashboard.php");
}
?>

<link rel="stylesheet" href="login.css">

<div class="form-box" style="margin:120px auto;">

<h2>Edit Data</h2>

<form method="POST">
    <input type="text" name="judul" class="input" value="<?= $d['judul']; ?>">
    <textarea name="isi" class="input"><?= $d['isi']; ?></textarea>
    <button name="update" class="btn-login">Update</button>
</form>

</div>