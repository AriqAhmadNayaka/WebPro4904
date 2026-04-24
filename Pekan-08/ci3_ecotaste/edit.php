<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

require 'database.php';
$db = new Database();
$error = '';

if (!isset($_GET['id'])) {
    header('Location: homepage.php');
    exit;
}

$id = (int) $_GET['id'];
$row = $db->getMenuById($id);

if (!$row) {
    header('Location: homepage.php');
    exit;
}

if (isset($_POST['simpan'])) {
    if ($db->saveMenu($_POST, $_FILES)) {
        echo "<script>
                alert('Data berhasil diubah!');
                document.location.href = 'homepage.php';
              </script>";
    } else {
        $error = 'Data gagal diubah. Pastikan input valid dan file gambar sesuai format.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Menu EcoTaste</title>
    <style>
        body { font-family: sans-serif; background: #F4F9F4; padding: 50px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; width: 400px; margin: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { color: #4CAF50; text-align: center; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0 15px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .btn-batal { display: block; text-align: center; margin-top: 10px; color: #888; text-decoration: none; font-size: 14px; }
        .img-preview { width: 100px; margin-bottom: 10px; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="form-box">
        <h2>Edit Menu Kuliner</h2>
        <?php if ($error !== '') : ?>
            <p style="color:#d32f2f; text-align:center;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">

            <label>Nama Kuliner:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4" required><?= htmlspecialchars($row['deskripsi'], ENT_QUOTES, 'UTF-8'); ?></textarea>

            <label>Rating:</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" value="<?= htmlspecialchars((string) $row['rating'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label>Foto Saat Ini:</label><br>
            <?php if ($db->imageExists($row['gambar'])) : ?>
                <img src="<?= htmlspecialchars($db->getImageWebPath($row['gambar']), ENT_QUOTES, 'UTF-8'); ?>" class="img-preview" alt="<?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?>"><br>
            <?php else : ?>
                <small style="display:block; margin-bottom:10px; color:#666;">Belum ada gambar.</small>
            <?php endif; ?>

            <label>Ganti Foto Menu (Opsional):</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif,.webp">
            <small style="display:block; margin-bottom:10px; color:#666;">Kosongkan jika tidak ingin mengubah foto.</small>

            <button type="submit" name="simpan">Simpan</button>
            <a href="homepage.php" class="btn-batal">Batal</a>
        </form>
    </div>

</body>
</html>
