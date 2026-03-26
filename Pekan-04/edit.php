<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM destinasi WHERE id='$id'");
$d = mysqli_fetch_array($data);
?>

<h2>Edit Destinasi</h2>

<form method="POST">
    Nama: <input type="text" name="nama" value="<?php echo $d['nama']; ?>"><br><br>
    Lokasi: <input type="text" name="lokasi" value="<?php echo $d['lokasi']; ?>"><br><br>
    Deskripsi: <textarea name="deskripsi"><?php echo $d['deskripsi']; ?></textarea><br><br>
    <button type="submit" name="update">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
    mysqli_query($conn, "UPDATE destinasi SET
        nama='$_POST[nama]',
        lokasi='$_POST[lokasi]',
        deskripsi='$_POST[deskripsi]'
        WHERE id='$id'");

    header("Location: dashboard.php");
}
?>