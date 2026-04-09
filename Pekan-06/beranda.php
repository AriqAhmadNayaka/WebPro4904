<?php
session_start();
require_once 'EcoTasteClasses.php';

if (!isset($_SESSION['isLoggedIn'])) { header("Location: login.php"); exit; }

$donasi = new Donasi();
$user_id = $_SESSION['user_id'];

if (isset($_POST['tambah'])) {
    $donasi->tambahData($user_id, $_POST, $_FILES);
    header("Location: beranda.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $donasi->hapusData($_GET['hapus'], $user_id);
    header("Location: beranda.php");
    exit;
}

$daftar_makanan = $donasi->tampilkanSemua($user_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - EcoTaste</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background: #4CAF50; color: white; padding: 15px 30px; border-radius: 10px; margin-bottom: 20px; }
        .header a { color: white; background: #d32f2f; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        form { display: grid; gap: 15px; grid-template-columns: 1fr 1fr 1fr 1fr auto; align-items: end; margin-bottom: 30px; }
        input { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; box-sizing: border-box;}
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;}
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; }
        th { background-color: #4CAF50; color: white; }
        img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .btn-edit { color: #1976D2; text-decoration: none; }
        .btn-hapus { color: #d32f2f; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2><i class="fas fa-leaf"></i> Dashboard EcoTaste</h2>
        <div>Halo, <?php echo $_SESSION['username']; ?>! <a href="logout.php">Logout</a></div>
    </div>
    <div class="container">
        <h3>Formulir Donasi Makanan</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div><label>Nama Makanan</label><input type="text" name="nama_makanan" required></div>
            <div><label>Jumlah</label><input type="text" name="jumlah" required></div>
            <div><label>Kedaluwarsa</label><input type="date" name="tanggal_kedaluwarsa" required></div>
            <div><label>Foto Makanan</label><input type="file" name="gambar" accept="image/*" required></div>
            <button type="submit" name="tambah">Simpan</button>
        </form>

        <h3>Daftar Makanan</h3>
        <table>
            <tr>
                <th>Foto</th><th>Nama Makanan</th><th>Jumlah</th><th>Kedaluwarsa</th><th>Status</th><th>Aksi</th>
            </tr>
            <?php foreach ($daftar_makanan as $row): ?>
            <tr>
                <td><img src="uploads/<?php echo $row['gambar']; ?>"></td>
                <td><?php echo $row['nama_makanan']; ?></td>
                <td><?php echo $row['jumlah']; ?></td>
                <td><?php echo $row['tanggal_kedaluwarsa']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                    <a href="beranda.php?hapus=<?php echo $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>