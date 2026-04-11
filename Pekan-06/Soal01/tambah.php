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

if (isset($_POST['simpan'])) {
    $nama  = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($nama) || empty($email)) {
        echo "<script>alert('Nama dan Email tidak boleh kosong!');</script>";
    } else {
        if ($dataObj->create($nama, $email, $_FILES['foto'] ?? null)) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "<script>alert('Gagal menyimpan data! Cek apakah folder uploads bisa ditulis.');</script>";
}
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data - CyberVault</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<header class="main-header">
    <nav class="nav-bar">
        <a href="dashboard.php" class="logo">CyberVault</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="tambah.php">Tambah Data</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="form-box" style="margin:120px auto; max-width:600px;">
    <h2>Tambah Data Pengguna</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nama" class="input" placeholder="Nama Lengkap" required>
        
        <input type="email" name="email" class="input" placeholder="Email" required>
        
        <label style="color:#8892b0; font-size:14px; margin:15px 0 8px; display:block;">
            Upload Foto Profil (opsional)
        </label>
        <input type="file" name="foto" class="input" accept="image/*">
        
        <button type="submit" name="simpan" class="btn-login">Simpan Data</button>
    </form>
    
    <div style="text-align:center; margin-top:20px;">
        <a href="dashboard.php" style="color:#00d4ff;">← Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>