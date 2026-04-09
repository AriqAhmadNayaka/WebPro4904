<?php
session_start();

if (!isset($_SESSION['current_user'])) {
    header('Location: ../../auth.php');
    exit;
}

$notification = '';
$notification_type = '';

// SiswaModel child class dari Database untuk semua operasi CRUD siswa
// Sesuai modul 6.4.8: SiswaModel extends Database, mewarisi koneksi $conn
require_once 'classes/../SiswaModel.php';

// Buat objek SiswaModel dengan menyertakan path folder uploads
// Sesuai modul 6.4.5: object dibuat dengan kata kunci "new"
$siswaModel = new SiswaModel(__DIR__ . '/uploads/');

// HANDLE POST: CREATE & UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'simpan') {

    // Bersihkan semua input menggunakan method testInput() dari SiswaModel
    $nisn = $siswaModel->testInput($_POST['nisn'] ?? '');
    $nama = $siswaModel->testInput($_POST['namaLengkap'] ?? '');
    $kelas = $siswaModel->testInput($_POST['kelas'] ?? '');
    $jenisKelamin = $siswaModel->testInput($_POST['jenisKelamin'] ?? '');
    $email = $siswaModel->testInput($_POST['email'] ?? '');
    $noTelepon = $siswaModel->testInput($_POST['noTelepon'] ?? '');
    $alamat = $siswaModel->testInput($_POST['alamat'] ?? '');
    $status = $siswaModel->testInput($_POST['status'] ?? 'Aktif');
    $editId = (int) ($_POST['editId'] ?? 0);

    // Validasi input
    if (empty($nisn)) {
        $notification = 'NISN wajib diisi'; $notification_type = 'error';
    } elseif (empty($nama)) {
        $notification = 'Nama lengkap wajib diisi'; $notification_type = 'error';
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $nama)) {
        $notification = 'Nama hanya boleh berisi huruf dan spasi'; $notification_type = 'error';
    } elseif (empty($kelas)) {
        $notification = 'Kelas wajib dipilih'; $notification_type = 'error';
    } elseif (empty($jenisKelamin)) {
        $notification = 'Jenis kelamin wajib dipilih'; $notification_type = 'error';
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $notification = 'Format email tidak valid'; $notification_type = 'error';
    } else {
        // Proses upload foto via method uploadFoto() dari SiswaModel
        // Sesuai modul 5.4.9 dan 6.4.4: logika upload dibungkus dalam method class
        $hasilUpload = $siswaModel->uploadFoto($_FILES['foto'] ?? null);

        // Cek apakah upload error (nilai kembalian dimulai dengan 'ERROR:')
        if ($hasilUpload !== null && str_starts_with((string)$hasilUpload, 'ERROR:')) {
            $notification      = substr($hasilUpload, 6);
            $notification_type = 'error';
        } else {
            $fotoBaru = $hasilUpload; // null jika tidak ada upload, nama file jika ada

            if ($editId > 0) {
                // Ambil foto lama untuk dihapus jika ada foto baru
                $siswaLama = $siswaModel->getById($editId);
                $fotoLama  = $siswaLama['foto'] ?? null;

                // UPDATE via method update() dari SiswaModel
                $siswaModel->update($editId, $nisn, $nama, $kelas, $jenisKelamin, $email, $noTelepon, $alamat, $status, $fotoLama, $fotoBaru);
                header('Location: index.php?status=diperbarui');
                exit;
            } else {
                // CREATE via method tambah() dari SiswaModel
                $siswaModel->tambah($nisn, $nama, $kelas, $jenisKelamin, $email, $noTelepon, $alamat, $status, $fotoBaru);
                header('Location: index.php?status=ditambahkan');
                exit;
            }
        }
    }
}

// HANDLE GET: HAPUS
if (isset($_GET['hapus'])) {
    // DELETE via method hapus() dari SiswaModel — foto otomatis ikut dihapus
    $siswaModel->hapus((int) $_GET['hapus']);
    header('Location: index.php?status=dihapus');
    exit;
}

// NOTIFIKASI SETELAH REDIRECT
$pesanStatus = [
    'ditambahkan' => 'Siswa baru berhasil ditambahkan!',
    'diperbarui'  => 'Data siswa berhasil diperbarui!',
    'dihapus'     => 'Data siswa berhasil dihapus!',
];
if (isset($_GET['status']) && isset($pesanStatus[$_GET['status']])) {
    $notification      = $pesanStatus[$_GET['status']];
    $notification_type = $_GET['status'] === 'dihapus' ? 'info' : 'success';
}

