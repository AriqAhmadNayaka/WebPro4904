<?php
session_start(); // Mulai session untuk cek login
include "koneksi.php"; // Panggil koneksi database

// Jika belum login, arahkan ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// CREATE (Simpan data + upload file)
if (isset($_POST['simpan'])) {
    // Ambil input dari form dengan aman (pakai isset)
    $nama   = isset($_POST['nama']) ? $_POST['nama'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $nohp   = isset($_POST['nohp']) ? $_POST['nohp'] : '';

    // Tentukan folder upload
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Buat folder jika belum ada
    }

    // Ambil nama file dari input
    $fileName = isset($_FILES['file']['name']) ? basename($_FILES['file']['name']) : '';
    $targetFile = $targetDir . $fileName;

    // Proses upload file
    if (!empty($fileName) && move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        // Simpan data ke tabel warga
        mysqli_query($conn, "INSERT INTO warga (nama, alamat, nohp, file) VALUES ('$nama','$alamat','$nohp','$fileName')");
    } else {
        echo "<script>alert('Upload file gagal atau tidak ada file!');</script>";
    }
}

// DELETE (hapus data)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus']; // Ambil id dari URL
    mysqli_query($conn, "DELETE FROM warga WHERE id='$id'");
}

// UPDATE (edit data)
if (isset($_POST['update'])) {
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $nama   = isset($_POST['nama']) ? $_POST['nama'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $nohp   = isset($_POST['nohp']) ? $_POST['nohp'] : '';

    // Update data di tabel warga
    mysqli_query($conn, "UPDATE warga SET nama='$nama', alamat='$alamat', nohp='$nohp' WHERE id='$id'");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Data Warga</title>
</head>
<body>
<div class="container">
  <h2>Kelola Data Warga</h2>

  <!-- Form input data warga + upload file -->
  <form method="post" enctype="multipart/form-data">
    <!-- Input nama -->
    <input type="text" name="nama" placeholder="Nama" required>
    <!-- Input alamat -->
    <input type="text" name="alamat" placeholder="Alamat" required>
    <!-- Input no HP -->
    <input type="text" name="nohp" placeholder="No HP" required>
    <!-- Input file -->
    <input type="file" name="file" required>
    <!-- Tombol simpan -->
    <button type="submit" name="simpan">Simpan</button>
  </form>

  <!-- Tabel data warga -->
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <th>Nama</th>
      <th>Alamat</th>
      <th>No HP</th>
      <th>File</th>
      <th>Aksi</th>
    </tr>
    <?php
    // Ambil semua data dari tabel warga
    $q = mysqli_query($conn, "SELECT * FROM warga");
    while ($row = mysqli_fetch_assoc($q)) {
        // Tampilkan data ke tabel
        echo "<tr>
                <td>{$row['nama']}</td>
                <td>{$row['alamat']}</td>
                <td>{$row['nohp']}</td>
                <td><a href='uploads/{$row['file']}' download>Download</a></td>
                <td><a href='warga.php?hapus={$row['id']}'>Hapus</a></td>
              </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>