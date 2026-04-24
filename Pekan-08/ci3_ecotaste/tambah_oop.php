<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

require 'database.php';
$db = new Database();
$error = '';

if (isset($_POST['simpan'])) {
    if ($db->saveMenu($_POST, $_FILES)) {
        echo "<script>
                alert('Data & Gambar Berhasil Disimpan!');
                window.location='homepage.php';
              </script>";
    } else {
        $error = 'Data gagal disimpan. Pastikan semua field terisi dan file gambar valid.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Menu EcoTaste</title>
    <style>
        body { font-family: sans-serif; background: #F4F9F4; padding: 50px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; width: 450px; margin: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { color: #4CAF50; text-align: center; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0 15px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-batal { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <div class="form-box">
        <h2>Tambah Menu Kuliner</h2>
        <?php if ($error !== '') : ?>
            <p style="color:#d32f2f; text-align:center;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label>Nama Kuliner:</label>
            <input type="text" name="nama" placeholder="Contoh: Batagor" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4" placeholder="Jelaskan menunya..." required></textarea>

            <label>Rating:</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" placeholder="Contoh: 4.5" required>

            <label>Foto Menu:</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif,.webp" required>

            <button type="submit" name="simpan">Simpan</button>
            <a href="homepage.php" class="btn-batal">Batal</a>
        </form>
    </div>

</body>
</html>
