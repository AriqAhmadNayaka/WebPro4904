<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

require_once __DIR__ . '/classes/DataUser.php';

$dataObj = new DataUser($_SESSION['user_id']);
$result = $dataObj->readAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - CyberVault</title>
    <link rel="stylesheet" href="login.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: rgba(6,6,19,0.8); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(0,212,255,0.2); }
        th { background: #0a0a0f; color: #00d4ff; }
        img { width: 55px; height: 55px; object-fit: cover; border-radius: 50%; border: 2px solid #00d4ff; }
        .btn { padding: 8px 14px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-right: 5px; }
        .btn-edit { background: #00ff88; color: black; }
        .btn-hapus { background: #ff3366; color: white; }
    </style>
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
    <h2 style="color:#00d4ff; margin-bottom:25px;">Daftar Data Pengguna</h2>
    
    <a href="tambah.php" style="background:#00d4ff; color:black; padding:12px 24px; border-radius:50px; text-decoration:none; font-weight:700;">+ Tambah Data Baru</a>

    <table>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php 
        $no = 1;
        if ($result->num_rows > 0) {
            while ($d = $result->fetch_assoc()) { 
        ?>
        <tr>
            <td><?= $no++ ?></td>
           <td>
    <?php if (!empty($d['foto']) && file_exists($d['foto'])): ?>
        <img src="<?= htmlspecialchars($d['foto']) ?>" 
             alt="Foto Profil"
             style="width:55px; height:55px; object-fit:cover; border-radius:50%; border:2px solid #00d4ff;">
    <?php else: ?>
        <div style="width:55px; height:55px; background:#444; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#888; font-size:12px;">
            No Foto
        </div>
    <?php endif; ?>
</td>
            <td><?= htmlspecialchars($d['nama']) ?></td>
            <td><?= htmlspecialchars($d['email']) ?></td>
            <td>
                <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-hapus" 
                   onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php 
            }
        } else {
        ?>
        <tr>
            <td colspan="5" style="text-align:center; padding:50px; color:#8892b0;">
                Belum ada data. Silakan tambah data baru.
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>