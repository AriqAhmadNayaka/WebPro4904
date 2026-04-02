<?php
include 'koneksi.php'; // Koneksi ke database

$id = $_GET['id']; //untuk mengambil id dari url
$data = mysqli_query($conn, "SELECT * FROM anak WHERE id='$id'");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) { //untuk mengecek apakah tombol update sudah ditekan atau belum

    //untuk menjalankan query update ke database dengan data yang diambil dari form
    mysqli_query($conn, "UPDATE anak SET 
        nama='$_POST[nama]',
        umur='$_POST[umur]',
        jenis_kelamin='$_POST[jenis_kelamin]',
        jenis_pelatihan='$_POST[jenis_pelatihan]',
        alergi='$_POST[alergi]'
        WHERE id='$id'
    ");

    header("Location: index.php");
}
?>

<!--untuk menampilkan form edit data anak dengan data yang sudah diambil dari database-->
<!DOCTYPE html>
<html>
<head>
    <title>Edit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Data Anak</h2>

<!--untuk menampilkan form edit data anak dengan data yang sudah diambil dari database-->
<form method="POST">
    <label>Nama</label>
    <input type="text" name="nama" value="<?= $row['nama']; ?>">

    <label>Umur</label>
    <input type="number" name="umur" value="<?= $row['umur']; ?>">

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin">
        <option <?= $row['jenis_kelamin']=="Laki-laki"?"selected":""; ?>>Laki-laki</option>
        <option <?= $row['jenis_kelamin']=="Perempuan"?"selected":""; ?>>Perempuan</option>
    </select>

    <label>Jenis Pelatihan</label>
    <select name="jenis_pelatihan">
        <option <?= $row['jenis_pelatihan']=="Barista"?"selected":""; ?>>Barista</option>
        <option <?= $row['jenis_pelatihan']=="Membatik"?"selected":""; ?>>Membatik</option>
        <option <?= $row['jenis_pelatihan']=="Hidroponik"?"selected":""; ?>>Hidroponik</option>
        <option <?= $row['jenis_pelatihan']=="Tata Boga"?"selected":""; ?>>Tata Boga</option>
        <option <?= $row['jenis_pelatihan']=="Menjahit"?"selected":""; ?>>Menjahit</option>
        <option <?= $row['jenis_pelatihan']=="Tata Graha"?"selected":""; ?>>Tata Graha</option>
    </select>

    <label>Alergi</label>
    <input type="text" name="alergi" value="<?= $row['alergi']; ?>">

    <button type="submit" name="update">Update</button>
</form>

<br>
<a href="index.php">Kembali</a>

</body>
</html>