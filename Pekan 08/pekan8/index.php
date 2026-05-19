<?php
session_start();
// Redirect ke login jika belum login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require_once 'Proyek.php'; // Muat kelas Proyek (yang sudah muat Database.php di dalamnya)

// Ambil koneksi database menggunakan Singleton Database
$conn = Database::getInstance()->getConnection();

// Buat objek Proyek dengan menyuntikkan koneksi (Dependency Injection)
$proyekObj = new Proyek($conn);

// --- Logika Hapus ---
if (isset($_GET['hapus'])) {
    $proyekObj->delete((int) $_GET['hapus']); // Panggil method delete()
    header("Location: index.php");
    exit;
}

// --- Logika Simpan (Create & Update) ---
if (isset($_POST['simpan'])) {
    $id         = (int) $_POST['id'];
    $nama       = $_POST['nama_proyek'];
    $deskripsi  = $_POST['deskripsi'];
    $file       = $_FILES['berkas'];
    $fileLama   = $_POST['file_lama'];

    if ($id) {
        // Jika ada ID → mode Update
        $proyekObj->update($id, $nama, $deskripsi, $file, $fileLama);
    } else {
        // Jika tidak ada ID → mode Create
        $proyekObj->create($nama, $deskripsi, $file);
    }

    header("Location: index.php");
    exit;
}

// --- Logika Edit (Ambil data ke form) ---
$editData = ['id' => '', 'nama_proyek' => '', 'deskripsi' => '', 'nama_file' => ''];
if (isset($_GET['edit'])) {
    // Panggil method getById() untuk mengisi form dengan data yang ada
    $editData = $proyekObj->getById((int) $_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Proyek</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Manajemen Proyek</h2>
    <a href="login.php" class="logout-btn">Logout</a>
    <hr>

    <!-- Form Create / Update -->
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id"       value="<?= $editData['id'] ?>">
        <input type="hidden" name="file_lama" value="<?= $editData['nama_file'] ?>">

        <input type="text" name="nama_proyek"
               placeholder="Nama Proyek"
               value="<?= $editData['nama_proyek'] ?>" required>

        <textarea name="deskripsi" placeholder="Deskripsi"><?= $editData['deskripsi'] ?></textarea>

        <input type="file" name="berkas">
        <button type="submit" name="simpan">SIMPAN DATA &amp; UPLOAD</button>
    </form>

    <hr>

    <!-- Tabel Daftar Proyek -->
    <table border="1" cellpadding="10">
        <tr>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>File</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Panggil method getAll() untuk mengambil seluruh data proyek
        $data = $proyekObj->getAll();
        while ($row = mysqli_fetch_assoc($data)):
        ?>
        <tr>
            <td><?= $row['nama_proyek'] ?></td>
            <td><?= $row['deskripsi'] ?></td>
            <td>
                <a href="uploads/<?= $row['nama_file'] ?>" target="_blank">
                    <?= $row['nama_file'] ?>
                </a>
            </td>
            <td>
                <a href="?edit=<?= $row['id'] ?>">Edit</a> |
                <a href="?hapus=<?= $row['id'] ?>"
                   onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>