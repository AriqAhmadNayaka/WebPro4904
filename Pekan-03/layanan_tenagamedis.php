<?php
// Mulai sesi PHP untuk menyimpan data sementara sebagai pengganti localStorage
session_start();

if (!isset($_SESSION['informasiMedis'])) { // Cek jika data informasi medis belum ada di sesi
    $_SESSION['informasiMedis'] = []; // Inisialisasi array kosong untuk menyimpan data informasi medis
}

$editIndex = null; // Variabel untuk menyimpan indeks data yang sedang diedit, null berarti tidak ada yang diedit
$judul = ''; // Variabel untuk menyimpan judul informasi, default kosong
$kategori = 'Perawatan & Medis'; // Variabel untuk menyimpan kategori informasi, default "Perawatan & Medis"
$deskripsi = ''; // Variabel untuk menyimpan deskripsi informasi, default kosong


if (isset($_GET['hapus'])) { // Cek jika ada parameter "hapus" di URL
    $id = $_GET['hapus']; // Ambil nilai indeks data yang akan dihapus dari parameter "hapus"
    if (isset($_SESSION['informasiMedis'][$id])) { // Cek jika data dengan indeks tersebut ada di sesi
        unset($_SESSION['informasiMedis'][$id]); // Hapus data dari sesi berdasarkan indeks
        
        $_SESSION['informasiMedis'] = array_values($_SESSION['informasiMedis']); // Reset indeks array setelah penghapusan
    }
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect ke halaman yang sama untuk memperbarui tampilan setelah penghapusan
    exit;
}

if (isset($_GET['edit'])) { // Cek jika ada parameter "edit" di URL
    $editIndex = $_GET['edit']; // Ambil nilai indeks data yang akan diedit dari parameter "edit"
    if (isset($_SESSION['informasiMedis'][$editIndex])) { // Cek jika data dengan indeks tersebut ada di sesi
        $dataEdit = $_SESSION['informasiMedis'][$editIndex]; // Ambil data yang akan diedit dari sesi berdasarkan indeks
        $judul = $dataEdit['judul']; // Set variabel judul dengan nilai dari data yang akan diedit
        $kategori = $dataEdit['kategori']; // Set variabel kategori dengan nilai dari data yang akan diedit
        $deskripsi = $dataEdit['deskripsi']; // Set variabel deskripsi dengan nilai dari data yang akan diedit
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Cek jika form disubmit dengan metode POST
    $judulPost = trim($_POST['judul']); // Ambil dan bersihkan input judul dari form
    $kategoriPost = $_POST['kategori']; // Ambil input kategori dari form
    $deskripsiPost = trim($_POST['deskripsi']); // Ambil dan bersihkan input deskripsi dari form
    $indexPost = $_POST['editIndex']; // Ambil nilai indeks data yang sedang diedit dari form, jika tidak ada berarti tambah baru

    if (!empty($judulPost) && !empty($deskripsiPost)) { // Validasi bahwa judul dan deskripsi tidak kosong
        if ($indexPost !== '' && isset($_SESSION['informasiMedis'][$indexPost])) { // Cek jika indeks edit tidak kosong dan data dengan indeks tersebut ada di sesi, berarti update data
            
            $_SESSION['informasiMedis'][$indexPost] = [ // Update data di sesi berdasarkan indeks edit
                'judul' => $judulPost, // Set judul dengan nilai dari form
                'kategori' => $kategoriPost, // Set kategori dengan nilai dari form
                'deskripsi' => $deskripsiPost // Set deskripsi dengan nilai dari form
            ];
        } else { // Jika indeks edit kosong atau data dengan indeks tersebut tidak ada di sesi, berarti tambah data baru
            
            $_SESSION['informasiMedis'][] = [ // Tambah data baru ke sesi, indeks otomatis bertambah
                'judul' => $judulPost, // Set judul dengan nilai dari form
                'kategori' => $kategoriPost, // Set kategori dengan nilai dari form
                'deskripsi' => $deskripsiPost // Set deskripsi dengan nilai dari form
            ];
        }
    }
    
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect ke halaman yang sama untuk memperbarui tampilan setelah tambah atau update data
    exit;
}
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
        <li><a href="datapasien.html">Data Pasien</a></li>
        <li><a href="jadwalkontrol.html">Jadwal Kontrol</a></li>
        <li><a href="rekomendasidokter.html">Rekomendasi Kegiatan</a></li>
        <li><a href="nutrisidokter.html">Nutrisi dan Gizi</a></li>
        <li><a class="active" href="#">Layanan Informasi</a></li>
        <li><a href="kontak.html">Kontak</a></li>
    </ul>
</aside>

<main class="main">

<h2>Input Informasi Kesehatan</h2>

<div class="form-box">
    <form action="" method="POST">
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
<th>Aksi</th>
</tr>
</thead>
<tbody>
    <?php if (empty($_SESSION['informasiMedis'])): ?>
        <tr>
            <td colspan="4" style="text-align: center;">Belum ada data informasi.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($_SESSION['informasiMedis'] as $i => $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['judul']) ?></td>
                <td><?= htmlspecialchars($item['kategori']) ?></td>
                <td><?= nl2br(htmlspecialchars($item['deskripsi'])) ?></td>
                <td>
                    <a href="?edit=<?= $i ?>" class="action-btn edit">Edit</a>
                    <a href="?hapus=<?= $i ?>" class="action-btn delete" onclick="return confirm('Hapus data ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
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