// READ: Ambil data siswa via method getAll() dari SiswaModel
$filterKelas  = $siswaModel->testInput($_GET['kelas']         ?? '');
$filterStatus = $siswaModel->testInput($_GET['status_filter'] ?? '');
$keyword      = $siswaModel->testInput($_GET['q']             ?? '');

// Panggil method getAll() dengan parameter filter logika query ada di dalam SiswaModel
$students = $siswaModel->getAll($filterKelas, $filterStatus, $keyword);

// unset() memanggil destruktor Database yang menutup koneksi secara otomatis
unset($siswaModel);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Siswa - InkluSkill</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    #filterForm .toolbar input,
    #filterForm .toolbar select { width: auto; }
    #filterForm .toolbar input[name="q"] { flex: 1; }
  </style>
</head>
<body>

<?php if (!empty($notification)): ?>
<div id="phpNotification" style="
    position:fixed; top:20px; right:20px;
    background:<?= $notification_type === 'error' ? '#ef4444' : ($notification_type === 'info' ? '#3b82f6' : '#10b981') ?>;
    color:white; padding:14px 18px; border-radius:10px; font-size:13px; z-index:9999; font-family:inherit;">
    <?= htmlspecialchars($notification) ?>
</div>
<?php endif; ?>

<div class="sidebar">
  <h2 class="logo">InkluSkill</h2>
  <nav class="menu">
    <a href="../dashboard/dashboardSekolah.php"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a class="active"><i class="ri-user-3-line"></i>Data Siswa</a>
    <a href="#"><i class="ri-file-edit-line"></i>Pendaftaran Pelatihan</a>
    <a href="#"><i class="ri-bar-chart-line"></i>Laporan</a>
    <a href="#"><i class="ri-archive-line"></i>Rekap Siswa</a>
    <a href="#"><i class="ri-user-settings-line"></i>Profil</a>
  </nav>
  <button class="logout" onclick="window.location.href='../../auth.php?logout=1'">
    <i class="ri-logout-box-line"></i> Logout
  </button>
</div>

