<?php
// Mengambil data semua dokter dari database menggunakan helper function getData
include 'db.php';
include 'functions.php';

$dokter = getData($conn, "dokter");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | Nuids</title>

  <link rel="stylesheet" href="global.css" />
  <link rel="stylesheet" href="admin.css" />
  <link rel="stylesheet" href="popup.css" />
</head>

<body>
  <div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar acrylic-card">
      <div class="profile-sidebar">
        <div class="avatar-placeholder">👤</div>
        <h3 class="adminName">admin</h3>
        <p>Super Administrator</p>
      </div>

      <nav class="sidebar-nav">
        <a href="admin.php" class="nav-item active">Manajemen Dokter</a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

      <section id="manajemenDokter" class="page-section show">
        <h2 class="section-title">Manajemen Dokter</h2>

        <div class="acrylic-card">
          <div
            style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
              ">
            <h4>Daftar Dokter</h4>
            <a href="tambah-dokter.php" class="btn-primary">
              + Tambah Dokter
            </a>
          </div>

          <div class="user-filter-panel">
            <input
              type="text"
              id="searchDoctorInput"
              placeholder="Cari dokter..."
              class="custom-input" />
            <select id="filterDoctorSpecialty" class="custom-input">
              <option value="all">Semua Spesialisasi</option>
              <option value="umum">Dokter Umum</option>
              <option value="anak">Spesialis Anak</option>
              <option value="penyakit-dalam">Penyakit Dalam</option>
              <option value="jantung">Jantung</option>
              <option value="gigi">Gigi</option>
              <option value="kulit">Kulit dan Kelamin</option>
            </select>
          </div>

          <table class="custom-table">
            <thead>
              <tr>
                <th>Nama Dokter</th>
                <th>Spesialisasi</th>
                <th>Kontak</th>
                <th>Sertifikat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="doctorTableBody">
              <?php for ($i = 0; $i < count($dokter); $i++) : ?>
                <tr>
                  <td><?= $dokter[$i]['nama'] ?></td>
                  <td>Dokter <?= ucfirst($dokter[$i]['spesialisasi']) ?></td>
                  <td>
                    <div style="font-size: 0.85rem; color: #5f6b85;">
                      <?= $dokter[$i]['email'] ?><br>
                      <?= $dokter[$i]['telepon'] ?>
                    </div>
                  </td>
                  <td>
                    <?php if (!empty($dokter[$i]['sertifikat'])) : ?>
                      <span class="status active" style="font-size: 0.75rem; cursor: pointer;" onclick="showCertificate('uploads/' + <?= htmlspecialchars(json_encode($dokter[$i]['sertifikat'])) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['nama'])) ?>)">📄 Lihat File</span>
                    <?php else : ?>
                      <span class="status offline" style="font-size: 0.75rem; opacity: 0.6;">Tidak ada file</span>
                    <?php endif; ?>
                  </td>
                  <td class="action-cell">
                    <button class="btn-action detail" onclick="showDoctorDetail(<?= htmlspecialchars(json_encode($dokter[$i]['nama'])) ?>, <?= htmlspecialchars(json_encode(ucfirst($dokter[$i]['spesialisasi']))) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['email'])) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['telepon'])) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['nomor_sip'])) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['kode_dokter'])) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['bio'])) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['sertifikat'])) ?>)">
                      Detail
                    </button>
                    <a href="edit.php?id=<?= $dokter[$i]['id'] ?>" class="btn-action edit">Edit</a>
                    <button class="btn-action delete" onclick="confirmDelete(<?= htmlspecialchars(json_encode($dokter[$i]['nama'])) ?>, <?= htmlspecialchars(json_encode($dokter[$i]['id'])) ?>)">Hapus</button>
                  </td>
                </tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Modal Detail Dokter -->
      <div id="doctorDetailModal" class="modal hidden">
        <div class="modal-content" style="width: 480px; max-width: 90vw;">
          <span id="closeDoctorModal" class="modal-close" style="font-size: 32px;">&times;</span>
          <div class="modal-header">
            <div class="modal-avatar">👨‍⚕️</div>
            <h3 id="modalDoctorName">Nama Dokter</h3>
            <p id="modalDoctorSpecialist" style="color: #1a75ff; font-weight: 600;">Spesialis</p>
          </div>
          <div class="modal-body" style="text-align: left; margin-top: 20px;">
            <div style="display: grid; gap: 15px;">
              <p><strong>Email:</strong> <br><span id="modalDoctorEmail" style="color: #5f6b85;">-</span></p>
              <p><strong>Telepon:</strong> <br><span id="modalDoctorPhone" style="color: #5f6b85;">-</span></p>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <p><strong>SIP / STR:</strong> <br><span id="modalDoctorSIP" style="color: #5f6b85;">-</span></p>
                <p><strong>ID Dokter:</strong> <br><span id="modalDoctorID" style="color: #5f6b85;">-</span></p>
              </div>
              <p><strong>Deskripsi Singkat:</strong> <br><span id="modalDoctorBio" style="color: #5f6b85; line-height: 1.6;">-</span></p>
              <p>
                <strong>Dokumen Sertifikat:</strong> <br>
                <a href="javascript:void(0)" id="modalDoctorCert" style="color: #1a75ff; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; margin-top: 5px;">
                  📄 <span id="modalDoctorCertName">Lihat File</span>
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Certificate Viewer -->
      <div id="certificateModal" class="modal hidden">
        <div class="modal-content">
          <span id="closeCertModal" class="modal-close">&times;</span>
          <div class="cert-modal-header">
            <h3>Certificate Viewer</h3>
            <span id="certOwner" style="font-size: 0.85rem; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">-</span>
          </div>
          <div class="cert-viewer-container">
            <img id="certImage" src="" alt="Sertifikat">
          </div>
        </div>
      </div>

    </main>
  </div>

  <script src="main.js"></script>
  <script src="popup.js"></script>
  <script>
    // Fungsi untuk menampilkan detail dokter pada modal
    function showDoctorDetail(nama, spesialis, email, telepon, sip, id, bio, cert) {
      // Mengisi konten modal dengan data dari parameter
      document.getElementById('modalDoctorName').innerText = nama;
      document.getElementById('modalDoctorSpecialist').innerText = 'Dokter ' + spesialis;
      document.getElementById('modalDoctorEmail').innerText = email;
      document.getElementById('modalDoctorPhone').innerText = telepon;
      document.getElementById('modalDoctorSIP').innerText = sip || '-';
      document.getElementById('modalDoctorID').innerText = id || '-';
      document.getElementById('modalDoctorBio').innerText = bio || 'Tidak ada deskripsi.';

      // Logika untuk menampilkan/menyembunyikan link sertifikat
      const certLink = document.getElementById('modalDoctorCert');
      if (cert) {
        certLink.style.display = 'inline-flex';
        certLink.onclick = () => showCertificate('uploads/' + cert, nama);
      } else {
        certLink.style.display = 'none';
      }

      // Menampilkan modal dengan menghapus class hidden
      const modal = document.getElementById('doctorDetailModal');
      modal.classList.remove('hidden');
    }

    // Fungsi untuk menampilkan gambar sertifikat pada modal terpisah
    function showCertificate(fileUrl, ownerName) {
      const modal = document.getElementById('certificateModal');
      const img = document.getElementById('certImage');
      const ownerSpan = document.getElementById('certOwner');

      img.src = fileUrl;
      ownerSpan.innerText = 'Milik: ' + ownerName;
      modal.classList.remove('hidden');
    }

    // Event listener untuk menutup modal detail saat tombol close diklik
    document.getElementById('closeDoctorModal').addEventListener('click', () => {
      document.getElementById('doctorDetailModal').classList.add('hidden');
    });

    // Event listener untuk menutup modal sertifikat saat tombol close diklik
    document.getElementById('closeCertModal').addEventListener('click', () => {
      document.getElementById('certificateModal').classList.add('hidden');
    });

    // Menutup modal secara otomatis jika area di luar modal diklik (overlay)
    window.onclick = function(event) {
      const detailModal = document.getElementById('doctorDetailModal');
      const certModal = document.getElementById('certificateModal');

      if (event.target == detailModal) {
        detailModal.classList.add('hidden');
      }
      if (event.target == certModal) {
        certModal.classList.add('hidden');
      }
    }

    // Fungsi konfirmasi hapus menggunakan custom popup
    function confirmDelete(nama, id) {
      customConfirm('Apakah Anda yakin ingin menghapus data untuk ' + nama + '?', function() {
        // Jika dikonfirmasi, arahkan ke delete.php dengan ID yang sesuai
        window.location = 'delete.php?id=' + id;
      });
    }

    // Menangani notifikasi (flash message) dari URL setelah proses hapus
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'success') : ?>
      window.addEventListener('load', function() {
        customAlert('Data dokter telah dihapus dari sistem.', 'success', 'Berhasil Dihapus');
      });
    <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 'error') : ?>
      window.addEventListener('load', function() {
        customAlert('Gagal menghapus data dokter. Silakan coba lagi.', 'error', 'Error');
      });
    <?php endif; ?>
  </script>

</body>

</html>