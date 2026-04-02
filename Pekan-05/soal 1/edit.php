<?php
include 'db.php';
include 'functions.php';

/**
 * 1. Validasi & Pengambilan Data
 * Memastikan ID dokter tersedia di URL dan datanya ada di database.
 */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = $_GET['id'];
$result = getData($conn, "dokter", ['id' => $id]);

if (count($result) === 0) {
    // Jika ID tidak valid atau data tidak ditemukan, kembali ke dashboard
    echo "<script>alert('Data dokter tidak ditemukan!'); window.location='admin.php';</script>";
    exit;
}

$oldData = $result[0]; // Menyimpan data lama untuk ditampilkan di form

/**
 * 2. Proses Pembaruan Data (Update)
 * Dijalankan ketika form disubmit (tombol 'update' diklik).
 */
if (isset($_POST['update'])) {
    $data = [
        'nama' => $_POST['nama'],
        'spesialisasi' => $_POST['spesialisasi'],
        'email' => $_POST['email'],
        'telepon' => $_POST['telepon'],
        'nomor_sip' => $_POST['nomor_sip'],
        'kode_dokter' => $_POST['kode_dokter'],
        'bio' => $_POST['bio']
    ];

    /**
     * Handle File Update
     * Fungsi ini akan mengecek apakah ada file baru. 
     * Jika ada, file lama akan dihapus dan file baru diunggah.
     */
    $fileUpdate = handleFileUpdate($_FILES['sertifikat'], $oldData['sertifikat'], 'uploads/');
    
    if ($fileUpdate['status'] !== 'error') {
        $data['sertifikat'] = $fileUpdate['filename'];
        
        try {
            // Melakukan update ke database
            updateData($conn, "dokter", $data, $id);
            $success_msg = "Data dokter berhasil diperbarui!";
            
            // Sinkronisasi data lama dengan data baru agar form langsung terupdate
            $oldData = array_merge($oldData, $data);
        } catch (mysqli_sql_exception $e) {
            $error_msg = "Gagal memperbarui data: " . $e->getMessage();
        }
    } else {
        $error_msg = "Gagal update sertifikat: " . $fileUpdate['msg'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Dokter | Nuids</title>

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
            <span class="page-kicker">Edit Data Dokter</span>
            <h2 class="section-title">Perbarui Informasi Dokter</h2>
            <p class="page-subtitle">
              Pastikan informasi yang Anda masukkan sudah benar sebelum menyimpan perubahan.
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
                  <p class="section-desc">Data identitas utama dokter.</p>
                </div>

                <div class="doctor-form-grid">
                  <div class="form-group">
                    <label for="doctorName">Nama Dokter <span class="required-mark">*</span></label>
                    <input id="doctorName" name="nama" type="text" class="custom-input" placeholder="Nama lengkap" value="<?= htmlspecialchars($oldData['nama']) ?>" required />
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
                    <input id="doctorEmail" name="email" type="email" class="custom-input" placeholder="nama@nuids.id" value="<?= htmlspecialchars($oldData['email']) ?>" required />
                  </div>

                  <div class="form-group">
                    <label for="doctorPhone">Nomor Telepon <span class="required-mark">*</span></label>
                    <input id="doctorPhone" name="telepon" type="tel" class="custom-input" placeholder="08xxxxxxxxxx" value="<?= htmlspecialchars($oldData['telepon']) ?>" required />
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
                    <input id="doctorLicense" name="nomor_sip" type="text" class="custom-input" placeholder="Masukkan nomor izin" value="<?= htmlspecialchars($oldData['nomor_sip']) ?>" />
                  </div>

                  <div class="form-group">
                    <label for="doctorSlug">Kode / ID Dokter</label>
                    <input id="doctorSlug" name="kode_dokter" type="text" class="custom-input" placeholder="Contoh: DOK-001" value="<?= htmlspecialchars($oldData['kode_dokter']) ?>" />
                  </div>

                  <div class="form-group full-width">
                    <label for="doctorBio">Deskripsi Singkat</label>
                    <textarea id="doctorBio" name="bio" class="custom-input" maxlength="300" placeholder="Tulis profil singkat dokter"><?= htmlspecialchars($oldData['bio']) ?></textarea>
                  </div>

                  <div class="form-group full-width">
                    <label for="doctorCert">Update Sertifikat / Dokumen</label>
                    <input id="doctorCert" name="sertifikat" type="file" class="custom-input file-input" accept=".pdf,.doc,.docx,.jpg,.png" />
                    <span class="field-hint">Biarkan kosong jika tidak ingin mengubah sertifikat. (Lama: <?= htmlspecialchars($oldData['sertifikat']) ?>)</span>
                  </div>
                </div>

                <div class="form-actions full-width">
                  <a href="admin.php" class="btn-secondary">Batal</a>
                  <button type="submit" class="btn-primary" name="update">Simpan Perubahan</button>
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
