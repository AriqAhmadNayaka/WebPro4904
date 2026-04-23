<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Dokter | Nuids</title>

  <link rel="stylesheet" href="<?= base_url('global.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('admin.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('popup.css') ?>" />
</head>

<body class="no-sidebar-page">
  <!-- Form edit dokter: data awal diisi dari oldData (Admin::edit) -->
  <div class="admin-wrapper">
    <main class="main-content">
      <section class="page-section show">
        <div class="page-header">
          <div>
            <a href="<?= base_url('index.php?c=admin&m=index') ?>" class="page-backlink">Kembali ke daftar dokter</a>
            <span class="page-kicker">Edit Data Dokter</span>
            <h2 class="section-title">Perbarui Informasi Dokter</h2>
            <p class="page-subtitle">
              Pastikan informasi yang Anda masukkan sudah benar sebelum menyimpan perubahan.
            </p>
          </div>
        </div>

        <div class="doctor-form-layout">
          <div class="doctor-form-column">
            <!-- multipart tetap dipakai karena file sertifikat bisa diganti -->
            <form class="doctor-form-shell" method="POST" enctype="multipart/form-data">
              <div class="acrylic-card doctor-form-card form-section-card">
                <div class="section-heading">
                  <div>
                    <span class="section-step">01</span>
                    <h3>Informasi Utama</h3>
                  </div>
                  <p class="section-desc">Data identitas utama dokter.</p>
                </div>

                <div class="doctor-form-grid">
                  <div class="form-group">
                    <label for="doctorName">Nama Dokter <span class="required-mark">*</span></label>
                    <input id="doctorName" name="nama" type="text" class="custom-input" placeholder="Nama lengkap" value="<?= htmlspecialchars($oldData['nama'] ?? '') ?>" required />
                  </div>

                  <div class="form-group">
                    <label for="doctorSpecialty">Spesialisasi <span class="required-mark">*</span></label>
                    <select id="doctorSpecialty" name="spesialisasi" class="custom-input" required>
                      <option value="">Pilih spesialisasi</option>
                      <option value="umum" <?= $oldData['spesialisasi'] == 'umum' ? 'selected' : '' ?>>Dokter Umum</option>
                      <option value="anak" <?= $oldData['spesialisasi'] == 'anak' ? 'selected' : '' ?>>Spesialis Anak</option>
                      <option value="penyakit-dalam" <?= $oldData['spesialisasi'] == 'penyakit-dalam' ? 'selected' : '' ?>>Penyakit Dalam</option>
                      <option value="jantung" <?= $oldData['spesialisasi'] == 'jantung' ? 'selected' : '' ?>>Jantung</option>
                      <option value="gigi" <?= $oldData['spesialisasi'] == 'gigi' ? 'selected' : '' ?>>Gigi</option>
                      <option value="kulit" <?= $oldData['spesialisasi'] == 'kulit' ? 'selected' : '' ?>>Kulit dan Kelamin</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="doctorEmail">Email <span class="required-mark">*</span></label>
                    <input id="doctorEmail" name="email" type="email" class="custom-input" placeholder="nama@nuids.id" value="<?= htmlspecialchars($oldData['email'] ?? '') ?>" required />
                  </div>

                  <div class="form-group">
                    <label for="doctorPhone">Nomor Telepon <span class="required-mark">*</span></label>
                    <input id="doctorPhone" name="telepon" type="tel" class="custom-input" placeholder="08xxxxxxxxxx" value="<?= htmlspecialchars($oldData['telepon'] ?? '') ?>" required />
                  </div>
                </div>
              </div>

              <div class="acrylic-card doctor-form-card form-section-card">
                <div class="section-heading">
                  <div>
                    <span class="section-step">02</span>
                    <h3>Profil Tambahan</h3>
                  </div>
                  <p class="section-desc">Lengkapi informasi pendukung lainnya.</p>
                </div>

                <div class="doctor-form-grid">
                  <div class="form-group">
                    <label for="doctorLicense">Nomor SIP / STR</label>
                    <input id="doctorLicense" name="nomor_sip" type="text" class="custom-input" placeholder="Masukkan nomor izin" value="<?= htmlspecialchars($oldData['nomor_sip'] ?? '') ?>" />
                  </div>

                  <div class="form-group">
                    <label for="doctorSlug">Kode / ID Dokter</label>
                    <input id="doctorSlug" name="kode_dokter" type="text" class="custom-input" placeholder="Contoh: DOK-001" value="<?= htmlspecialchars($oldData['kode_dokter'] ?? '') ?>" />
                  </div>

                  <div class="form-group full-width">
                    <label for="doctorBio">Deskripsi Singkat</label>
                    <textarea id="doctorBio" name="bio" class="custom-input" maxlength="300" placeholder="Tulis profil singkat dokter"><?= htmlspecialchars($oldData['bio'] ?? '') ?></textarea>
                  </div>

                  <div class="form-group full-width">
                    <label for="doctorCert">Update Sertifikat / Dokumen</label>
                    <input id="doctorCert" name="sertifikat" type="file" class="custom-input file-input" accept=".pdf,.doc,.docx,.jpg,.png" />
                    <span class="field-hint">Biarkan kosong jika tidak ingin mengubah sertifikat. (Lama: <?= htmlspecialchars($oldData['sertifikat'] ?? '') ?>)</span>
                  </div>
                </div>

                <div class="form-actions full-width">
                  <a href="<?= base_url('index.php?c=admin&m=index') ?>" class="btn-secondary">Batal</a>
                  <button type="submit" class="btn-primary" name="update">Simpan Perubahan</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="<?= base_url('main.js') ?>"></script>
  <script src="<?= base_url('popup.js') ?>"></script>
  <script>
    // Popup ini membantu user tahu update berhasil atau gagal tanpa pindah halaman langsung.
    <?php if (!empty($success_msg)) : ?>
      customAlert("<?= $success_msg ?>", "success", "Berhasil", function() {
        window.location = '<?= base_url('index.php?c=admin&m=index') ?>';
      });
    <?php endif; ?>

    <?php if (!empty($error_msg)) : ?>
      customAlert("<?= $error_msg ?>", "error", "Gagal");
    <?php endif; ?>
  </script>
</body>

</html>


