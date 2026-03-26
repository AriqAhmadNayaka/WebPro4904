<?php
include 'PemeriksaKoneksi.php';
?>

<h2>Data Barang</h2>

<form method="POST">
    Nama: <input type="text" name="nama"><br>
    Harga: <input type="number" name="harga"><br>
    Stok: <input type="number" name="stok"><br>
    <button name="simpan">Simpan</button>
</form>

<?php
if (isset($_POST['simpan'])) {
    mysqli_query($conn, "INSERT INTO data_barang VALUES('', '$_POST[nama]', '$_POST[harga]', '$_POST[stok]')");
}
?>

<table border="1">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;
$data = mysqli_query($conn, "SELECT * FROM data_barang");
while ($d = mysqli_fetch_array($data)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama_barang'] ?></td>
    <td><?= $d['harga'] ?></td>
    <td><?= $d['stok'] ?></td>
    <td>
        <a href="edit.php?id=<?= $d['id'] ?>">Edit</a>
        <a href="hapus.php?id=<?= $d['id'] ?>">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>