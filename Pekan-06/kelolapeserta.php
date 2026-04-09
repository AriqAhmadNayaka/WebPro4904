<?php

declare(strict_types=1);

// Memuat autoloader class OOP aplikasi.
require_once __DIR__ . "/app/bootstrap.php";

use App\Config\Database;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\ParticipantPhotoService;
use App\Services\ParticipantService;
use App\Services\RememberMeService;
use App\Support\Response;
use App\Support\SessionManager;

// Menyiapkan session manager untuk halaman peserta.
$session = new SessionManager();
// Memastikan session aktif.
$session->start();
// Menyiapkan repository untuk akses tabel user.
$userRepository = new UserRepository(Database::getConnection());
// Menyiapkan service auth untuk proteksi login.
$authService = new AuthService($session, $userRepository, new RememberMeService($userRepository));
// Menyiapkan service peserta untuk proses tambah, edit, hapus, dan tampil data.
$participantService = new ParticipantService(
    $userRepository,
    new ParticipantPhotoService(__DIR__ . DIRECTORY_SEPARATOR . "uploads")
);

// Pastikan halaman ini hanya bisa diakses oleh user yang sudah login.
$authService->requireLogin();

// Ambil flash message dari session lalu hapus setelah dibaca.
$pesan = (string)$session->pull("flash_pesan", "");

// Jalankan proses form saat request POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Serahkan data form ke service peserta.
    [$success, $message, $shouldRedirect] = $participantService->handleForm($_POST, $_FILES);

    // Jika proses berhasil, simpan pesan ke session lalu redirect.
    if ($success && $shouldRedirect) {
        $session->set("flash_pesan", $message);
        Response::redirect("kelolapeserta.php");
    }

    // Jika gagal, tampilkan pesan error di halaman ini.
    $pesan = $message;
}

