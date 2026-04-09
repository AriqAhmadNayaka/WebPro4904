<?php
session_start();
require_once 'EcoTasteClasses.php';

if (!isset($_SESSION['isLoggedIn'])) { header("Location: login.php"); exit; }

$donasi = new Donasi();
$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

// Proses Update
if (isset($_POST['update'])) {
    $donasi->editData($id, $user_id, $_POST, $_FILES);
    header("Location: beranda.php");
    exit;
}

$data = $donasi->getDonasiById($id, $user_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit - EcoTaste</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 30px; border-radius: 10px; width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px;}
        .batal { display: block; text-align: center; color: #888; text-decoration: none; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Edit Data Donasi</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="group"><label>Nama Makanan</label><input type="text" name="nama_makanan" value="<?php echo $data['nama_makanan']; ?>" required></div>
            <div class="group"><label>Jumlah</label><input type="text" name="jumlah" value="<?php echo $data['jumlah']; ?>" required></div>
            <div class="group"><label>Kedaluwarsa</label><input type="date" name="tanggal_kedaluwarsa" value="<?php echo $data['tanggal_kedaluwarsa']; ?>" required></div>
            <div class="group">
                <label>Status</label>
                <select name="status">
                    <option value="Tersedia" <?php if($data['status']=='Tersedia') echo 'selected'; ?>>Tersedia</option>
                    <option value="Diambil" <?php if($data['status']=='Diambil') echo 'selected'; ?>>Diambil</option>
                </select>
            </div>
            <div class="group"><label>Ganti Foto (Opsional)</label><input type="file" name="gambar" accept="image/*"></div>
            <button type="submit" name="update">Simpan Perubahan</button>
            <a href="beranda.php" class="batal">Batal</a>
        </form>
    </div>
</body>
</html>