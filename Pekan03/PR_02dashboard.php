<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: PR_02login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard - Web Desa</title></head>
<body>
<h2>Selamat datang di Web Desa</h2>
<p>Halo, <?= $_SESSION['user']; ?>! Anda berhasil login.</p>

<h3>Informasi Desa</h3>
<ul>
    <li>Nama Desa: Tanjung Beringin</li>
    <li>Kabupaten: Dairi</li>
    <li>Jumlah Penduduk: 19.038 jiwa</li>
    <li>Potensi Desa: Pertanian, UMKM, Wisata Alam</li>
</ul>

<a href="PR_02login.php">Logout</a>
</body>
</html>