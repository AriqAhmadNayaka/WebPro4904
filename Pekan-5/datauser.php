<?php
// Memulai session untuk menyimpan data pengguna di server (digunakan untuk login)
session_start();

// --- PROTEKSI HALAMAN ---
// Mengecek apakah ada session 'login'. Jika tidak ada, pengguna dipaksa ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit; // Menghentikan eksekusi script agar kode di bawah tidak dijalankan
}

// Koneksi ke database (host, username, password, nama_database)
$conn = mysqli_connect("localhost", "root", "", "cybervault");

// Cek jika koneksi gagal, hentikan program dan tampilkan pesan error
if (!$conn) { 
    die("Koneksi gagal: " . mysqli_connect_error()); 
}

// --- PROSES SIMPAN DATA (TEKS + FILE) ---
// Mengecek apakah tombol 'tambah' pada form sudah diklik
if (isset($_POST['tambah'])) {
    
    // Mengambil data input dan melindunginya dari SQL Injection menggunakan mysqli_real_escape_string
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Mengamankan password dengan teknik Hashing (tidak disimpan dalam bentuk teks biasa)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Informasi File Foto
    $namaFile   = $_FILES['foto']['name'];      // Nama asli file
    $tmpName    = $_FILES['foto']['tmp_name'];  // Alamat penyimpanan sementara di server
    $errorFile  = $_FILES['foto']['error'];     // Kode error (0 jika sukses, 4 jika tidak ada file)

    // Cek apakah user mengupload foto atau tidak
    if ($errorFile === 4) {
        // Jika tidak upload, gunakan foto default
        $fotoFinal = "default.png";
    } else {
        // Validasi ekstensi file
        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $ekstensiFile  = explode('.', $namaFile);
        $ekstensiFile  = strtolower(end($ekstensiFile)); // Ambil ekstensi akhir dan ubah ke huruf kecil

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            echo "<script>alert('Format file tidak didukung!');</script>";
        } else {
            // Generate nama file baru yang unik (mencegah nama file kembar)
            $fotoFinal = uniqid() . '.' . $ekstensiFile;
            // Pindahkan file dari folder sementara ke folder tujuan (img/)
            move_uploaded_file($tmpName, 'img/' . $fotoFinal);
        }
    }

    // Menyusun query SQL untuk memasukkan data ke tabel 'datauser'
    $sql = "INSERT INTO datauser (nama, email, password, foto) VALUES ('$nama', '$email', '$password', '$fotoFinal')";
    
    // Eksekusi query dan berikan feedback jika berhasil
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data Berhasil Ditambahkan!'); window.location.href='datauser.php';</script>";
    }
}

// --- PROSES TAMPIL DATA ---
// Mengambil semua data dari tabel datauser, diurutkan dari yang terbaru (ID terbesar)
$query  = "SELECT * FROM datauser ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User • CyberVault</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0a0a0f; color: #fff; padding: 40px; }
        .header-area { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        h2 { color: #00d4ff; margin: 0; }
        .btn-logout { background: #ff4d4d; color: #fff; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px; }
        .btn-logout:hover { background: #cc0000; }
        .form-tambah { 
            background: rgba(255,255,255,0.05); padding: 20px; border-radius: 10px; 
            border: 1px solid rgba(0, 212, 255, 0.3); display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; margin-bottom: 30px;
        }
        .input-group { display: flex; flex-direction: column; flex: 1; min-width: 150px; }
        .input-group label { font-size: 12px; color: #00d4ff; margin-bottom: 5px; }
        input { padding: 10px; background: #1a1a2e; border: 1px solid #333; color: #fff; border-radius: 5px; }
        .btn-simpan { background: #00d4ff; color: #000; border: none; padding: 10px 25px; border-radius: 5px; font-weight: bold; cursor: pointer; height: 42px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #222; text-align: left; }
        th { background: rgba(0, 212, 255, 0.1); color: #00d4ff; }
        .img-preview { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 1px solid #00d4ff; }
        .btn-aksi { padding: 5px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold; margin-right: 5px; }
        .btn-edit { background: #00ffaa; color: #000; }
        .btn-hapus { background: #ff4d4d; color: #fff; }
    </style>
</head>
<body>

    <div class="header-area">
        <h2>Manajemen Pengguna CyberVault</h2>
        <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
    </div>

    <form action="" method="POST" enctype="multipart/form-data" class="form-tambah">
        <div class="input-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" required placeholder="Nama">
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="email@vault.com">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="******">
        </div>
        <div class="input-group">
            <label>Foto Profil</label>
            <input type="file" name="foto">
        </div>
        <button type="submit" name="tambah" class="btn-simpan">Simpan Data</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><img src="img/<?= $row['foto']; ?>" class="img-preview"></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-aksi btn-edit">Edit</a>
                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>