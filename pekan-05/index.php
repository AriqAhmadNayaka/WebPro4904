<?php
include 'koneksi.php';

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$edit_data = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM konten_edukasi WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Konten</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        table th {
            background: #28a745;
            color: white;
        }

        img {
            border-radius: 5px;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }

        .edit {
            background: #007bff;
        }

        .hapus {
            background: #dc3545;
        }
    </style>
</head>
<body>

<h2>Kelola Konten Edukasi</h2>

<div class="card">
    <h3><?= $edit_data ? "Edit Konten" : "Tambah Konten Baru" ?></h3>

    <form action="proses.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e($edit_data['id']) ?>">

        <label>Judul</label>
        <input type="text" name="judul" value="<?= e($edit_data['judul']) ?>" required>

        <label>Kategori</label>
        <select name="kategori">
            <option <?= ($edit_data['kategori'] ?? '')=='Edukasi'?'selected':'' ?>>Edukasi</option>
            <option <?= ($edit_data['kategori'] ?? '')=='Tips Gizi'?'selected':'' ?>>Tips Gizi</option>
            <option <?= ($edit_data['kategori'] ?? '')=='Resep Sehat'?'selected':'' ?>>Resep Sehat</option>
        </select>

        <label>Deskripsi</label>
        <textarea name="deskripsi"><?= e($edit_data['deskripsi']) ?></textarea>

        <label>Gambar</label>
        <input type="file" name="gambar">

        <?php if (!empty($edit_data['gambar'])): ?>
            <img src="uploads/<?= e($edit_data['gambar']) ?>" width="100">
        <?php endif; ?>

        <br><br>
        <button type="submit" name="simpan">Simpan</button>
    </form>
</div>

<div class="card">
    <h3>Data Konten</h3>

    <table>
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>

        <?php
        $data = mysqli_query($conn, "SELECT * FROM konten_edukasi ORDER BY id DESC");
        while ($row = mysqli_fetch_assoc($data)):
        ?>
        <tr>
            <td>
                <?php if ($row['gambar']): ?>
                    <img src="uploads/<?= e($row['gambar']) ?>" width="60">
                <?php endif; ?>
            </td>
            <td><?= e($row['judul']) ?></td>
            <td><?= e($row['kategori']) ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>" class="btn edit">Edit</a>
                <a href="proses.php?hapus=<?= $row['id'] ?>" class="btn hapus"
                   onclick="return confirm('Hapus data?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>