// Siapkan array data peserta untuk ditampilkan di tabel.
$daftarPeserta = [];
// Jika struktur tabel siap, ambil seluruh data peserta.
if ($participantService->canManageParticipants()) {
    $daftarPeserta = $participantService->getParticipants();
} elseif ($pesan === "") {
    // Tampilkan pesan jika struktur tabel belum lengkap.
    $pesan = "Tabel user belum siap ditampilkan. Pastikan ada kolom id, nama/username, umur/usia, jenis_kelamin, pelatihan.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Menentukan karakter encoding halaman. -->
    <meta charset="UTF-8" />
    <!-- Menyesuaikan tampilan agar responsif di mobile. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Judul halaman browser. -->
    <title>Kelola Peserta</title>
    <!-- Memanggil stylesheet halaman peserta. -->
    <link rel="stylesheet" href="kelolapeserta.css" />
    <!-- Memanggil library icon Remix Icon. -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
</head>
<body>
    <!-- Jika ada pesan dari backend, tampilkan alert. -->
    <?php if ($pesan !== ""): ?>
        <!-- Menampilkan alert JavaScript. -->
        <script>alert('<?php echo addslashes($pesan); ?>');</script>
    <?php endif; ?>

    <!-- Sidebar menu aplikasi. -->
    <div class="sidebar">
        <!-- Judul sidebar. -->
        <h2 class="sidebar-title">InkluSkill</h2>
        <!-- Link dashboard. -->
        <a href="dinas.html"><i class="ri-dashboard-line"></i> Dashboard</a>
        <!-- Link halaman aktif kelola peserta. -->
        <a class="active" href="#"><i class="ri-user-3-line"></i> Kelola Peserta</a>
        <!-- Link halaman pelatihan. -->
        <a href="peltihan.html"><i class="ri-calendar-check-line"></i> Pelatihan & Jadwal</a>
        <!-- Link halaman kehadiran. -->
        <a href="kehadiran.html"><i class="ri-time-line"></i> Kehadiran</a>
        <!-- Link halaman statistik. -->
        <a href="statistik.html"><i class="ri-bar-chart-box-line"></i> Statistik</a>
        <!-- Link halaman laporan. -->
        <a href="laporan.html"><i class="ri-file-list-3-line"></i> Laporan</a>
        <!-- Tombol logout. -->
        <a class="logout-btn" href="logout.php"><i class="ri-logout-box-line"></i> Logout</a>
    </div>

    <!-- Konten utama halaman. -->
    <div class="main-content">
        <!-- Bagian header konten. -->
        <header>
            <!-- Judul halaman konten. -->
            <h1 class="title-page"><i class="ri-user-3-line"></i> Kelola Peserta</h1>
            <!-- Profil pengguna login. -->
            <div class="profile">
                <!-- Gambar admin. -->
                <img src="../assets/admin.jpg" alt="">
                <!-- Menampilkan nama user dari session. -->
                <span><?php echo htmlspecialchars($authService->username() ?: "Dinas Sosial"); ?></span>
            </div>
        </header>

        <!-- Baris aksi pencarian dan tombol tambah. -->
        <div class="action-row">
            <!-- Input pencarian peserta. -->
            <input type="text" id="searchPeserta" placeholder="Cari peserta...">
            <!-- Tombol membuka modal tambah peserta. -->
            <button class="add-btn" id="openModal" type="button"><i class="ri-add-circle-line"></i> Tambah Peserta</button>
        </div>

        <!-- Card tabel peserta. -->
        <div class="table-card">
            <!-- Tabel data peserta. -->
            <table>
                <!-- Bagian kepala tabel. -->
                <thead>
                    <!-- Baris judul kolom. -->
                    <tr>
                        <!-- Kolom nomor. -->
                        <th>No</th>
                        <!-- Kolom nama peserta. -->
                        <th>Nama Peserta</th>
                        <!-- Kolom usia peserta. -->
                        <th>Usia</th>
                        <!-- Kolom jenis kelamin. -->
                        <th>Jenis Kelamin</th>
                        <!-- Kolom pelatihan. -->
                        <th>Pelatihan Diikuti</th>
                        <!-- Kolom foto. -->
                        <th>Foto</th>
                        <!-- Kolom aksi. -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <!-- Bagian body tabel. -->
                <tbody id="pesertaTable">
                    <!-- Jika data peserta tersedia. -->
                    <?php if (!empty($daftarPeserta)): ?>
                        <!-- Inisialisasi nomor urut. -->
                        <?php $no = 1; ?>
                        <!-- Loop seluruh data peserta. -->
                        <?php foreach ($daftarPeserta as $data): ?>
                            <!-- Susun URL foto jika ada file foto. -->
                            <?php $fotoUrl = !empty($data["foto"]) ? "uploads/" . rawurlencode($data["foto"]) : ""; ?>
                            <!-- Satu baris data peserta. -->
                            <tr>
                                <!-- Menampilkan nomor urut. -->
                                <td><?php echo $no++; ?></td>
                                <!-- Menampilkan nama peserta. -->
                                <td><?php echo htmlspecialchars($data["nama"]); ?></td>
                                <!-- Menampilkan umur peserta. -->
                                <td><?php echo htmlspecialchars((string)$data["umur"]); ?></td>
                                <!-- Menampilkan jenis kelamin peserta. -->
                                <td><?php echo htmlspecialchars($data["jenis_kelamin"]); ?></td>
                                <!-- Menampilkan pelatihan peserta. -->
                                <td><?php echo htmlspecialchars($data["pelatihan"]); ?></td>
                                <!-- Menampilkan kolom foto. -->
                                <td>
                                    <!-- Jika foto tersedia. -->
                                    <?php if ($fotoUrl !== ""): ?>
                                        <!-- Tampilkan gambar foto peserta. -->
                                        <img src="<?php echo htmlspecialchars($fotoUrl); ?>" alt="Foto Peserta" style="width:72px; height:72px; object-fit:cover; border-radius:10px;">
                                    <?php else: ?>
                                        <!-- Tampilkan teks jika tidak ada foto. -->
                                        Tidak ada foto
                                    <?php endif; ?>
                                </td>
                                <!-- Kolom aksi edit dan hapus. -->
                                <td>
                                    <!-- Tombol edit data peserta. -->
                                    <button
                                        class="edit"
                                        type="button"
                                        data-id="<?php echo (int)$data["id"]; ?>"
                                        data-nama="<?php echo htmlspecialchars($data["nama"], ENT_QUOTES); ?>"
                                        data-umur="<?php echo htmlspecialchars((string)$data["umur"], ENT_QUOTES); ?>"
                                        data-jk="<?php echo htmlspecialchars($data["jenis_kelamin"], ENT_QUOTES); ?>"
                                        data-pelatihan="<?php echo htmlspecialchars($data["pelatihan"], ENT_QUOTES); ?>"
                                        data-foto="<?php echo htmlspecialchars($data["foto"] ?? "", ENT_QUOTES); ?>"
                                        onclick="editData(this)"
                                    >Edit</button>
                                    <!-- Form hapus data peserta. -->
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus peserta ini?');">
                                        <!-- Menandai aksi hapus. -->
                                        <input type="hidden" name="aksi" value="hapus">
                                        <!-- Menyimpan id peserta. -->
                                        <input type="hidden" name="id" value="<?php echo (int)$data["id"]; ?>">
                                        <!-- Menyimpan nama file foto lama. -->
                                        <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($data["foto"] ?? "", ENT_QUOTES); ?>">
                                        <!-- Tombol submit hapus. -->
                                        <button class="delete" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Jika data kosong, tampilkan pesan data belum ada. -->
                        <tr><td colspan="7">Data belum ada</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal form tambah dan edit peserta. -->
    <div class="modal" id="modalForm">
        <!-- Kontainer isi modal. -->
        <div class="modal-content">
            <!-- Judul modal. -->
            <h3 id="modalTitle">Tambah Peserta</h3>
            <!-- Form tambah/edit peserta. -->
            <form action="" method="POST" enctype="multipart/form-data" id="pesertaForm">
                <!-- Menyimpan jenis aksi tambah atau edit. -->
                <input type="hidden" name="aksi" id="aksiInput" value="tambah">
                <!-- Menyimpan id peserta saat edit. -->
                <input type="hidden" name="id" id="idInput" value="">
                <!-- Menyimpan nama file foto lama saat edit. -->
                <input type="hidden" name="foto_lama" id="fotoLamaInput" value="">

                <!-- Label nama peserta. -->
                <label>Nama Peserta</label>
                <!-- Input nama peserta. -->
                <input type="text" name="nama" id="namaInput" required>

                <!-- Label usia peserta. -->
                <label>Usia</label>
                <!-- Input usia peserta. -->
                <input type="number" name="umur" id="usiaInput" required>

                <!-- Label jenis kelamin peserta. -->
                <label>Jenis Kelamin</label>
                <!-- Pilihan jenis kelamin. -->
                <select id="genderInput" name="jenis_kelamin" required>
                    <!-- Opsi laki-laki. -->
                    <option value="Laki-laki">Laki-laki</option>
                    <!-- Opsi perempuan. -->
                    <option value="Perempuan">Perempuan</option>
                </select>

                <!-- Label pelatihan peserta. -->
                <label>Pelatihan</label>
                <!-- Pilihan pelatihan. -->
                <select id="inputPelatihan" name="pelatihan" required>
                    <!-- Opsi pelatihan pertama. -->
                    <option value="Moshing">Moshing</option>
                    <!-- Opsi pelatihan kedua. -->
                    <option value="Icikiwir">Icikiwir</option>
                </select>

                <!-- Label upload foto. -->
                <label>Upload Foto</label>
                <!-- Input file untuk foto peserta. -->
                <input type="file" name="foto" id="fotoInput" accept=".jpg,.jpeg,.png,.gif,.webp">
                <!-- Info kecil tentang foto saat ini. -->
                <p id="fotoInfo" style="margin:8px 0 0; font-size:14px; color:#555;"></p>

                <!-- Tombol aksi modal. -->
                <div class="modal-buttons">
                    <!-- Tombol simpan form. -->
                    <button id="saveBtn" type="submit">Simpan</button>
                    <!-- Tombol batal menutup modal. -->
                    <button id="closeModal" type="button">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Memanggil file JavaScript halaman peserta. -->
    <script src="kelolapeserta.js"></script>
</body>
</html>
