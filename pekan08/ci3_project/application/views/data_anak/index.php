<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anak</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/data-anak.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <h2 class="logo">InkluSkill</h2>
    <a href="<?= site_url('dashboard-ortu') ?>"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="<?= site_url('data-anak') ?>" class="active"><i class="ri-user-3-line"></i>Data Anak</a>
</div>

<div class="content">
    <div class="page-header">
        <div>
            <h1>Data Anak</h1>
            <p>Kelola data anak, foto, dan informasi pendukung dalam satu halaman.</p>
        </div>
        <div class="user-badge">
            <i class="ri-user-smile-line"></i>
            <span><?= html_escape($userName) ?></span>
        </div>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert error"><?= html_escape($error) ?></div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="alert success"><?= html_escape($success) ?></div>
    <?php endif; ?>

    <div class="layout-grid">
        <div class="profile-box">
            <div class="photo-box">
                <?php if ($formData['foto'] !== ''): ?>
                    <img src="<?= base_url($formData['foto']) ?>" alt="Foto Anak">
                <?php else: ?>
                    <div class="photo-placeholder">
                        <i class="ri-user-smile-line"></i>
                    </div>
                <?php endif; ?>
                <p>Foto akan ikut tersimpan saat tombol <b>Simpan</b> ditekan.</p>
            </div>

            <form method="post" enctype="multipart/form-data" class="big-card">
                <div class="form-heading">
                    <div>
                        <h3><?= $formData['id'] > 0 ? 'Edit Data Anak' : 'Tambah Data Anak' ?></h3>
                        <?php if ($formData['id'] > 0): ?>
                            <p class="edit-hint">Perubahan sedang diterapkan ke data yang dipilih di daftar kanan.</p>
                        <?php else: ?>
                            <p class="edit-hint">Isi form berikut untuk menambahkan data anak baru.</p>
                        <?php endif; ?>
                    </div>
                    <?php if ($formData['id'] > 0): ?>
                        <span class="edit-badge"><i class="ri-edit-2-line"></i> Mode Edit</span>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="id" value="<?= (int) $formData['id'] ?>">
                <input type="hidden" name="foto_lama" value="<?= html_escape($formData['foto']) ?>">

                <div class="info-grid">
                    <div class="input-group">
                        <label>Nama</label>
                        <input type="text" name="nama" value="<?= html_escape($formData['nama']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">Pilih</option>
                            <option value="Laki-laki" <?= $formData['gender'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $formData['gender'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal" value="<?= html_escape($formData['tanggal_lahir']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Kelas</label>
                        <input type="text" name="kelas" value="<?= html_escape($formData['kelas']) ?>" required>
                    </div>

                    <div class="input-group full">
                        <label>Alamat</label>
                        <input type="text" name="alamat" value="<?= html_escape($formData['alamat']) ?>">
                    </div>

                    <div class="input-group full">
                        <label>Catatan</label>
                        <textarea name="catatan"><?= html_escape($formData['catatan']) ?></textarea>
                    </div>

                    <div class="input-group full">
                        <label>Upload Foto</label>
                        <input type="file" name="foto" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <small>Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 2 MB.</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="simpan" class="save-btn">
                        <i class="ri-save-line"></i> <?= $formData['id'] > 0 ? 'Update Data' : 'Simpan' ?>
                    </button>
                    <?php if ($formData['id'] > 0): ?>
                        <a href="<?= site_url('data-anak') ?>" class="secondary-btn">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="list-card">
            <div class="list-header">
                <h3>Daftar Data Anak</h3>
                <span><?= count($children) ?> data</span>
            </div>

            <?php if (empty($children)): ?>
                <div class="empty-state">
                    <i class="ri-folder-open-line"></i>
                    <p>Belum ada data anak yang tersimpan.</p>
                </div>
            <?php else: ?>
                <div class="child-list">
                    <?php foreach ($children as $child): ?>
                        <div class="child-item<?= $formData['id'] > 0 && (int) $formData['id'] === (int) $child['id'] ? ' is-editing' : '' ?>">
                            <div class="child-preview">
                                <?php if (!empty($child['foto'])): ?>
                                    <img src="<?= base_url($child['foto']) ?>" alt="Foto <?= html_escape(isset($child['nama']) ? $child['nama'] : 'Anak') ?>">
                                <?php else: ?>
                                    <div class="child-avatar"><i class="ri-user-3-line"></i></div>
                                <?php endif; ?>

                                <div>
                                    <h4><?= html_escape(isset($child['nama']) ? $child['nama'] : '-') ?></h4>
                                    <p><?= html_escape((isset($child['kelas']) ? $child['kelas'] : '-') . ' | ' . (isset($child['gender']) ? $child['gender'] : '-')) ?></p>
                                    <small><?= html_escape(isset($child['tanggal_lahir']) ? $child['tanggal_lahir'] : '-') ?></small>
                                </div>
                            </div>

                            <div class="child-actions">
                                <a href="<?= site_url('data-anak?edit=' . (int) $child['id']) ?>" class="action-btn edit-btn"><?= $formData['id'] > 0 && (int) $formData['id'] === (int) $child['id'] ? 'Sedang Diedit' : 'Edit' ?></a>
                                <form method="post" onsubmit="return confirm('Hapus data anak ini?');">
                                    <input type="hidden" name="id" value="<?= (int) $child['id'] ?>">
                                    <button type="submit" name="hapus" class="action-btn delete-btn">Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
