<?php
// VIEW: sekolah/siswa/index.php
// Halaman manajemen data siswa (CRUD).
// Menampilkan: tabel daftar siswa, form filter/pencarian,
// modal form tambah/edit siswa, dan konfirmasi hapus (SweetAlert2).
//
// Variabel yang diterima dari Siswa Controller (via array $data):
//   $listSiswa         : Array of arrays berisi data semua siswa (hasil query dari model)
//   $notification      : Pesan notifikasi sukses/error (dari flashdata CI3)
//   $notification_type : Tipe notifikasi: 'success' / 'error'
//   $filterKelas       : Nilai filter kelas yang aktif ('X', 'XI', 'XII', atau '')
//   $filterStatus      : Nilai filter status yang aktif ('Aktif', 'Tidak Aktif', atau '')
//   $keyword           : Keyword pencarian yang aktif (string, bisa kosong)
//   $current_user      : Array data user yang sedang login (dari session)
//
// CATATAN PERBAIKAN CI3:
// 1. Nama variabel loop: $students → $listSiswa (sesuai nama dari controller)
// 2. Akses session: $_SESSION['current_user'] → $current_user (dikirim dari controller)
// 3. Filter name: name="q" → name="keyword", name="status_filter" → name="status"
//    (harus cocok persis dengan $this->input->get() di controller)
// 4. Path foto: base_url('uploads/siswa/') bukan base_url('assets/fotonovi.webp')
// 5. img tag: src=src="..." diperbaiki menjadi src="..."
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Siswa - InkluSkill</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Remix Icon: library ikon dari CDN -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
  <!-- CSS halaman siswa: base_url() dari CI3 url helper -->
  <link rel="stylesheet" href="<?= base_url('assets/css/siswa.css') ?>">
  <!-- SweetAlert2: library popup konfirmasi hapus yang lebih elegan dari confirm() bawaan browser -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Override lebar input/select di toolbar filter agar tidak mengambil full width */
    #filterForm .toolbar input,
    #filterForm .toolbar select { width: auto; }
    /* Input keyword menggunakan flex:1 agar mengisi sisa ruang yang tersedia */
    #filterForm .toolbar input[name="keyword"] { flex: 1; }
  </style>
</head>
<body>

<?php if (!empty($notification)): ?>
<!-- Notifikasi flash: ditampilkan jika ada pesan dari flashdata CI3 setelah redirect -->
<!-- Warna berubah sesuai tipe: merah=error, biru=info, hijau=sukses -->
<div id="phpNotification" style="
    position:fixed; top:20px; right:20px;
    background:<?= $notification_type === 'error' ? '#ef4444' : ($notification_type === 'info' ? '#3b82f6' : '#10b981') ?>;
    color:white; padding:14px 18px; border-radius:10px; font-size:13px; z-index:9999; font-family:inherit;">
    <?= htmlspecialchars($notification) ?>
</div>
<?php endif; ?>

<!-- SIDEBAR NAVIGASI                                       -->
<div class="sidebar">
  <h2 class="logo">InkluSkill</h2>
  <nav class="menu">
    <!-- base_url('dashboard') mengarahkan ke Dashboard::index() -->
    <a href="<?= base_url('dashboard') ?>"><i class="ri-dashboard-line"></i>Dashboard</a>
    <!-- Class "active" menandai menu ini sebagai halaman yang sedang aktif -->
    <a class="active"><i class="ri-user-3-line"></i>Data Siswa</a>
    <a href="#"><i class="ri-file-edit-line"></i>Pendaftaran Pelatihan</a>
    <a href="#"><i class="ri-bar-chart-line"></i>Laporan</a>
    <a href="#"><i class="ri-archive-line"></i>Rekap Siswa</a>
    <a href="#"><i class="ri-user-settings-line"></i>Profil</a>
  </nav>
  <!-- Tombol logout: redirect ke Auth::logout() yang destroy session -->
  <button class="logout" onclick="window.location.href='<?= base_url('auth/logout') ?>'">
    <i class="ri-logout-box-line"></i> Logout
  </button>
</div>

