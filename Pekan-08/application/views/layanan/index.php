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
        <img src="<?php echo $profile_pic; ?>" alt="User">
        <!-- Menampilkan foto profil menggunakan variabel $profile_pic -->
        <span><b><?php echo $nama_dokter; ?></b></span>
    </div>

    <ul class="menu">
        <li><a href="<?php echo base_url('dashboard'); ?>">Beranda</a></li>
        <li><a href="<?php echo base_url('datapasien'); ?>">Data Pasien</a></li>
        <li><a href="<?php echo base_url('layanan/jadwalkontrol'); ?>">Jadwal Kontrol</a></li>
        <li><a href="<?php echo base_url('layanan/rekomendasi_dokter'); ?>">Rekomendasi Kegiatan</a></li>
        <li><a href="<?php echo base_url('layanan/nutrisidokter'); ?>">Nutrisi dan Gizi</a></li>
        <li><a class="active" href="<?php echo base_url('layanan'); ?>">Layanan Informasi</a></li>
        <li><a href="<?php echo base_url('layanan/kontak'); ?>">Kontak</a></li>
        <li>
            <a href="<?php echo base_url('auth/logout'); ?>"
                style="color:#dc2626; margin-top:16px; border-top:1px solid #e5e7eb; padding-top:16px; display:block;"
                onclick="return confirm('Yakin ingin keluar?');">
                Logout
            </a>
        </li>
    </ul>
</aside>

<main class="main">

<h2>Input Informasi Kesehatan</h2>

// Tampilkan pesan sukses atau error jika ada
<?php if ($this->session->flashdata('success')): ?>
    <div style="padding:10px;background:#d4edda;border-left:4px solid #28a745;color:#155724;border-radius:6px;margin-bottom:16px;">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

// Tampilkan pesan error jika ada
<?php if ($this->session->flashdata('error')): ?>
    <div style="padding:10px;background:#f8d7da;border-left:4px solid #dc3545;color:#721c24;border-radius:6px;margin-bottom:16px;">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

// Form input untuk tambah/edit informasi kesehatan
<div class="form-box">
    <?php if ($edit_data === null): ?>
    <form action="<?php echo base_url('layanan/store'); ?>" method="POST" enctype="multipart/form-data">
    <?php else: ?>
    <form action="<?php echo base_url('layanan/update/' . $edit_data->id); ?>" method="POST" enctype="multipart/form-data">
    <?php endif; ?>

        <div class="form-group">
            <label>Judul Informasi</label>
            <input type="text" name="judul" value="<?= $edit_data ? htmlspecialchars($edit_data->judul) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori">
                <option value="Perawatan & Medis" <?= ($edit_data && $edit_data->kategori == 'Perawatan & Medis') ? 'selected' : '' ?>>Perawatan & Medis</option>
                <option value="Obat & Terapi" <?= ($edit_data && $edit_data->kategori == 'Obat & Terapi') ? 'selected' : '' ?>>Obat & Terapi</option>
                <option value="Kesehatan Mental" <?= ($edit_data && $edit_data->kategori == 'Kesehatan Mental') ? 'selected' : '' ?>>Kesehatan Mental</option>
                <option value="Gaya Hidup Sehat" <?= ($edit_data && $edit_data->kategori == 'Gaya Hidup Sehat') ? 'selected' : '' ?>>Gaya Hidup Sehat</option>
                <option value="Nutrisi & Gizi" <?= ($edit_data && $edit_data->kategori == 'Nutrisi & Gizi') ? 'selected' : '' ?>>Nutrisi & Gizi</option>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" required><?= $edit_data ? htmlspecialchars($edit_data->deskripsi) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>Upload File (Gambar)</label>
            <input type="file" name="file">
        </div>

        <button type="submit"><?= $edit_data ? 'Update Informasi' : 'Simpan Informasi' ?></button>
        <?php if ($edit_data): ?>
            <a href="<?php echo base_url('layanan'); ?>" style="margin-left: 10px; color: #dc2626; text-decoration: none;">Batal Edit</a>
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
// Tampilkan data layanan, jika kosong tampilkan pesan
<?php if (empty($list_layanan)): ?>
    <tr><td colspan="5" style="text-align:center;">Belum ada data informasi.</td></tr>
<?php else: ?>
    <?php foreach ($list_layanan as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item->judul) ?></td>
            <td><?= htmlspecialchars($item->kategori) ?></td>
            <td><?= nl2br(htmlspecialchars($item->deskripsi)) ?></td>
            <td>
                <?php if (!empty($item->file)): ?>
                    <img src="<?= base_url('uploads/' . $item->file) ?>" width="120">
                <?php else: ?>
                    Tidak ada file
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= base_url('layanan?edit=' . $item->id) ?>" class="action-btn edit">Edit</a>
                <a href="<?= base_url('layanan/hapus/' . $item->id) ?>" class="action-btn delete"
                    onclick="return confirm('Hapus data ini?');">Hapus</a>
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