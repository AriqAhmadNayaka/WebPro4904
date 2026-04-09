<?php
session_start();
require 'config.php';

// Proteksi halaman
if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Proses CREATE (Tambah Data sekaligus Upload File)
if (isset($_POST['tambah'])) {
    $nama_makanan = $_POST['nama_makanan'];
    $jumlah = $_POST['jumlah'];
    $tanggal_kedaluwarsa = $_POST['tanggal_kedaluwarsa'];
    
    // Logika Upload File
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    
    // Ganti nama file dengan menambahkan angka acak agar tidak ada file bernama sama
    $fotobaru = date('d-m-Y_H-i-s').'_'.$gambar;
    $path = "uploads/".$fotobaru;

    // Memindahkan file ke folder uploads
    if(move_uploaded_file($tmp, $path)) { 
        // Jika file berhasil diupload, masukkan ke database
        $stmt = $conn->prepare("INSERT INTO donasi_makanan (user_id, nama_makanan, jumlah, tanggal_kedaluwarsa, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $nama_makanan, $jumlah, $tanggal_kedaluwarsa, $fotobaru);
        $stmt->execute();
    } else {
        echo "<script>alert('Gagal mengupload gambar!');</script>";
    }
    
    header("Location: beranda.php");
}

// Proses DELETE (Hapus Data beserta filenya)
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // Cari nama file dari DB untuk dihapus dari folder
    $cari_gambar = $conn->query("SELECT gambar FROM donasi_makanan WHERE id='$id_hapus'");
    $data_gambar = $cari_gambar->fetch_assoc();
    if(is_file("uploads/".$data_gambar['gambar'])) {
        unlink("uploads/".$data_gambar['gambar']); // Menghapus file fisik
    }

    $conn->query("DELETE FROM donasi_makanan WHERE id='$id_hapus' AND user_id='$user_id'");
    header("Location: beranda.php");
}
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
        .header a { color: white; text-decoration: none; font-weight: bold; background: #d32f2f; padding: 8px 15px; border-radius: 5px; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        /* Grid disesuaikan untuk menampung input file */
        form { margin-bottom: 30px; display: grid; gap: 15px; grid-template-columns: 1fr 1fr 1fr 1fr auto; align-items: end; }
        input { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; box-sizing: border-box;}
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;}
        button:hover { background: #388E3C; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; }
        th { background-color: #4CAF50; color: white; }
        .btn-edit { color: #1976D2; text-decoration: none; margin-right: 10px; }
        .btn-hapus { color: #d32f2f; text-decoration: none; }
        .img-preview { width: 80px; height: 80px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h2><i class="fas fa-leaf"></i> Dashboard EcoTaste</h2>
        <div>
            <span>Halo, <?php echo htmlspecialchars($username); ?>! </span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h3>Formulir Donasi Makanan Berlebih</h3>
        <form action="beranda.php" method="POST" enctype="multipart/form-data">
            <div>
                <label>Nama Makanan</label>
                <input type="text" name="nama_makanan" required placeholder="Cth: Roti">
            </div>
            <div>
                <label>Jumlah/Porsi</label>
                <input type="text" name="jumlah" required placeholder="Cth: 5">
            </div>
            <div>
                <label>Kedaluwarsa</label>
                <input type="date" name="tanggal_kedaluwarsa" required>
            </div>
            <div>
                <label>Foto Makanan</label>
                <input type="file" name="gambar" accept="image/*" required>
            </div>
            <button type="submit" name="tambah">Simpan</button>
        </form>

        <h3>Daftar Makanan Tersedia</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Makanan</th>
                <th>Jumlah</th>
                <th>Kadaluwarsa</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            $result = $conn->query("SELECT * FROM donasi_makanan WHERE user_id=$user_id ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                // Mengecek apakah gambar ada, jika tidak pakai gambar default kosong
                $foto = ($row['gambar'] != '') ? "uploads/".$row['gambar'] : "default.png";
                
                echo "<tr>
                        <td>{$no}</td>
                        <td><img src='{$foto}' class='img-preview'></td>
                        <td>" . htmlspecialchars($row['nama_makanan']) . "</td>
                        <td>" . htmlspecialchars($row['jumlah']) . "</td>
                        <td>{$row['tanggal_kedaluwarsa']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='edit.php?id={$row['id']}' class='btn-edit'>Edit</a> | 
                            <a href='beranda.php?hapus={$row['id']}' class='btn-hapus' onclick='return confirm(\"Yakin hapus data?\")'>Hapus</a>
                        </td>
                    </tr>";
                $no++;
            }
            if ($result->num_rows == 0) {
                echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data donasi.</td></tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>