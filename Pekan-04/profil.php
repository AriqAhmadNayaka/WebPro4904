<?php
session_start();
include 'koneksi.php';
include 'profil_helper.php';

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit();
}

ensureProfileSchema($conn);
$user = getCurrentUserProfile($conn, $_SESSION['email']);

if (!$user) {
    session_destroy();
    header("location:login.php");
    exit();
}

syncUserSessionFromProfile($user);

$pesan = isset($_SESSION['profil_success']) ? $_SESSION['profil_success'] : '';
$error = isset($_SESSION['profil_error']) ? $_SESSION['profil_error'] : '';
unset($_SESSION['profil_success'], $_SESSION['profil_error']);

$fotoProfil = getProfilePhotoPath($user['foto_profil']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - WeBandoo+</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/profil.css">
</head>
<body>
    <div class="page">
        <div class="topbar">
            <a href="dashboard.php" class="brand">WeBandoo+</a>
            <div class="topbar-actions">
                <a href="dashboard.php" class="btn-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
                <a href="keluar.php" class="btn-link"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </div>

        <div class="layout">
            <aside class="card profile-summary">
                <div class="profile-photo">
                    <img src="<?php echo htmlspecialchars($fotoProfil); ?>" alt="Foto Profil" id="profileImagePreview">
                </div>
                <h1><?php echo htmlspecialchars(!empty($user['nama_lengkap']) ? $user['nama_lengkap'] : $user['username']); ?></h1>
                <p>@<?php echo htmlspecialchars($user['username']); ?></p>
                <p><?php echo htmlspecialchars($user['email']); ?></p>

                <div class="meta-list">
                    <div class="meta-item">
                        <strong>Nomor HP</strong>
                        <span><?php echo htmlspecialchars(!empty($user['no_hp']) ? $user['no_hp'] : '-'); ?></span>
                    </div>
                    <div class="meta-item">
                        <strong>Kota</strong>
                        <span><?php echo htmlspecialchars(!empty($user['kota']) ? $user['kota'] : '-'); ?></span>
                    </div>
                    <div class="meta-item">
                        <strong>Pekerjaan</strong>
                        <span><?php echo htmlspecialchars(!empty($user['pekerjaan']) ? $user['pekerjaan'] : '-'); ?></span>
                    </div>
                    <div class="meta-item">
                        <strong>Bio Singkat</strong>
                        <span><?php echo htmlspecialchars(!empty($user['bio']) ? $user['bio'] : 'Belum ada bio.'); ?></span>
                    </div>
                </div>
            </aside>

            <section class="card form-card">
                <h2>Profil Saya</h2>
                <p class="lead">Lengkapi data akun agar identitas pengguna terlihat lebih rapi dan informatif.</p>

                <?php if (!empty($pesan)) { ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($pesan); ?></div>
                <?php } ?>

                <?php if (!empty($error)) { ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php } ?>

                <form action="proses_update_profil.php" method="POST" enctype="multipart/form-data">
                    <div class="grid">
                        <div class="field">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>

                        <div class="field">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>">
                        </div>

                        <div class="field">
                            <label for="email">Email</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <span class="hint">Email dipakai sebagai identitas login dan tidak diubah dari halaman ini.</span>
                        </div>

                        <div class="field">
                            <label for="no_hp">Nomor HP</label>
                            <input type="text" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($user['no_hp']); ?>" placeholder="Contoh: 081234567890">
                        </div>

                        <div class="field">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($user['tanggal_lahir']); ?>">
                        </div>

                        <div class="field">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="Laki-laki" <?php echo ($user['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo ($user['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                <option value="Lainnya" <?php echo ($user['jenis_kelamin'] === 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>

                        <div class="field">
                            <label for="kota">Kota</label>
                            <input type="text" id="kota" name="kota" value="<?php echo htmlspecialchars($user['kota']); ?>" placeholder="Contoh: Bandung">
                        </div>

                        <div class="field">
                            <label for="pekerjaan">Pekerjaan</label>
                            <input type="text" id="pekerjaan" name="pekerjaan" value="<?php echo htmlspecialchars($user['pekerjaan']); ?>" placeholder="Contoh: Mahasiswa">
                        </div>

                        <div class="field full">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" placeholder="Isi alamat lengkap"><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                        </div>

                        <div class="field full">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" placeholder="Tulis deskripsi singkat tentang diri Anda"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                        </div>

                        <div class="field full">
                            <label for="foto_profil">Foto Profil</label>
                            <input type="file" id="foto_profil" name="foto_profil" accept=".jpg,.jpeg,.png,.webp">
                            <span class="hint">Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </form>
            </section>
        </div>
    </div>
    <script src="assets/js/profil.js"></script>
</body>
</html>
