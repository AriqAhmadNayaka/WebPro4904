<?php include 'koneksi.php'; ?>

<h2>Tambah Destinasi</h2>

<form method="POST">
    Nama: <input type="text" name="nama"><br><br>
    Lokasi: <input type="text" name="lokasi"><br><br>
    Deskripsi: <textarea name="deskripsi"></textarea><br><br>
    <button type="submit" name="simpan">Simpan</button>
</form>

<?php
if (isset($_POST['simpan'])) {
    mysqli_query($conn, "INSERT INTO destinasi 
    VALUES('', '$_POST[nama]', '$_POST[lokasi]', '$_POST[deskripsi]', NOW())");

    header("Location: dashboard.php");
}
?>