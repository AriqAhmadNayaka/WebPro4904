<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: Login.php");
    exit;
}

include "koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<header class="main-header">
    <nav class="nav-bar">
        <a href="#" class="logo">CyberVault</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="tambah.php">Tambah Data</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div style="padding:120px 40px;">

    <h2 style="color:#00d4ff; margin-bottom:20px;">Data Catatan</h2>

    <table border="1" cellpadding="10" style="width:100%;">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Keluhan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $data = mysqli_query($conn, "SELECT * FROM catatan");

        while($d = mysqli_fetch_array($data)){
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $d['judul']; ?></td>
            <td><?= $d['isi']; ?></td>
            <td>
                <a href="edit.php?id=<?= $d['id']; ?>">Edit</a>
                <a href="hapus.php?id=<?= $d['id']; ?>">Hapus</a>
            </td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>