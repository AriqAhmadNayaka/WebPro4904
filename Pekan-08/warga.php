<?php
// Session tetap dipakai kalau nanti butuh nyimpen status user.
session_start();
require_once 'koneksi.php';
require_once 'halaman_warga.php';

// Bikin object database dan object warga sekali di awal.
$database = new Database();
$wargaObj = new Warga($database->conn);

// Kalau tombol simpan ditekan, berarti lagi nambah data baru.
if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama'] ?? ''; 
    $alamat = $_POST['alamat'] ?? '';
    $nohp   = $_POST['nohp'] ?? '';

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = $_FILES['file']['name'] ?? '';
    $targetFile = $targetDir . basename($fileName);

    // File dipindah dulu, habis itu baru data disimpan ke database.
    if (!empty($fileName) && move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        $wargaObj->create($nama, $alamat, $nohp, $fileName);
        header("Location: warga.php");
        exit();
    } else {
        echo "<script>alert('Upload gagal!');</script>";
    }
}

// Kalau ada parameter hapus, langsung hapus data yang dipilih.
if (isset($_GET['hapus'])) {
    $wargaObj->delete($_GET['hapus']);
    header("Location: warga.php");
    exit();
}

// Ini dipakai kalau form edit disubmit.
if (isset($_POST['update'])) {
    $wargaObj->update($_POST['id'], $_POST['nama'], $_POST['alamat'], $_POST['nohp']);
    header("Location: warga.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Warga (OOP)</title>
</head>
<body>
<div class="container">
    <h2>Kelola Data Warga</h2>

    <!-- Form paling atas buat nambah data warga baru -->
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="alamat" placeholder="Alamat" required>
        <input type="text" name="nohp" placeholder="No HP" required>
        <input type="file" name="file" required>
        <button type="submit" name="simpan">Simpan</button>
    </form>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Nama</th><th>Alamat</th><th>No HP</th><th>File</th><th>Aksi</th>
        </tr>
        <?php
        // Ambil semua data warga lalu tampilkan satu per satu ke tabel.
        $dataWarga = $wargaObj->getAll();
        while ($row = mysqli_fetch_assoc($dataWarga)) {
            echo "<tr>
                    <td>{$row['nama']}</td>
                    <td>{$row['alamat']}</td>
                    <td>{$row['nohp']}</td>
                    <td><a href='uploads/{$row['file']}' download>Download</a></td>
                    <td>
                        <a href='halaman_warga.php?hapus={$row['id']}' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
