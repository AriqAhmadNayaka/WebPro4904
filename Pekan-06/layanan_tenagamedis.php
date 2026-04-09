<?php
session_start(); // Memulai sesi untuk menyimpan informasi login
include 'koneksi.php';// Menghubungkan ke file koneksi untuk akses database

// Cek login
if (!isset($_SESSION['login'])) { // Cek jika pengguna belum login, jika belum maka redirect ke halaman login
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit; // Hentikan eksekusi script setelah redirect
}

class Layanan { // Kelas untuk mengelola layanan informasi kesehatan
    private $db; // Variabel untuk menyimpan koneksi database
    public function __construct($connection) { // Konstruktor untuk menerima koneksi database saat objek dibuat
        $this->db = $connection; // Menyimpan koneksi database ke dalam variabel kelas
    }

    public function tampilSemua() { // Fungsi untuk mengambil semua data informasi kesehatan dari database
        return mysqli_query($this->db, "SELECT * FROM informasi_medis"); // Mengembalikan hasil query untuk diolah di bagian tampilan
    }

    public function hapus($id) { // Fungsi untuk menghapus data informasi kesehatan berdasarkan ID
        $id = mysqli_real_escape_string($this->db, $id); // Melindungi dari SQL Injection dengan membersihkan input ID
        return mysqli_query($this->db, "DELETE FROM informasi_medis WHERE id=$id"); // Eksekusi query untuk menghapus data berdasarkan ID
    }

    public function simpan($data, $file) { // Fungsi untuk menyimpan data informasi kesehatan, baik untuk menambah data baru maupun mengupdate data yang sudah ada, menerima data dari form dan file yang diupload
        $judul = mysqli_real_escape_string($this->db, $data['judul']);//    Melindungi dari SQL Injection dengan membersihkan input judul
        $kategori = mysqli_real_escape_string($this->db, $data['kategori']);// Melindungi dari SQL Injection dengan membersihkan input kategori
        $deskripsi = mysqli_real_escape_string($this->db, $data['deskripsi']);// Melindungi dari SQL Injection dengan membersihkan input deskripsi
        $id = isset($data['editIndex']) ? mysqli_real_escape_string($this->db, $data['editIndex']) : '';// Melindungi dari SQL Injection dengan membersihkan input ID untuk edit, jika ada

        $namaFile = $file['name']; // Nama file yang diupload
        $tmpFile = $file['tmp_name']; // Lokasi sementara file yang diupload
        $path = "upload/" . $namaFile; // Path tujuan untuk menyimpan file yang diupload, pastikan folder 'upload' sudah ada dan memiliki izin yang benar

        // Upload file jika ada
        if (!empty($namaFile)) { // Cek jika ada file yang diupload
            move_uploaded_file($tmpFile, $path); // Pindahkan file dari lokasi sementara ke folder tujuan, pastikan folder 'upload' sudah ada dan memiliki izin yang benar
        }

        if (!empty($id)) { // Cek jika ID ada, berarti ini adalah update data, jika tidak maka ini adalah tambah data baru
            // Logika UPDATE
            if (!empty($namaFile)) { // Jika ada file baru yang diupload saat edit, update juga field file di database
                return mysqli_query($this->db, "UPDATE informasi_medis SET judul='$judul', kategori='$kategori', deskripsi='$deskripsi', file='$namaFile' WHERE id=$id"); // Eksekusi query untuk mengupdate data berdasarkan ID, termasuk update file jika ada
            } else { // Jika tidak ada file baru yang diupload saat edit, jangan update field file di database
                return mysqli_query($this->db, "UPDATE informasi_medis SET judul='$judul', kategori='$kategori', deskripsi='$deskripsi' WHERE id=$id");
            }
        } else {
            // Logika INSERT
            return mysqli_query($this->db, "INSERT INTO informasi_medis (judul, kategori, deskripsi, file) VALUES ('$judul', '$kategori', '$deskripsi', '$namaFile')"); // Eksekusi query untuk menambah data baru ke database, termasuk nama file yang diupload
        }
    }

