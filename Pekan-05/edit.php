<?php
session_start();
require 'config.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil data lama
$query = $conn->query("SELECT * FROM donasi_makanan WHERE id='$id' AND user_id='$user_id'");
$data = $query->fetch_assoc();

// Proses UPDATE data
if (isset($_POST['update'])) {
    $nama_makanan = $_POST['nama_makanan'];
    $jumlah = $_POST['jumlah'];
    $tanggal_kedaluwarsa = $_POST['tanggal_kedaluwarsa'];
    $status = $_POST['status'];
    
    // Cek apakah user mengupload gambar baru
    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $fotobaru = date('d-m-Y_H-i-s').'_'.$gambar;
        $path = "uploads/".$fotobaru;

        if(move_uploaded_file($tmp, $path)) {
            // Hapus foto lama
            if(is_file("uploads/".$data['gambar'])) {
                unlink("uploads/".$data['gambar']);
            }
            // Update dengan foto baru
            $stmt = $conn->prepare("UPDATE donasi_makanan SET nama_makanan=?, jumlah=?, tanggal_kedaluwarsa=?, status=?, gambar=? WHERE id=?");
            $stmt->bind_param("sssssi", $nama_makanan, $jumlah, $tanggal_kedaluwarsa, $status, $fotobaru, $id);
        }
    } else {
        // Update tanpa ganti foto
        $stmt = $conn->prepare("UPDATE donasi_makanan SET nama_makanan=?, jumlah=?, tanggal_kedaluwarsa=?, status=? WHERE id=?");
        $stmt->bind_param("ssssi", $nama_makanan, $jumlah, $tanggal_kedaluwarsa, $status, $id);
    }
    
    $stmt->execute();
    header("Location: beranda.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Donasi - EcoTaste</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .edit-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 400px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .input-group input, .input-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn-update { background: #4CAF50; color: white; border: none; padding: 10px; width: 100%; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px;}
        .btn-batal { display: block; text-align: center; color: #888; text-decoration: none; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="edit-box">
        <h2>Edit Data Donasi</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Nama Makanan</label>
                <input type="text" name="nama_makanan" value="<?php echo $data['nama_makanan']; ?>" required>
            </div>
            <div class="input-group">
                <label>Jumlah</label>
                <input type="text" name="jumlah" value="<?php echo $data['jumlah']; ?>" required>
            </div>
            <div class="input-group">
                <label>Tanggal Kedaluwarsa</label>
                <input type="date" name="tanggal_kedaluwarsa" value="<?php echo $data['tanggal_kedaluwarsa']; ?>" required>
            </div>
            <div class="input-group">
                <label>Status</label>
                <select name="status">
                    <option value="Tersedia" <?php if($data['status'] == 'Tersedia') echo 'selected'; ?>>Tersedia</option>
                    <option value="Diambil" <?php if($data['status'] == 'Diambil') echo 'selected'; ?>>Diambil</option>
                </select>
            </div>
            <div class="input-group">
                <label>Ganti Foto (Kosongkan jika tidak diganti)</label>
                <input type="file" name="gambar" accept="image/*">
            </div>
            <button type="submit" name="update" class="btn-update">Simpan Perubahan</button>
            <a href="beranda.php" class="btn-batal">Batal</a>
        </form>
    </div>
</body>
</html>