<?php
include 'koneksi.php'; 
$id = $_GET['id'];// Mengambil ID dari URL
$data = mysqli_query($conn, "SELECT * FROM wishlist WHERE id=$id");// Mengambil data dari tabel wishlist berdasarkan ID 
$row = mysqli_fetch_assoc($data);
if(isset($_POST['update'])){// Mengambil data dari form input
    $nama = $_POST['nama_tempat'];
    $lokasi = $_POST['lokasi'];
    $harga = $_POST['harga_tiket'];
    mysqli_query($conn, "UPDATE wishlist SET  
        nama_tempat='$nama',
        lokasi='$lokasi',
        harga_tiket='$harga'
        WHERE id=$id
    "); //Untuk mengupdate data di database
    header("Location: dasboard.php");
    exit;
}
?>

<h2>Edit Tempat</h2>

<form method="POST">
    <!-- Input untuk nama tempat, otomatis terisi dari database -->
    <label>Nama Tempat:</label><br>
    <input type="text" name="nama_tempat" value="<?= $row['nama_tempat']; ?>" required><br><br>
    <label>Lokasi:</label><br>
    <input type="text" name="lokasi" value="<?= $row['lokasi']; ?>" required><br><br>
    <label>Harga Tiket:</label><br>
    <input type="number" name="harga_tiket" value="<?= $row['harga_tiket']; ?>" required><br><br>
    <button type="submit" name="update">Update</button>
<!-- Menginput nama ,lokasi,harga untuk di update datanya-->
</form>