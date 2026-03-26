<?php
include 'koneksi.php';
if(isset($_POST['simpan'])){
    $nama = $_POST['nama_tempat'];
    $lokasi = $_POST['lokasi'];
    $harga = $_POST['harga_tiket'];// Mengambil data dari input form
    mysqli_query($conn, "INSERT INTO wishlist (nama_tempat, lokasi, harga_tiket) 
    VALUES ('$nama','$lokasi','$harga')");// Query untuk menambahkan data ke tabel wishlist
    header("Location: dasboard.php");//Kembali ke halaman dashboard setelah selesai nginput data
    exit;
}
?>
<h2>Tambah Tempat</h2>
<form method="POST">
    <label>Nama Tempat:</label><br>
    <input type="text" name="nama_tempat" required><br><br>
    <label>Lokasi:</label><br>
    <input type="text" name="lokasi" required><br><br>
    <label>Harga Tiket:</label><br>
    <input type="number" name="harga_tiket" required><br><br>
<!--Menginput data yang akan di list-->
    <button type="submit" name="simpan">Simpan</button>

</form>