<!-- AREA KONTEN UTAMA                                      -->
<div class="content">
  <div class="topbar">
    <h1>Manajemen Data Siswa</h1>
    <div class="user-info">
      <!-- PERBAIKAN: gunakan $current_user (dikirim dari controller) bukan $_SESSION langsung -->
      <!-- ?? '' sebagai fallback jika key 'name' tidak ada -->
      <span><?= htmlspecialchars($current_user['name'] ?? '') ?></span>
      <img src="<?= base_url('assets/img/fotonovi.webp') ?>">
    </div>
  </div>

  <div class="dashboard-body">
    <div class="header">
      <h1>Daftar Siswa</h1>
      <p>Kelola data siswa secara terstruktur</p>
    </div>

    <!-- FORM FILTER DAN PENCARIAN                              -->
    <!-- method GET: parameter dikirim via URL query string     -->
    <!-- Contoh: /siswa?keyword=budi&kelas=X&status=Aktif       -->
    <!-- PERBAIKAN: name="keyword" dan name="status" harus cocok dengan
         $this->input->get('keyword') dan $this->input->get('status') di Siswa Controller -->
    <form method="GET" action="<?= base_url('siswa') ?>" id="filterForm">
      <div class="toolbar">
        <!-- Input pencarian: auto-submit saat user mengetik (onkeyup) -->
        <!-- value diisi dari $keyword untuk mempertahankan isian setelah submit -->
        <input type="text" name="keyword" placeholder="Cari nama, NISN, atau kelas..."
               value="<?= htmlspecialchars($keyword) ?>" onkeyup="this.form.submit()">

        <!-- Dropdown filter kelas: auto-submit saat pilihan berubah -->
        <!-- 'selected' ditambahkan pada option yang cocok dengan $filterKelas -->
        <select name="kelas" onchange="this.form.submit()">
          <option value="">Semua Kelas</option>
          <option value="X"   <?= $filterKelas === 'X'   ? 'selected' : '' ?>>Kelas X</option>
          <option value="XI"  <?= $filterKelas === 'XI'  ? 'selected' : '' ?>>Kelas XI</option>
          <option value="XII" <?= $filterKelas === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
        </select>

        <!-- Dropdown filter status: auto-submit saat pilihan berubah -->
        <select name="status" onchange="this.form.submit()">
          <option value="">Semua Status</option>
          <option value="Aktif"       <?= $filterStatus === 'Aktif'       ? 'selected' : '' ?>>Aktif</option>
          <option value="Tidak Aktif" <?= $filterStatus === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>

        <!-- Tombol buka modal tambah siswa baru -->
        <button type="button" class="btn-primary" onclick="openModal()">
          <i class="ri-add-line"></i> Tambah Siswa
        </button>
      </div>
    </form>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Foto</th><th>NISN</th><th>Nama</th><th>Kelas</th>
            <th>JK</th><th>Email</th><th>Status</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listSiswa as $s): ?>
          <tr>
            <td>
              <?php if (!empty($s['foto'])): ?>
                <!-- htmlspecialchars() pada nama file untuk mencegah XSS -->
                <img src="<?= base_url('uploads/siswa/' . htmlspecialchars($s['foto'])) ?>"
                     style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
              <?php else: ?>
                <!-- Fallback icon jika siswa tidak memiliki foto -->
                <i class="ri-user-3-line" style="font-size:24px;color:#bbb;"></i>
              <?php endif; ?>
            </td>
            <!-- htmlspecialchars() pada setiap data untuk mencegah XSS di tampilan tabel -->
            <td><?= htmlspecialchars($s['nisn']) ?></td>
            <td><?= htmlspecialchars($s['nama']) ?></td>
            <td><?= htmlspecialchars($s['kelas']) ?></td>
            <td><?= htmlspecialchars($s['jenis_kelamin']) ?></td>
            <td><?= htmlspecialchars($s['email'] ?? '') ?></td>
            <td>
              <!-- Badge status: class CSS berbeda untuk 'Aktif' (hijau) vs lainnya (merah/abu) -->
              <span class="status <?= $s['status'] === 'Aktif' ? 'active' : 'inactive' ?>">
                <?= htmlspecialchars($s['status']) ?>
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <!-- Tombol Edit: memanggil openEditModal() dengan semua data siswa sebagai argumen -->
                <!-- ENT_QUOTES pada htmlspecialchars() penting agar karakter ' dan " di dalam
                     atribut onclick tidak merusak sintaks JavaScript -->
                <button class="btn-icon btn-edit"
                  onclick="openEditModal(
                    <?= (int)$s['id'] ?>,
                    '<?= htmlspecialchars($s['nisn'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($s['nama'], ENT_QUOTES) ?>',
                    '<?= $s['kelas'] ?>',
                    '<?= $s['jenis_kelamin'] ?>',
                    '<?= htmlspecialchars($s['email'] ?? '', ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($s['no_telepon'] ?? '', ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($s['alamat'] ?? '', ENT_QUOTES) ?>',
                    '<?= $s['status'] ?>',
                    '<?= htmlspecialchars($s['foto'] ?? '', ENT_QUOTES) ?>'
                  )">
                  Edit
                </button>
                <!-- Tombol Hapus: memunculkan konfirmasi SweetAlert2 sebelum menghapus -->
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