    public function ambilSatu($id) { // Fungsi untuk mengambil satu data informasi kesehatan berdasarkan ID, digunakan untuk menampilkan data yang akan diedit di form
        $id = mysqli_real_escape_string($this->db, $id); // Melindungi dari SQL Injection dengan membersihkan input ID
        $res = mysqli_query($this->db, "SELECT * FROM informasi_medis WHERE id=$id"); // Eksekusi query untuk mengambil data berdasarkan ID
        return mysqli_fetch_assoc($res); // Mengembalikan data sebagai array asosiatif untuk ditampilkan di form edit, jika data ditemukan maka akan berisi data, jika tidak ditemukan maka akan bernilai null
    }
}

$layananObj = new Layanan($conn); // Membuat objek Layanan dengan koneksi database yang sudah dibuat di koneksi.php

// Inisialisasi variabel agar tidak error saat form pertama dimuat
$editIndex = null;
$judul = ''; // Variabel untuk menyimpan data yang akan diedit, jika ada
$kategori = 'Perawatan & Medis'; // Variabel untuk menyimpan data yang akan diedit, jika ada, dengan default kategori pertama
$deskripsi = ''; // Variabel untuk menyimpan data yang akan diedit, jika ada

// Eksekusi aksi Hapus
if (isset($_GET['hapus'])) { // Panggil fungsi hapus dengan ID yang dikirimkan melalui query string
    $layananObj->hapus($_GET['hapus']); // Panggil fungsi hapus dengan ID yang dikirimkan melalui query string
    header("Location: layanan_tenagamedis.php"); // Redirect ke halaman yang sama untuk melihat perubahan
    exit; // Hentikan eksekusi setelah redirect
}

// Eksekusi aksi Edit (Ambil data)
if (isset($_GET['edit'])) { // Ambil data yang akan diedit berdasarkan ID yang dikirimkan melalui query string
    $dataEdit = $layananObj->ambilSatu($_GET['edit']); // Ambil data yang akan diedit berdasarkan ID yang dikirimkan melalui query string
    if ($dataEdit) { // Jika data ditemukan, isi variabel untuk menampilkan di form edit
        $editIndex = $dataEdit['id']; // Simpan ID yang sedang diedit untuk digunakan di form
        $judul = $dataEdit['judul']; // Ambil data judul untuk ditampilkan di input saat edit
        $kategori = $dataEdit['kategori']; // Ambil data kategori untuk menampilkan pilihan yang sesuai di dropdown saat edit
        $deskripsi = $dataEdit['deskripsi']; // Ambil data deskripsi untuk ditampilkan di textarea saat edit
    }
}

// Eksekusi aksi Simpan/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Panggil fungsi simpan dengan data dari form dan file yang diupload
    $layananObj->simpan($_POST, $_FILES['file']); // Panggil fungsi simpan dengan data dari form dan file yang diupload
    header("Location: layanan_tenagamedis.php"); // Redirect ke halaman yang sama untuk melihat perubahan
    exit; // Hentikan eksekusi setelah redirect
}

$dataInformasi = $layananObj->tampilSemua(); // Ambil semua data informasi untuk ditampilkan di tabel
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LifeTrack – Tenaga Medis</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #F5F8F8;
    color: #1f2f2b;
    min-height: 100vh;
}

.layout {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 260px;
    background: #ffffff;
    padding: 24px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.05);
}

.sidebar h1 {
    color: #0f766e;
    margin-bottom: 24px;
    text-align: center;
}

.menu {
    list-style: none;
}

.menu a {
    display: block;
    text-decoration: none;
    color: #0f766e;
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 8px;
    font-weight: 500;
}

.menu a:hover,
.menu a.active {
    background: #E3ECE9;
}

.profile {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #e5e8ea;
    background: #fff;
    margin-bottom: 24px;
}

.profile img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
}

.main {
    flex: 1;
    padding: 32px;
}

h2 {
    margin-bottom: 16px;
    color: #0f766e;
}

.form-box {
    background: #ffffff;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 32px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #dfe6e4;
}

textarea {
    resize: vertical;
    min-height: 80px;
}

