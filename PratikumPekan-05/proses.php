<?php
include 'config.php';

// SIMPAN + UPLOAD
if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $qty = $_POST['qty'];
    $harga = $_POST['harga'];

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    move_uploaded_file($tmp, "upload/" . $file);

    mysqli_query($conn, "INSERT INTO barang VALUES ('','$nama','$qty','$harga','$file')");

    header("location:index.php");
}

// HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");
    header("location:index.php");
}
?>