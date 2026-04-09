<?php
session_start();
require_once '../app/bootstrap.php';

$auth->requireLogin('../auth.php');

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$user = $auth->user();
$userId = $auth->id();
$childRepository->adoptLegacyRowsForUser($userId);

$error = '';
$success = '';
$editId = (int) ($_GET['edit'] ?? 0);

$formData = [
    'id' => 0,
    'nama' => '',
    'gender' => '',
    'tanggal_lahir' => '',
    'kelas' => '',
    'alamat' => '',
    'catatan' => '',
    'foto' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $child = $childRepository->findByIdAndUser($id, $userId);

    if ($child) {
        $childRepository->delete($id, $userId);
        $fileUploader->delete($child['foto'] ?? null, dirname(__DIR__));
        header('Location: index.php?status=hapus');
        exit;
    }

    $error = 'Data anak yang ingin dihapus tidak ditemukan.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $formData = [
        'id' => (int) ($_POST['id'] ?? 0),
        'nama' => trim($_POST['nama'] ?? ''),
        'gender' => trim($_POST['gender'] ?? ''),
        'tanggal_lahir' => trim($_POST['tanggal'] ?? ''),
        'kelas' => trim($_POST['kelas'] ?? ''),
        'alamat' => trim($_POST['alamat'] ?? ''),
        'catatan' => trim($_POST['catatan'] ?? ''),
        'foto' => trim($_POST['foto_lama'] ?? ''),
    ];

    if ($formData['nama'] === '' || $formData['gender'] === '' || $formData['tanggal_lahir'] === '' || $formData['kelas'] === '') {
        $error = 'Nama, gender, tanggal lahir, dan kelas wajib diisi.';
    } else {
        try {
            $existingRecord = null;

            if ($formData['id'] > 0) {
                $existingRecord = $childRepository->findByIdAndUser($formData['id'], $userId);
                if (!$existingRecord) {
                    throw new RuntimeException('Data anak yang akan diperbarui tidak ditemukan.');
                }
            }

            $uploadedPhoto = $fileUploader->upload($_FILES['foto'] ?? []);
            if ($uploadedPhoto !== null) {
                if ($existingRecord && !empty($existingRecord['foto'])) {
                    $fileUploader->delete($existingRecord['foto'], dirname(__DIR__));
                }
                $formData['foto'] = $uploadedPhoto;
            } elseif ($existingRecord) {
                $formData['foto'] = $existingRecord['foto'] ?? '';
            }

            $savedId = $childRepository->save([
                'id' => $formData['id'],
                'user_id' => $userId,
                'nama' => $formData['nama'],
                'gender' => $formData['gender'],
                'tanggal_lahir' => $formData['tanggal_lahir'],
                'kelas' => $formData['kelas'],
                'alamat' => $formData['alamat'],
                'catatan' => $formData['catatan'],
                'foto' => $formData['foto'],
            ]);

            $status = $formData['id'] > 0 ? 'update' : 'create';
            header('Location: index.php?status=' . $status . '&edit=' . $savedId);
            exit;
        } catch (Throwable $exception) {
            $error = $exception->getMessage();
        }
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'create') {
        $success = 'Data anak berhasil ditambahkan.';
    } elseif ($_GET['status'] === 'update') {
        $success = 'Data anak berhasil diperbarui.';
    } elseif ($_GET['status'] === 'hapus') {
        $success = 'Data anak berhasil dihapus.';
    }
}

if ($editId > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $child = $childRepository->findByIdAndUser($editId, $userId);
    if ($child) {
        $formData = [
            'id' => (int) $child['id'],
            'nama' => $child['nama'] ?? '',
            'gender' => $child['gender'] ?? '',
            'tanggal_lahir' => $child['tanggal_lahir'] ?? '',
            'kelas' => $child['kelas'] ?? '',
            'alamat' => $child['alamat'] ?? '',
            'catatan' => $child['catatan'] ?? '',
            'foto' => $child['foto'] ?? '',
        ];
    }
}

$children = $childRepository->allByUser($userId);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anak</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
</head>

<body>

<div class="sidebar">
    <h2 class="logo">InkluSkill</h2>

    <a href="../dasboard/dashboardOrtu.php"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="#" class="active"><i class="ri-user-3-line"></i>Data Anak</a>
</div>

<div class="content">

    <div class="page-header">
        <div>
            <h1>Data Anak</h1>
            <p>Kelola data anak, foto, dan informasi pendukung dalam satu halaman.</p>
        </div>
        <div class="user-badge">
            <i class="ri-user-smile-line"></i>
            <span><?= e($user['name'] ?? 'Pengguna') ?></span>
        </div>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="alert success"><?= e($success) ?></div>
    <?php endif; ?>

    <div class="layout-grid">
        <div class="profile-box">
            <div class="photo-box">
                <?php if ($formData['foto'] !== ''): ?>
                    <img src="../<?= e($formData['foto']) ?>" alt="Foto Anak">
                <?php else: ?>
                    <img src="https://i.ibb.co/YfyqgLw/default-avatar.png" alt="Foto Default">
                <?php endif; ?>
                <p>Foto akan ikut tersimpan saat tombol <b>Simpan</b> ditekan.</p>
            </div>

            <form method="POST" enctype="multipart/form-data" class="big-card">
                <h3><?= $formData['id'] > 0 ? 'Edit Data Anak' : 'Tambah Data Anak' ?></h3>
                <input type="hidden" name="id" value="<?= (int) $formData['id'] ?>">
                <input type="hidden" name="foto_lama" value="<?= e($formData['foto']) ?>">

                <div class="info-grid">
                    <div class="input-group">
                        <label>Nama</label>
                        <input type="text" name="nama" value="<?= e($formData['nama']) ?>" required>
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
                        <input type="date" name="tanggal" value="<?= e($formData['tanggal_lahir']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Kelas</label>
                        <input type="text" name="kelas" value="<?= e($formData['kelas']) ?>" required>
                    </div>

                    <div class="input-group full">
                        <label>Alamat</label>
                        <input type="text" name="alamat" value="<?= e($formData['alamat']) ?>">
                    </div>

                    <div class="input-group full">
                        <label>Catatan</label>
                        <textarea name="catatan"><?= e($formData['catatan']) ?></textarea>
                    </div>

                    <div class="input-group full">
                        <label>Upload Foto</label>
                        <input type="file" name="foto" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <small>Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 2 MB.</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="simpan" class="save-btn">
                        <i class="ri-save-line"></i> Simpan
                    </button>
                    <?php if ($formData['id'] > 0): ?>
                        <a href="index.php" class="secondary-btn">Batal Edit</a>
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
                        <div class="child-item">
                            <div class="child-preview">
                                <?php if (!empty($child['foto'])): ?>
                                    <img src="../<?= e($child['foto']) ?>" alt="Foto <?= e($child['nama'] ?? 'Anak') ?>">
                                <?php else: ?>
                                    <div class="child-avatar"><i class="ri-user-3-line"></i></div>
                                <?php endif; ?>

                                <div>
                                    <h4><?= e($child['nama'] ?? '-') ?></h4>
                                    <p><?= e(($child['kelas'] ?? '-') . ' | ' . ($child['gender'] ?? '-')) ?></p>
                                    <small><?= e($child['tanggal_lahir'] ?? '-') ?></small>
                                </div>
                            </div>

                            <div class="child-actions">
                                <a href="index.php?edit=<?= (int) $child['id'] ?>" class="action-btn edit-btn">Edit</a>
                                <form method="POST" onsubmit="return confirm('Hapus data anak ini?');">
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
