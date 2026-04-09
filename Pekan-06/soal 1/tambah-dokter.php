<?php
// Memuat file inisialisasi (autoloader & session)
require_once 'config/init.php';

// Proteksi halaman dashboard
Auth::checkLogin();

/**
 * Flow Penambahan Dokter Baru (Create)
 * Dijalankan ketika form disubmit (tombol 'tambah' diklik).
 */
if (isset($_POST['tambah'])) {
  $dokumen = $_FILES['sertifikat'] ?? null;

  // 1. Upload File: Memanggil helper FileHelper::upload untuk validasi dan simpan file
  $uploadResult = FileHelper::upload($dokumen);
  
  if ($uploadResult['status'] == "success") {
    $data = $_POST;
    unset($data['tambah']); // Membersihkan key 'tambah' dari array POST
    $data['sertifikat'] = $uploadResult['filename']; // Mengaitkan nama file yang baru diupload
    
    $doctorModel = new Doctor();
    try {
      // 2. Simpan Database: Gunakan model untuk menyisipkan data dokter baru
      $doctorModel->create($data);
      $success_msg = "Dokter berhasil ditambahkan!";
    } catch (Exception $e) {
      $error_msg = "Error! pastikan email dan kode dokter belum terdaftar!";
    }
  } else {
    // Feedback jika upload gagal
    $error_msg = "Gagal upload sertifikat: " . $uploadResult['msg'];
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Dokter | Nuids</title>

  <link rel="stylesheet" href="global.css" />
  <link rel="stylesheet" href="admin.css" />
  <link rel="stylesheet" href="popup.css" />
</head>

<body class="no-sidebar-page">
  <div class="admin-wrapper">
    <main class="main-content">
      <section class="page-section show">
        <div class="page-header">
          <div>
            <a href="admin.php" class="page-backlink">Kembali ke daftar dokter</a>
            <span class="page-kicker">Form Data Dokter</span>
            <h2 class="section-title">Tambah Dokter Baru</h2>
            <p class="page-subtitle">
              Lengkapi data dokter di bawah ini untuk menambahkan ke dalam sistem Nuids.
            </p>
          </div>
        </div>

        <div class="doctor-form-layout">
          <div class="doctor-form-column">
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
                  <a href="admin.php" class="btn-secondary">Batal</a>
                  <button type="submit" class="btn-primary" name="tambah">Tambah Dokter</button>
                </div>
              </div>
            </form>
          </div>


        </div>
      </section>
    </main>
  </div>

  <script src="main.js"></script>
  <script src="popup.js"></script>
  <script>
    <?php if (isset($success_msg)) : ?>
      customAlert("<?= $success_msg ?>", "success", "Berhasil", function() {
        window.location = 'admin.php';
      });
    <?php endif; ?>

    <?php if (isset($error_msg)) : ?>
      customAlert("<?= $error_msg ?>", "error", "Gagal");
    <?php endif; ?>
  </script>
</body>

</html>