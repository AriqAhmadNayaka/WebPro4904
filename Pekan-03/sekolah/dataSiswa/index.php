<?php
// Cek apakah user sudah login — session_start() wajib dipanggil paling atas
session_start();

// Jika belum login, redirect ke halaman auth menggunakan header() di sisi server
if (!isset($_SESSION['current_user'])) {
    header('Location: ../../auth.php');
    exit;
}

// Variabel notifikasi untuk ditampilkan ke user
$notification      = '';
$notification_type = '';

// Path file JSON sebagai pengganti database
// __DIR__ agar path selalu relatif terhadap lokasi file ini, bukan server
$SISWA_FILE = __DIR__ . '/siswa.json';

// Membersihkan input dari spasi berlebih, backslash, dan karakter berbahaya (XSS)
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Membaca data siswa dari file JSON, kembalikan $default jika file belum ada
function readSiswa($file, $default) {
    if (!file_exists($file)) return $default;
    return json_decode(file_get_contents($file), true) ?: $default;
}

// Menyimpan array siswa ke file JSON
// array_values() memastikan index array berurutan setelah ada penghapusan
function saveSiswa($file, $data) {
    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));
}

// require dipakai karena data dummy adalah komponen wajib —
// jika file tidak ditemukan, program langsung berhenti (fatal error)
require 'data_siswa.php';

// Gunakan file JSON jika sudah ada (data real), fallback ke $students dari data_siswa.php
$students = readSiswa($SISWA_FILE, $students);

// ===== HANDLE POST: CREATE & UPDATE =====
// POST digunakan karena operasi ini mengubah data di server
// Data dikirim melalui HTTP Body — tidak tampil di URL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'simpan') {

        // Ambil dan bersihkan semua input dari form
        $nisn         = test_input($_POST['nisn']         ?? '');
        $nama         = test_input($_POST['namaLengkap']  ?? '');
        $kelas        = test_input($_POST['kelas']        ?? '');
        $jenisKelamin = test_input($_POST['jenisKelamin'] ?? '');
        $email        = test_input($_POST['email']        ?? '');
        $noTelepon    = test_input($_POST['noTelepon']    ?? '');
        $alamat       = test_input($_POST['alamat']       ?? '');
        $status       = test_input($_POST['status']       ?? 'Aktif');
        $editId       = (int) ($_POST['editId']           ?? 0);

        // Validasi field wajib diisi
        if (empty($nisn)) {
            $notification = 'NISN wajib diisi';
            $notification_type = 'error';

        } elseif (empty($nama)) {
            $notification = 'Nama lengkap wajib diisi';
            $notification_type = 'error';

        // preg_match: nama hanya boleh huruf dan spasi
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $nama)) {
            $notification = 'Nama hanya boleh berisi huruf dan spasi';
            $notification_type = 'error';

        } elseif (empty($kelas)) {
            $notification = 'Kelas wajib dipilih';
            $notification_type = 'error';

        } elseif (empty($jenisKelamin)) {
            $notification = 'Jenis kelamin wajib dipilih';
            $notification_type = 'error';

        // filter_var: validasi format email jika diisi
        } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $notification = 'Format email tidak valid';
            $notification_type = 'error';

        } else {

            if ($editId > 0) {
                // UPDATE: cari siswa berdasarkan id lalu perbarui datanya
                foreach ($students as &$s) {
                    if ((int)$s['id'] === $editId) {
                        $s['nisn']         = $nisn;
                        $s['nama']         = $nama;
                        $s['kelas']        = $kelas;
                        $s['jenisKelamin'] = $jenisKelamin;
                        $s['email']        = $email;
                        $s['noTelepon']    = $noTelepon;
                        $s['alamat']       = $alamat;
                        $s['status']       = $status;
                        break;
                    }
                }
                unset($s);
                saveSiswa($SISWA_FILE, $students);

                // PRG Pattern: setelah POST berhasil, redirect ke GET agar
                // refresh browser tidak submit ulang form dan duplikasi data
                header('Location: index.php?status=diperbarui');
                exit;

            } else {
                // CREATE: buat siswa baru dengan id otomatis (max id + 1)
                $maxId = 0;
                foreach ($students as $s) {
                    if ((int)$s['id'] > $maxId) $maxId = (int)$s['id'];
                }
                $students[] = [
                    'id'           => $maxId + 1,
                    'nisn'         => $nisn,
                    'nama'         => $nama,
                    'kelas'        => $kelas,
                    'jenisKelamin' => $jenisKelamin,
                    'email'        => $email,
                    'noTelepon'    => $noTelepon,
                    'alamat'       => $alamat,
                    'status'       => $status,
                ];
                saveSiswa($SISWA_FILE, $students);

                // PRG Pattern: redirect ke GET setelah CREATE
                header('Location: index.php?status=ditambahkan');
                exit;
            }
        }
    }
}

