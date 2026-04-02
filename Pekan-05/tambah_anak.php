<?php
include 'koneksi.php';

//untuk mengecek apakah tombol submit sudah ditekan atau belum, jika sudah maka akan menjalankan ke database dengan data yang diambil dari form, lalu mengalihkan halaman ke index.php
if (isset($_POST['submit'])) {

    //untuk menambahkan data anak  seperti umur, jenis kelamin, jenis pelatihan, alergi ke database dengan data yang diambil dari form
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jenis_pelatihan = $_POST['jenis_pelatihan'];
    $alergi = $_POST['alergi'];

    mysqli_query($conn, "INSERT INTO anak VALUES (
        NULL,
        NULL,
        '$nama',
        '$umur',
        '$jenis_kelamin',
        '$jenis_pelatihan',
        '$alergi',
        NOW()
    )");

    header("Location: index.php");
    exit;
}
?>

<!--untuk menampilkan form tambah data anak dengan desain yang sudah dibuat di style.css-->
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Tambah Data Anak</h2>

<form method="POST">
    <label>Nama</label>
    <input type="text" name="nama" required>

    <label>Umur</label>
    <input type="number" name="umur" required>

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin">
        <option>Laki-laki</option>
        <option>Perempuan</option>
    </select>

    <label>Jenis Pelatihan</label>
    <select name="jenis_pelatihan">
        <option value="">-- Pilih Pelatihan --</option>
        <option value="Barista">Barista</option>
        <option value="Membatik">Membatik</option>
        <option value="Hidroponik">Hidroponik</option>
        <option value="Tata Boga">Tata Boga</option>
        <option value="Menjahit">Menjahit</option>
        <option value="Tata Graha">Tata Graha</option>
    </select>

    <label>Alergi</label>
    <input type="text" name="alergi">

    <button type="submit" name="submit">Simpan</button>
</form>

<br>
<a href="index.php">Kembali</a>

</body>
</html>