<!-- MODAL FORM TAMBAH / EDIT SISWA                        -->
<!-- Satu modal digunakan untuk dua fungsi:                -->
<!--   - Tambah: editId = 0, judul "Tambah Siswa Baru"    -->
<!--   - Edit  : editId = ID siswa, judul "Edit Data Siswa"-->
<div class="modal" id="studentModal">
  <div class="modal-content">
    <div class="modal-header">
      <!-- Judul modal berubah via JavaScript (openModal / openEditModal) -->
      <h3 id="modalTitle">Tambah Siswa</h3>
      <button class="close-btn" onclick="closeModal()">×</button>
    </div>
    <!-- Form POST ke /siswa/simpan (Siswa::simpan()) dengan enctype untuk upload foto -->
    <form id="studentForm" method="POST" action="<?= base_url('siswa/simpan') ?>" enctype="multipart/form-data">
      <!-- Field hidden 'action': divalidasi di controller agar hanya request dari form ini yang diproses -->
      <input type="hidden" name="action" value="simpan">
      <!-- Field hidden 'editId': 0 = mode tambah, > 0 = mode edit (ID siswa yang diedit) -->
      <input type="hidden" name="editId" id="editId" value="0">
      <div class="form-grid">
        <!-- name attribute setiap input harus cocok dengan $this->input->post() di Siswa Controller -->
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
      <!-- Upload foto (opsional): name="foto" harus cocok dengan $_FILES['foto'] di controller -->
      <div style="margin-top:12px;">
        <label style="font-size:13px;font-weight:500;display:block;margin-bottom:6px;">
          Foto Siswa <span style="color:#999;font-weight:400;">(opsional, JPG/PNG)</span>
        </label>
        <!-- Preview foto: ditampilkan saat modal edit dibuka (foto lama) atau file baru dipilih -->
        <div id="fotoPreviewWrap" style="display:none;margin-bottom:8px;">
          <img id="fotoPreview" src="" alt="Foto saat ini"
               style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #e5e7eb;">
        </div>
        <!-- Input file: onchange memanggil previewFoto() untuk live preview sebelum upload -->
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
  // Base URL CI3 yang dikirim ke JavaScript untuk menyusun path foto yang benar
  // base_url() dari PHP → string JavaScript, digunakan saat membangun URL foto di openEditModal()
  const baseUrl = '<?= base_url() ?>';

  // openModal(): Buka modal dalam mode TAMBAH siswa baru
  // Reset semua field form dan set editId ke 0
  function openModal() {
    document.getElementById('studentModal').classList.add('show');
    document.getElementById('modalTitle').textContent = 'Tambah Siswa Baru';
    document.getElementById('studentForm').reset();
    document.getElementById('editId').value = '0';
    document.getElementById('fotoPreviewWrap').style.display = 'none';
    document.getElementById('fotoPreview').src = '';
  }

  // openEditModal(): Buka modal dalam mode EDIT dengan data siswa yang dipilih
  // Dipanggil dari tombol Edit di setiap baris tabel dengan data siswa sebagai argumen
  function openEditModal(id, nisn, nama, kelas, jk, email, telp, alamat, status, foto) {
    document.getElementById('studentModal').classList.add('show');
    document.getElementById('modalTitle').textContent = 'Edit Data Siswa';
    // Set editId ke ID siswa → controller tahu ini adalah mode UPDATE, bukan INSERT
    document.getElementById('editId').value       = id;
    // Isi semua field form dengan data siswa yang akan diedit
    document.getElementById('nisn').value         = nisn;
    document.getElementById('namaLengkap').value  = nama;
    document.getElementById('kelas').value        = kelas;
    document.getElementById('jenisKelamin').value = jk;
    document.getElementById('email').value        = email;
    document.getElementById('noTelepon').value    = telp;
    document.getElementById('alamat').value       = alamat;
    document.getElementById('status').value       = status;
    // PERBAIKAN: gunakan baseUrl (dari PHP) untuk membangun path foto yang benar
    // Jika siswa punya foto, tampilkan preview-nya di modal edit
    if (foto && foto !== '') {
      document.getElementById('fotoPreview').src = baseUrl + 'uploads/siswa/' + foto;
      document.getElementById('fotoPreviewWrap').style.display = 'block';
    } else {
      document.getElementById('fotoPreviewWrap').style.display = 'none';
    }
  }

  // closeModal(): Tutup modal dan reset semua field
  function closeModal() {
    document.getElementById('studentModal').classList.remove('show');
    document.getElementById('studentForm').reset();
    document.getElementById('editId').value = '0';
    document.getElementById('fotoPreviewWrap').style.display = 'none';
    document.getElementById('fotoPreview').src = '';
  }

  // previewFoto(): Tampilkan preview gambar yang dipilih sebelum diupload
  // Menggunakan FileReader API untuk membaca file secara lokal (tanpa upload dulu)
  function previewFoto(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('fotoPreview').src = e.target.result; // Data URL gambar
        document.getElementById('fotoPreviewWrap').style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]); // Baca file sebagai Data URL
    }
  }

  // confirmHapus(): Tampilkan dialog konfirmasi SweetAlert2 sebelum menghapus siswa
  // Jika user klik "Ya, hapus!", browser diarahkan ke /siswa/hapus/{id} (Siswa::hapus($id))
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
      // Hanya redirect jika user mengkonfirmasi (bukan klik Batal atau tutup dialog)
      if (result.isConfirmed) window.location.href = "<?= base_url('siswa/hapus/') ?>" + id;
    });
  }

  // Auto-dismiss notifikasi PHP setelah 3.5 detik
  const notif = document.getElementById('phpNotification');
  if (notif) setTimeout(() => notif.remove(), 3500);
</script>
</body>
</html>
