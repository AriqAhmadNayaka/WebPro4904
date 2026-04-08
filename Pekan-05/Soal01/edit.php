<?php
include "koneksi.php";
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM catatan WHERE id='$id'");
$d = mysqli_fetch_array($data);

if(isset($_POST['update'])){
    $judul = $_POST['judul'];
    $isi   = $_POST['isi'];
    $nama_file = $_FILES['gambar']['name'];
    $source    = $_FILES['gambar']['tmp_name'];
    $folder    = 'uploads/';

    if($nama_file != ""){ // Jika ganti foto
        move_uploaded_file($source, $folder.$nama_file);
        mysqli_query($conn, "UPDATE catatan SET judul='$judul', isi='$isi', gambar='$nama_file' WHERE id='$id'");
    } else { // Jika hanya ganti teks
        mysqli_query($conn, "UPDATE catatan SET judul='$judul', isi='$isi' WHERE id='$id'");
    }
    header("Location: dashboard.php");
}
?>

<link rel="stylesheet" href="login.css">
<div class="form-box" style="margin:120px auto;">
    <h2>Edit Profil</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="judul" class="input" value="<?= $d['judul']; ?>">
        <textarea name="isi" class="input"><?= $d['isi']; ?></textarea>
        <p style="font-size: 12px; color: var(--text-muted);">Ganti Foto (Kosongkan jika tidak ingin diubah):</p>
        <input type="file" name="gambar" class="input">
        <button name="update" class="btn-login">Update Profil</button>
    </form>
</div>