// ===== HANDLE GET: HAPUS =====
// Hapus menggunakan GET karena hanya membawa satu parameter (id) via URL
// SweetAlert di sisi client meminta konfirmasi sebelum request ini dikirim
if (isset($_GET['hapus'])) {
    $hapusId  = (int) $_GET['hapus'];
    // array_filter: simpan semua siswa KECUALI yang idnya cocok dengan $hapusId
    $students = array_filter($students, fn($s) => (int)$s['id'] !== $hapusId);
    saveSiswa($SISWA_FILE, $students);

    // Redirect setelah hapus — PRG Pattern
    header('Location: index.php?status=dihapus');
    exit;
}

// ===== NOTIFIKASI SETELAH REDIRECT (GET) =====
// Baca parameter ?status= dari URL yang dikirim setelah operasi POST/hapus
// GET dipakai di sini karena pesan status tidak sensitif dan boleh terlihat di URL
$pesanStatus = [
    'ditambahkan' => 'Siswa baru berhasil ditambahkan!',
    'diperbarui'  => 'Data siswa berhasil diperbarui!',
    'dihapus'     => 'Data siswa berhasil dihapus!',
];
if (isset($_GET['status']) && isset($pesanStatus[$_GET['status']])) {
    $notification      = $pesanStatus[$_GET['status']];
    $notification_type = $_GET['status'] === 'dihapus' ? 'info' : 'success';
}

// ===== READ: Filter & Search via GET =====
// Filter dan pencarian menggunakan GET karena parameter tidak sensitif
// dan bisa di-bookmark atau dibagikan linknya
$filterKelas  = test_input($_GET['kelas']         ?? '');
$filterStatus = test_input($_GET['status_filter'] ?? '');
$keyword      = test_input($_GET['q']             ?? '');

// array_filter: saring data siswa berdasarkan kelas, status, dan keyword pencarian
$filtered = array_filter($students, function($s) use ($filterKelas, $filterStatus, $keyword) {
    $kelasMatch  = empty($filterKelas)  || $s['kelas']  === $filterKelas;
    $statusMatch = empty($filterStatus) || $s['status'] === $filterStatus;
    // stripos: pencarian case-insensitive (tidak peduli huruf besar/kecil)
    $keyMatch    = empty($keyword)
        || stripos($s['nama'],  $keyword) !== false
        || stripos($s['nisn'],  $keyword) !== false
        || stripos($s['kelas'], $keyword) !== false;
    return $kelasMatch && $statusMatch && $keyMatch;
});

// Jika ada ?edit=id di URL, ambil data siswa tersebut untuk mengisi modal edit
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    foreach ($students as $s) {
        if ((int)$s['id'] === $editId) { $editData = $s; break; }
    }
}
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
    /* Override: rule "form select { width: 100% }" dari style.css ikut mempengaruhi
       select di toolbar karena berada dalam <form>. Di sini kita kembalikan ukurannya. */
    #filterForm .toolbar input,
    #filterForm .toolbar select { width: auto; }
    #filterForm .toolbar input[name="q"] { flex: 1; }
  </style>
</head>
<body>

<?php if (!empty($notification)): ?>
<!-- Notifikasi muncul setelah redirect GET dari operasi POST/hapus, hilang otomatis via JS -->
<div id="phpNotification" style="
    position:fixed; top:20px; right:20px;
    background:<?= $notification_type === 'error' ? '#ef4444' : ($notification_type === 'info' ? '#3b82f6' : '#10b981') ?>;
    color:white; padding:14px 18px; border-radius:10px;
    font-size:13px; z-index:9999; font-family:inherit;
">
    <?= htmlspecialchars($notification) ?>
</div>
<?php endif; ?>

<!-- SIDEBAR -->
<div class="sidebar">
  <h2 class="logo">InkluSkill</h2>

  <nav class="menu">
    <a href="../dashboard/dashboardSekolah.php"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a class="active"><i class="ri-user-3-line"></i>Data Siswa</a>
    <a href=#><i class="ri-file-edit-line"></i>Pendaftaran Pelatihan</a>
    <a href=#><i class="ri-bar-chart-line"></i>Laporan</a>
    <a href=#><i class="ri-archive-line"></i>Rekap Siswa</a>
    <a href=#><i class="ri-user-settings-line"></i>Profil</a>
  </nav>

  <!-- Tombol logout pakai <button> bukan <a> agar styling CSS class .logout terapply dengan benar -->
  <button class="logout" onclick="window.location.href='../../auth/auth.php?logout=1'">
    <i class="ri-logout-box-line"></i> Logout
  </button>
