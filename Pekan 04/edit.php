<?php
include 'PemeriksaKoneksi.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM data_barang WHERE id=$id");
$d = mysqli_fetch_array($data);
?>

<form method="POST">
    Nama: <input type="text" name="nama" value="<?= $d['nama_barang'] ?>"><br>
    Harga: <input type="number" name="harga" value="<?= $d['harga'] ?>"><br>
    Stok: <input type="number" name="stok" value="<?= $d['stok'] ?>"><br>
    <button name="update">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
    mysqli_query($conn, "UPDATE data_barang SET 
        nama_barang='$_POST[nama]',
        harga='$_POST[harga]',
        stok='$_POST[stok]'
        WHERE id=$id");

    header("Location: data.php");
}
?>