<div class="content">
  <div class="topbar">
    <h1>Manajemen Data Siswa</h1>
    <div class="user-info">
      <span><?= htmlspecialchars($_SESSION['current_user']['name']) ?></span>
      <img src="../../assets/fotonovi.webp">
    </div>
  </div>

  <div class="dashboard-body">
    <div class="header">
      <h1>Daftar Siswa</h1>
      <p>Kelola data siswa secara terstruktur</p>
    </div>

    <!-- Filter via GET, parameter dikirim ke method getAll() SiswaModel -->
    <form method="GET" action="index.php" id="filterForm">
      <div class="toolbar">
        <input type="text" name="q" placeholder="Cari nama, NISN, atau kelas..."
               value="<?= htmlspecialchars($keyword) ?>" onkeyup="this.form.submit()">
        <select name="kelas" onchange="this.form.submit()">
          <option value="">Semua Kelas</option>
          <option value="X"   <?= $filterKelas === 'X'   ? 'selected' : '' ?>>Kelas X</option>
          <option value="XI"  <?= $filterKelas === 'XI'  ? 'selected' : '' ?>>Kelas XI</option>
          <option value="XII" <?= $filterKelas === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
        </select>
        <select name="status_filter" onchange="this.form.submit()">
          <option value="">Semua Status</option>
          <option value="Aktif"       <?= $filterStatus === 'Aktif'       ? 'selected' : '' ?>>Aktif</option>
          <option value="Tidak Aktif" <?= $filterStatus === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>
        <button type="button" class="btn-primary" onclick="openModal()">
          <i class="ri-add-line"></i> Tambah Siswa
        </button>
      </div>
    </form>

    <!-- Tabel READ: data dari method getAll() SiswaModel -->
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Foto</th><th>NISN</th><th>Nama</th><th>Kelas</th>
            <th>JK</th><th>Email</th><th>Status</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($students as $s): ?>
          <tr>
            <td>
              <?php if (!empty($s['foto'])): ?>
                <img src="uploads/<?= htmlspecialchars($s['foto']) ?>"
                     style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
              <?php else: ?>
                <i class="ri-user-3-line" style="font-size:24px;color:#bbb;"></i>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($s['nisn']) ?></td>
            <td><?= htmlspecialchars($s['nama']) ?></td>
            <td><?= htmlspecialchars($s['kelas']) ?></td>
            <td><?= htmlspecialchars($s['jenis_kelamin']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td>
              <span class="status <?= $s['status'] === 'Aktif' ? 'active' : 'inactive' ?>">
                <?= htmlspecialchars($s['status']) ?>
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <button class="btn-icon btn-edit"
                  onclick="openEditModal(<?= (int)$s['id'] ?>, '<?= htmlspecialchars($s['nisn']) ?>', '<?= htmlspecialchars($s['nama']) ?>', '<?= $s['kelas'] ?>', '<?= $s['jenis_kelamin'] ?>', '<?= htmlspecialchars($s['email']) ?>', '<?= htmlspecialchars($s['no_telepon']) ?>', '<?= htmlspecialchars($s['alamat']) ?>', '<?= $s['status'] ?>', '<?= htmlspecialchars($s['foto'] ?? '') ?>')">
                  Edit
                </button>
                <button class="btn-icon btn-delete" onclick="confirmHapus(<?= (int)$s['id'] ?>)">
                  Hapus
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL: form POST + enctype multipart/form-data untuk upload foto -->
<div class="modal" id="studentModal">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="modalTitle">Tambah Siswa</h3>
      <button class="close-btn" onclick="closeModal()">×</button>
    </div>
    <form id="studentForm" method="POST" action="index.php" enctype="multipart/form-data">
      <input type="hidden" name="action" value="simpan">
      <input type="hidden" name="editId" id="editId" value="0">
      <div class="form-grid">
        <input id="nisn"        name="nisn"        placeholder="NISN"         required>
        <input id="namaLengkap" name="namaLengkap" placeholder="Nama Lengkap" required>
        <select id="kelas" name="kelas" required>
          <option value="">Pilih Kelas</option>
          <option>X</option><option>XI</option><option>XII</option>
        </select>
        <select id="jenisKelamin" name="jenisKelamin" required>
          <option value="">Jenis Kelamin</option>
          <option>Laki-laki</option><option>Perempuan</option>
        </select>
        <input id="email"     name="email"     placeholder="Email">
        <input id="noTelepon" name="noTelepon" placeholder="No Telepon">
      </div>
      <textarea id="alamat" name="alamat" placeholder="Alamat"></textarea>
      <select id="status" name="status">
        <option>Aktif</option><option>Tidak Aktif</option>
      </select>
      <!-- Upload foto diproses oleh method uploadFoto() SiswaModel -->
      <div style="margin-top:12px;">
        <label style="font-size:13px;font-weight:500;display:block;margin-bottom:6px;">
          Foto Siswa <span style="color:#999;font-weight:400;">(opsional, JPG/PNG)</span>
        </label>
        <div id="fotoPreviewWrap" style="display:none;margin-bottom:8px;">
          <img id="fotoPreview" src="" alt="Foto saat ini"
               style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #e5e7eb;">
        </div>
        <input type="file" name="foto" id="fotoInput" accept="image/*"
               style="font-size:13px;" onchange="previewFoto(this)">
      </div>
      <div class="form-actions">
        <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById('studentModal').classList.add('show');
    document.getElementById('modalTitle').textContent = 'Tambah Siswa Baru';
    document.getElementById('studentForm').reset();
    document.getElementById('editId').value = '0';
    document.getElementById('fotoPreviewWrap').style.display = 'none';
    document.getElementById('fotoPreview').src = '';
  }

  function openEditModal(id, nisn, nama, kelas, jk, email, telp, alamat, status, foto) {
    document.getElementById('studentModal').classList.add('show');
    document.getElementById('modalTitle').textContent = 'Edit Data Siswa';
    document.getElementById('editId').value       = id;
    document.getElementById('nisn').value         = nisn;
    document.getElementById('namaLengkap').value  = nama;
    document.getElementById('kelas').value        = kelas;
    document.getElementById('jenisKelamin').value = jk;
    document.getElementById('email').value        = email;
    document.getElementById('noTelepon').value    = telp;
    document.getElementById('alamat').value       = alamat;
    document.getElementById('status').value       = status;
    if (foto && foto !== '') {
      document.getElementById('fotoPreview').src = 'uploads/' + foto;
      document.getElementById('fotoPreviewWrap').style.display = 'block';
    } else {
      document.getElementById('fotoPreviewWrap').style.display = 'none';
    }
  }

  function closeModal() {
    document.getElementById('studentModal').classList.remove('show');
    document.getElementById('studentForm').reset();
    document.getElementById('editId').value = '0';
    document.getElementById('fotoPreviewWrap').style.display = 'none';
    document.getElementById('fotoPreview').src = '';
  }

  function previewFoto(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('fotoPreview').src = e.target.result;
        document.getElementById('fotoPreviewWrap').style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function confirmHapus(id) {
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: 'Data siswa yang dihapus tidak dapat dikembalikan!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#524fa1',
      cancelButtonColor: '#dc3545',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then(result => {
      if (result.isConfirmed) window.location.href = 'index.php?hapus=' + id;
    });
  }

  const notif = document.getElementById('phpNotification');
  if (notif) setTimeout(() => notif.remove(), 3500);
</script>
</body>
</html>