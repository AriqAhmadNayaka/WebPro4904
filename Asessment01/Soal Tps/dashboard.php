<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require_once 'tampilan.php';

$conn = Database::getInstance()->getConnection();

$sampahObj = new sampah($conn);

if (isset($_GET['hapus'])) {
    $proyekObj->delete((int) $_GET['hapus']);
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengolahan sampah</title>
</head>
<body>
    <h2>Pengolahan Sampah</h2>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id"       value="<?= $editData['id'] ?>">
        <input type="hidden" name="bukti_lama" value="<?= $editData['nama_file'] ?>">

        <input type="text" name="deskripsi"
               placeholder="Deskripsi Sampah"
               value="<?= $editData['nama_proyek'] ?>" required>

        <textarea name="deskripsi" placeholder="Deskripsi"><?= $editData['deskripsi'] ?></textarea>

        <input type="file" name="berkas">
        <button type="submit" name="simpan">SIMPAN DATA &amp; UPLOAD</button>
    </form>
</body>
</html>
?>