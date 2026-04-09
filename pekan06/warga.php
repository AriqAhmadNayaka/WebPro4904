<?php
session_start();
require_once 'koneksi.php'; // Berisi class Database
require_once 'halaman_warga.php';   // Berisi class Warga

// Inisialisasi Objek
$database = new Database();
$wargaObj = new Warga($database->conn);

// PROSES CREATE
if (isset($_POST['simpan'])) { // Memeriksa apakah form penyimpanan data telah disubmit
    $nama   = $_POST['nama'] ?? ''; 
    $alamat = $_POST['alamat'] ?? '';
    $nohp   = $_POST['nohp'] ?? '';

    $targetDir = "uploads/"; // Direktori tempat menyimpan file yang diupload
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true); // Membuat direktori jika belum ada

    $fileName = $_FILES['file']['name'] ?? ''; // Nama file yang diupload
    $targetFile = $targetDir . basename($fileName); // Path lengkap untuk menyimpan file yang diupload

    if (!empty($fileName) && move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) { // Memeriksa apakah file berhasil diupload dan memindahkannya ke direktori tujuan
        $wargaObj->create($nama, $alamat, $nohp, $fileName); // Memanggil metode create dari objek Warga untuk menyimpan data warga baru ke database
        header("Location: warga.php"); // Refresh agar input tidak double
        exit();
    } else {
        echo "<script>alert('Upload gagal!');</script>"; // Menampilkan pesan kesalahan jika upload file gagal
    }
}

// PROSES DELETE
if (isset($_GET['hapus'])) { // Memeriksa apakah parameter hapus telah dikirim melalui metode GET
    $wargaObj->delete($_GET['hapus']); // Memanggil metode delete dari objek Warga untuk menghapus data warga berdasarkan ID yang dikirim melalui parameter hapus
    header("Location: warga.php");
    exit();
}

// PROSES UPDATE
if (isset($_POST['update'])) { // Memeriksa apakah form pembaruan data telah disubmit
    $wargaObj->update($_POST['id'], $_POST['nama'], $_POST['alamat'], $_POST['nohp']); // Memanggil metode update dari objek Warga untuk memperbarui data warga berdasarkan ID yang dikirim melalui form pembaruan
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
        $dataWarga = $wargaObj->getAll(); // Memanggil metode getAll dari objek Warga untuk mengambil semua data warga dari database
        while ($row = mysqli_fetch_assoc($dataWarga)) {  // Melakukan iterasi pada setiap baris data warga yang diambil dari database dan menampilkannya dalam tabel HTML
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