button {
    background: #0f766e;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background: #0d5f57;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}

th {
    background: #E3ECE9;
    text-align: left;
}

.action-btn {
    padding: 6px 10px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    text-decoration: none;
    display: inline-block;
}

.edit {
    background: #0f766e;
    color: white;
}

.delete {
    background: #dc2626;
    color: white;
    margin-left: 6px;
}

footer {
    background:#0f766e;
    color:white;
    text-align:center;
    padding: 20px;
}

@media (max-width: 768px) {
    .layout {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
    }
}
</style>
</head>

<body>

<div class="layout">

<aside class="sidebar">
    <h1>LifeTrack</h1>

    <div class="profile">
        <img src="calm pfp.jpg" alt="Foto Profil">
        <span>Andi</span>
    </div>

    <ul class="menu">
        <li><a href="dashboard.php">Beranda</a></li>
        <li><a href="datapasien.php">Data Pasien</a></li>
        <li><a href="jadwalkontrol.php">Jadwal Kontrol</a></li>
        <li><a href="rekomendasi_dokter.php">Rekomendasi Kegiatan</a></li>
        <li><a href="nutrisidokter.php">Nutrisi dan Gizi</a></li>
        <li><a class="active" href="#">Layanan Informasi</a></li>
        <li><a href="kontak.php">Kontak</a></li>
    </ul>
</aside>

<main class="main">

<h2>Input Informasi Kesehatan</h2>

<div class="form-box">
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="editIndex" value="<?= $editIndex !== null ? htmlspecialchars($editIndex) : '' ?>">

        <div class="form-group">
            <label>Judul Informasi</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori">
                <option value="Perawatan & Medis" <?= $kategori == 'Perawatan & Medis' ? 'selected' : '' ?>>Perawatan & Medis</option>
                <option value="Obat & Terapi" <?= $kategori == 'Obat & Terapi' ? 'selected' : '' ?>>Obat & Terapi</option>
                <option value="Kesehatan Mental" <?= $kategori == 'Kesehatan Mental' ? 'selected' : '' ?>>Kesehatan Mental</option>
                <option value="Gaya Hidup Sehat" <?= $kategori == 'Gaya Hidup Sehat' ? 'selected' : '' ?>>Gaya Hidup Sehat</option>
                <option value="Nutrisi & Gizi" <?= $kategori == 'Nutrisi & Gizi' ? 'selected' : '' ?>>Nutrisi & Gizi</option>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" required><?= htmlspecialchars($deskripsi) ?></textarea>
        </div>

        <div class="form-group">
            <label>Upload File (Gambar)</label>
            <input type="file" name="file">
        </div>

        <button type="submit"><?= $editIndex !== null ? 'Update Informasi' : 'Simpan Informasi' ?></button>
        <?php if($editIndex !== null): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" style="margin-left: 10px; color: #dc2626; text-decoration: none;">Batal Edit</a>
        <?php endif; ?>
    </form>
</div>

<h2>Data Informasi Kesehatan</h2>

<div class="form-box">
<table>
<thead>
<tr>
<th>Judul</th>
<th>Kategori</th>
<th>Deskripsi</th>
<th>File</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
// Menggunakan variabel $dataInformasi yang sudah didefinisikan di atas melalui class Layanan
if (mysqli_num_rows($dataInformasi) == 0):
?>
<tr>
    <td colspan="5" style="text-align: center;">Belum ada data informasi.</td>
</tr>
<?php else: ?>
    <?php while ($item = mysqli_fetch_assoc($dataInformasi)): ?>
        <tr>
            <td><?= htmlspecialchars($item['judul']) ?></td>
            <td><?= htmlspecialchars($item['kategori']) ?></td>
            <td><?= nl2br(htmlspecialchars($item['deskripsi'])) ?></td>
            <td>
                <?php if(!empty($item['file'])): ?>
                    <img src="upload/<?= $item['file'] ?>" width="120">
                <?php else: ?>
                    Tidak ada file
                <?php endif; ?>
            </td>

<td>
    <a href="?edit=<?= $item['id'] ?>" class="action-btn edit">Edit</a>
    <a href="?hapus=<?= $item['id'] ?>" class="action-btn delete" onclick="return confirm('Hapus data ini?');">Hapus</a>
</td>
        </tr>
    <?php endwhile; ?>
<?php endif; ?>
</tbody>
</table>
</div>

</main>
</div>

<footer>
LifeTrack © 2026 — Membantu Anda hidup lebih sehat.
</footer>

</body>
</html>