</div>

<!-- CONTENT -->
<div class="content">

  <!-- TOPBAR -->
  <div class="topbar">
    <h1>Manajemen Data Siswa</h1>
    <div class="user-info">
      <span>SLB G YBMU Baleendah</span>
      <img src="../../assets/fotonovi.webp">
    </div>
  </div>

  <div class="dashboard-body">

    <div class="header">
      <h1>Daftar Siswa</h1>
      <p>Kelola data siswa secara terstruktur</p>
    </div>

    <!-- Toolbar filter menggunakan method GET agar hasil filter bisa di-bookmark/dibagikan via URL -->
    <form method="GET" action="index.php" id="filterForm">
      <div class="toolbar">
        <input type="text" name="q"
          placeholder="Cari nama, NISN, atau kelas..."
          value="<?= htmlspecialchars($keyword) ?>"
          onkeyup="this.form.submit()">

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

    <!-- Tabel READ: menampilkan data $filtered hasil filter GET -->
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>NISN</th><th>Nama</th><th>Kelas</th>
            <th>JK</th><th>Email</th><th>Status</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filtered as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['nisn']) ?></td>
            <td><?= htmlspecialchars($s['nama']) ?></td>
            <td><?= htmlspecialchars($s['kelas']) ?></td>
            <td><?= htmlspecialchars($s['jenisKelamin']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td>
              <span class="status <?= $s['status'] === 'Aktif' ? 'active' : 'inactive' ?>">
                <?= htmlspecialchars($s['status']) ?>
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <!-- Data siswa dikirim ke JS via parameter fungsi agar modal bisa diisi
                     tanpa request tambahan ke server -->
                <button class="btn-icon btn-edit"
                  onclick="openEditModal(<?= (int)$s['id'] ?>, '<?= htmlspecialchars($s['nisn']) ?>', '<?= htmlspecialchars($s['nama']) ?>', '<?= $s['kelas'] ?>', '<?= $s['jenisKelamin'] ?>', '<?= htmlspecialchars($s['email']) ?>', '<?= htmlspecialchars($s['noTelepon']) ?>', '<?= htmlspecialchars($s['alamat']) ?>', '<?= $s['status'] ?>')">
                  Edit
                </button>
                <!-- Hapus via GET ?hapus=id, SweetAlert meminta konfirmasi sebelum request dikirim -->
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

<!-- MODAL: Form tambah/edit siswa dikirim via POST karena mengubah data di server -->
<div class="modal" id="studentModal">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="modalTitle">Tambah Siswa</h3>
      <button class="close-btn" onclick="closeModal()">×</button>
    </div>

    <form id="studentForm" method="POST" action="index.php">
      <!-- action=simpan memberitahu PHP operasi mana yang dijalankan -->
      <input type="hidden" name="action" value="simpan">
      <!-- editId=0 berarti CREATE, editId>0 berarti UPDATE -->
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
          <option>Laki-laki</option>
          <option>Perempuan</option>
        </select>

        <input id="email"     name="email"     placeholder="Email">
        <input id="noTelepon" name="noTelepon" placeholder="No Telepon">
      </div>

      <textarea id="alamat" name="alamat" placeholder="Alamat"></textarea>

      <select id="status" name="status">
        <option>Aktif</option>
        <option>Tidak Aktif</option>
      </select>

      <div class="form-actions">
        <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Buka modal untuk tambah siswa baru
  function openModal() {
    document.getElementById('studentModal').classList.add('show');
    document.getElementById('modalTitle').textContent = 'Tambah Siswa Baru';
    document.getElementById('studentForm').reset();
    document.getElementById('editId').value = '0';
  }

  // Buka modal edit dan isi field dengan data siswa yang dipilih
  // Data diterima sebagai parameter dari PHP echo di tombol Edit
  function openEditModal(id, nisn, nama, kelas, jk, email, telp, alamat, status) {
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
  }

  // Tutup modal dan reset semua field form
  function closeModal() {
    document.getElementById('studentModal').classList.remove('show');
    document.getElementById('studentForm').reset();
    document.getElementById('editId').value = '0';
  }

  // Tampilkan dialog konfirmasi SweetAlert sebelum request hapus dikirim ke server
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
      // Baru kirim request GET ?hapus=id setelah user mengkonfirmasi
      if (result.isConfirmed) window.location.href = 'index.php?hapus=' + id;
    });
  }

  // Notifikasi dari PHP hilang otomatis setelah 3.5 detik
  const notif = document.getElementById('phpNotification');
  if (notif) setTimeout(() => notif.remove(), 3500);
</script>

</body>
</html>