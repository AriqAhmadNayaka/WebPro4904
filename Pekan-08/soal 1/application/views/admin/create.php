<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Dokter | Nuids</title>

  <link rel="stylesheet" href="<?= base_url('global.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('admin.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('popup.css') ?>" />
</head>

<body class="no-sidebar-page">
  <!-- Form tambah dokter: submit ke Admin::create -->
  <div class="admin-wrapper">
    <main class="main-content">
      <section class="page-section show">
        <div class="page-header">
          <div>
            <a href="<?= base_url('index.php?c=admin&m=index') ?>" class="page-backlink">Kembali ke daftar dokter</a>
            <span class="page-kicker">Form Data Dokter</span>
            <h2 class="section-title">Tambah Dokter Baru</h2>
            <p class="page-subtitle">
              Lengkapi data dokter di bawah ini untuk menambahkan ke dalam sistem Nuids.
            </p>
          </div>
        </div>

        <div class="doctor-form-layout">
          <div class="doctor-form-column">
            <!-- multipart wajib karena ada upload sertifikat -->
            <form class="doctor-form-shell" method="POST" enctype="multipart/form-data">
              <div class="acrylic-card doctor-form-card form-section-card">
                <div class="section-heading">
                  <div>
                    <span class="section-step">01</span>
                    <h3>Informasi Utama</h3>
                  </div>
                  <p class="section-desc">Data identitas yang akan tampil paling sering di daftar dokter.</p>
                </div>

                <div class="doctor-form-grid">
                  <div class="form-group">
                    <label for="doctorName">Nama Dokter <span class="required-mark">*</span></label>
                    <input id="doctorName" name="nama" type="text" class="custom-input" placeholder="Contoh: dr. Amanda Putri, Sp.A" required />
                    <span class="field-hint">Gunakan nama lengkap sesuai format tampilan profil dokter.</span>
                  </div>

                  <div class="form-group">
                    <label for="doctorSpecialty">Spesialisasi <span class="required-mark">*</span></label>
                    <select id="doctorSpecialty" name="spesialisasi" class="custom-input" required>
                      <option value="">Pilih spesialisasi</option>
                      <option value="umum">Dokter Umum</option>
                      <option value="anak">Spesialis Anak</option>
                      <option value="penyakit-dalam">Penyakit Dalam</option>
                      <option value="jantung">Jantung</option>
                      <option value="gigi">Gigi</option>
                      <option value="kulit">Kulit dan Kelamin</option>
                    </select>
                    <span class="field-hint">Pilih yang paling mendekati kategori layanan dokter.</span>
                  </div>

                  <div class="form-group">
                    <label for="doctorEmail">Email <span class="required-mark">*</span></label>
                    <input id="doctorEmail" name="email" type="email" class="custom-input" placeholder="nama@nuids.id" required />
                    <span class="field-hint">Dipakai untuk kebutuhan administrasi dan komunikasi internal.</span>
                  </div>

                  <div class="form-group">
                    <label for="doctorPhone">Nomor Telepon <span class="required-mark">*</span></label>
                    <input id="doctorPhone" name="telepon" type="tel" class="custom-input" placeholder="08xxxxxxxxxx" required />
                    <span class="field-hint">Masukkan nomor yang aktif dan mudah dihubungi.</span>
                  </div>
                </div>
              </div>



              <div class="acrylic-card doctor-form-card form-section-card">
                <div class="section-heading">
                  <div>
                    <span class="section-step">02</span>
                    <h3>Profil Tambahan</h3>
                  </div>
                  <p class="section-desc">Lengkapi informasi pendukung untuk administrasi dan unggah dokumen pendukung.</p>
                </div>

                <div class="doctor-form-grid">
                  <div class="form-group">
                    <label for="doctorLicense">Nomor SIP / STR</label>
                    <input id="doctorLicense" name="nomor_sip" type="text" class="custom-input" placeholder="Masukkan nomor izin praktek" />
                    <span class="field-hint">Simpan nomor izin agar mudah diverifikasi saat dibutuhkan.</span>
                  </div>

                  <div class="form-group">
                    <label for="doctorSlug">Kode / ID Dokter</label>
                    <input id="doctorSlug" name="kode_dokter" type="text" class="custom-input" placeholder="Contoh: DOK-AND-001" />
                    <span class="field-hint">Opsional untuk kebutuhan penandaan internal atau integrasi sistem.</span>
                  </div>

                  <div class="form-group full-width">
                    <label for="doctorBio">Deskripsi Singkat</label>
                    <textarea id="doctorBio" name="bio" class="custom-input" maxlength="300" placeholder="Tulis profil singkat, fokus layanan, atau catatan penting dokter"></textarea>
                    <span class="field-hint">Ringkas saja, cukup 1-3 kalimat agar tetap mudah dibaca.</span>
                  </div>

                  <div class="form-group full-width">
                    <label for="doctorCert">Unggah Sertifikat / Dokumen <span class="required-mark">*</span></label>
                    <input id="doctorCert" name="sertifikat" type="file" class="custom-input file-input" accept=".pdf,.doc,.docx,.jpg,.png" required />
                    <span class="field-hint">Format yang didukung: PDF, DOCX, JPG, atau PNG (Maks. 5MB).</span>
                  </div>
                </div>

                <div class="form-actions full-width">
                  <a href="<?= base_url('index.php?c=admin&m=index') ?>" class="btn-secondary">Batal</a>
                  <button type="submit" class="btn-primary" name="tambah">Tambah Dokter</button>
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
    // Popup status berhasil/gagal dikirim dari controller melalui variable view.
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


