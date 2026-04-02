<?php
// Memanggil file koneksi database.
include "koneksi.php";
// Memanggil helper session dan remember me.
include "session_helper.php";

// Mengembalikan session user jika cookie remember me masih valid.
restoreRememberedUser($koneksi);

// Jika user belum login, arahkan ke halaman login.
if (!isset($_SESSION["user"])) {
    // Redirect ke halaman auth.
    header("Location: auth.php");
    // Menghentikan eksekusi file setelah redirect.
    exit();
}

// Mengambil pesan flash dari session jika ada.
$pesan = $_SESSION["flash_pesan"] ?? "";
// Menghapus flash message agar tidak tampil berulang kali.
unset($_SESSION["flash_pesan"]);
// Menyediakan array kosong untuk menampung data peserta.
$daftarPeserta = [];

// Menentukan nama tabel yang digunakan.
$namaTabel = "user";
// Menyediakan array kosong untuk menampung daftar kolom tabel.
$kolomUser = [];
// Mengambil struktur kolom dari tabel user.
$cekKolom = mysqli_query($koneksi, "SHOW COLUMNS FROM $namaTabel");
// Jika query struktur kolom berhasil.
if ($cekKolom) {
    // Melakukan perulangan untuk setiap kolom yang ditemukan.
    while ($k = mysqli_fetch_assoc($cekKolom)) {
        // Menyimpan nama kolom ke array.
        $kolomUser[] = $k["Field"];
    }
}

// Jika kolom foto belum ada di tabel.
if (!in_array("foto", $kolomUser, true)) {
    // Tambahkan kolom foto ke tabel user.
    if (mysqli_query($koneksi, "ALTER TABLE $namaTabel ADD COLUMN foto VARCHAR(255) NULL")) {
        // Jika berhasil ditambah, masukkan juga ke daftar kolom.
        $kolomUser[] = "foto";
    }
}

// Menentukan kolom nama yang tersedia.
$kolomNama = in_array("nama", $kolomUser, true) ? "nama" : (in_array("username", $kolomUser, true) ? "username" : null);
// Menentukan kolom umur atau usia yang tersedia.
$kolomUmur = in_array("umur", $kolomUser, true) ? "umur" : (in_array("usia", $kolomUser, true) ? "usia" : null);
// Menentukan kolom jenis kelamin yang tersedia.
$kolomJK = in_array("jenis_kelamin", $kolomUser, true) ? "jenis_kelamin" : (in_array("jenisKelamin", $kolomUser, true) ? "jenisKelamin" : null);
// Menentukan kolom pelatihan.
$kolomPelatihan = in_array("pelatihan", $kolomUser, true) ? "pelatihan" : null;
// Menentukan kolom foto.
$kolomFoto = in_array("foto", $kolomUser, true) ? "foto" : null;
// Menentukan kolom id yang tersedia.
$kolomId = in_array("id", $kolomUser, true) ? "id" : (in_array("id_user", $kolomUser, true) ? "id_user" : null);

// Fungsi untuk upload foto peserta.
function uploadFotoPeserta(array $file, bool $wajib = true): array
{
    // Jika tidak ada file yang dipilih.
    if (($file["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        // Kembalikan error jika upload wajib, jika tidak wajib maka anggap aman.
        return $wajib ? [false, "Foto peserta wajib diupload.", ""] : [true, "", ""];
    }

    // Jika terjadi error saat upload file.
    if (($file["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        // Kembalikan status gagal upload.
        return [false, "Upload gambar gagal.", ""];
    }

    // Pastikan file yang diupload benar-benar gambar.
    if (@getimagesize($file["tmp_name"]) === false) {
        // Tolak file jika bukan gambar.
        return [false, "File yang diupload harus berupa gambar.", ""];
    }

    // Daftar ekstensi gambar yang diizinkan.
    $ekstensiValid = ["jpg", "jpeg", "png", "gif", "webp"];
    // Mengambil ekstensi file yang diupload.
    $ekstensi = strtolower(pathinfo($file["name"] ?? "", PATHINFO_EXTENSION));
    // Jika ekstensi tidak ada dalam daftar yang diizinkan.
    if (!in_array($ekstensi, $ekstensiValid, true)) {
        // Kembalikan pesan error format file.
        return [false, "Format gambar harus jpg, jpeg, png, gif, atau webp.", ""];
    }

    // Jika ukuran file lebih dari 2 MB.
    if (($file["size"] ?? 0) > 2 * 1024 * 1024) {
        // Kembalikan pesan error ukuran file.
        return [false, "Ukuran gambar maksimal 2 MB.", ""];
    }

    // Menentukan folder upload.
    $folderUpload = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
    // Jika folder belum ada, coba buat folder baru.
    if (!is_dir($folderUpload) && !mkdir($folderUpload, 0777, true)) {
        // Jika gagal membuat folder, tampilkan error.
        return [false, "Folder uploads tidak bisa dibuat.", ""];
    }

    // Membuat nama file unik agar tidak bentrok.
    $namaFileBaru = uniqid("peserta_", true) . "." . $ekstensi;
    // Menentukan lokasi akhir file.
    $targetFile = $folderUpload . DIRECTORY_SEPARATOR . $namaFileBaru;

    // Memindahkan file dari temp ke folder uploads.
    if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
        // Jika gagal dipindah, kembalikan error.
        return [false, "Gagal memindahkan gambar ke folder uploads.", ""];
    }

    // Jika semua berhasil, kembalikan status sukses dan nama file baru.
    return [true, "", $namaFileBaru];
}

// Fungsi untuk menghapus file foto lama.
function hapusFotoPeserta(string $namaFile): void
{
    // Jika nama file kosong, tidak perlu melakukan apa-apa.
    if ($namaFile === "") {
        // Keluar dari fungsi.
        return;
    }

    // Menentukan path file foto lama.
    $lokasiFoto = __DIR__ . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $namaFile;
    // Jika file benar-benar ada.
    if (file_exists($lokasiFoto)) {
        // Hapus file dari folder uploads.
        unlink($lokasiFoto);
    }
}

// Jika request yang masuk adalah POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil aksi dari form, default-nya tambah.
    $aksi = $_POST["aksi"] ?? "tambah";

    // Jika aksi adalah hapus dan kolom id tersedia.
    if ($aksi === "hapus" && $kolomId) {
        // Ambil id peserta yang akan dihapus.
        $idPeserta = (int)($_POST["id"] ?? 0);
        // Ambil nama file foto lama.
        $fotoLama = trim($_POST["foto_lama"] ?? "");

        // Siapkan query delete berdasarkan id.
        $stmt = mysqli_prepare($koneksi, "DELETE FROM $namaTabel WHERE $kolomId = ? LIMIT 1");
        // Jika statement berhasil dibuat.
        if ($stmt) {
            // Bind parameter id ke query.
            mysqli_stmt_bind_param($stmt, "i", $idPeserta);
            // Jika query delete berhasil dijalankan.
            if (mysqli_stmt_execute($stmt)) {
                // Hapus file foto lama dari folder uploads.
                hapusFotoPeserta($fotoLama);
                // Simpan pesan sukses ke session.
                $_SESSION["flash_pesan"] = "Data peserta berhasil dihapus.";
                // Tutup statement.
                mysqli_stmt_close($stmt);
                // Redirect ulang ke halaman yang sama.
                header("Location: kelolapeserta.php");
                // Hentikan proses lebih lanjut.
                exit();
            }
            // Simpan pesan error jika query gagal.
            $pesan = "Gagal menghapus data: " . mysqli_stmt_error($stmt);
            // Tutup statement.
            mysqli_stmt_close($stmt);
        } else {
            // Simpan pesan error jika prepare query gagal.
            $pesan = "Prepare query hapus gagal: " . mysqli_error($koneksi);
        }
    }

    // Jika aksi adalah tambah atau edit dan field nama tersedia.
    if (($aksi === "tambah" || $aksi === "edit") && isset($_POST["nama"])) {
        // Ambil id peserta dari form.
        $idPeserta = (int)($_POST["id"] ?? 0);
        // Ambil nama peserta.
        $nama = trim($_POST["nama"] ?? "");
        // Ambil umur peserta.
        $umur = trim($_POST["umur"] ?? "");
        // Ambil jenis kelamin peserta.
        $jenisKelamin = trim($_POST["jenis_kelamin"] ?? "");
        // Ambil pelatihan peserta.
        $pelatihan = trim($_POST["pelatihan"] ?? "");
        // Ambil nama file foto lama jika ada.
        $fotoLama = trim($_POST["foto_lama"] ?? "");

        // Validasi field wajib.
        if ($nama === "" || $umur === "" || $jenisKelamin === "" || $pelatihan === "") {
            // Pesan jika ada field kosong.
            $pesan = "Nama, umur, jenis kelamin, dan pelatihan wajib diisi.";
        } elseif (!$kolomNama || !$kolomUmur || !$kolomJK || !$kolomPelatihan || !$kolomId) {
            // Pesan jika struktur tabel belum lengkap.
            $pesan = "Struktur tabel user belum lengkap untuk kelola peserta.";
        } else {
            // Upload foto wajib jika sedang menambah data baru.
            $wajibUpload = $aksi === "tambah";
            // Jalankan proses upload foto.
            [$uploadBerhasil, $pesanUpload, $namaFileFoto] = uploadFotoPeserta($_FILES["foto"] ?? [], $wajibUpload);

            // Jika upload gagal.
            if (!$uploadBerhasil) {
                // Simpan pesan error upload.
                $pesan = $pesanUpload;
            } else {
                // Tentukan foto yang akan disimpan, pakai foto baru atau foto lama.
                $fotoUntukDisimpan = $namaFileFoto !== "" ? $namaFileFoto : $fotoLama;

                // Jika aksi tambah data.
                if ($aksi === "tambah") {
                    // Siapkan daftar kolom insert.
                    $kolomInsert = [$kolomNama, $kolomUmur, $kolomJK, $kolomPelatihan];
                    // Siapkan nilai insert.
                    $nilaiInsert = [$nama, (int)$umur, $jenisKelamin, $pelatihan];
                    // Siapkan tipe bind_param.
                    $tipeInsert = "siss";

                    // Jika kolom foto tersedia.
                    if ($kolomFoto) {
                        // Tambahkan kolom foto ke query.
                        $kolomInsert[] = $kolomFoto;
                        // Tambahkan nilai foto ke query.
                        $nilaiInsert[] = $fotoUntukDisimpan;
                        // Tambahkan tipe string.
                        $tipeInsert .= "s";
                    }

                    // Buat placeholder sesuai jumlah kolom.
                    $placeholders = implode(", ", array_fill(0, count($kolomInsert), "?"));
                    // Buat daftar kolom insert.
                    $listKolom = implode(", ", $kolomInsert);
                    // Susun query insert.
                    $sql = "INSERT INTO $namaTabel ($listKolom) VALUES ($placeholders)";
                    // Prepare query insert.
                    $stmt = mysqli_prepare($koneksi, $sql);

                    // Jika statement berhasil.
                    if ($stmt) {
                        // Siapkan parameter dinamis.
                        $param = [$stmt, $tipeInsert];
                        // Tambahkan seluruh nilai ke parameter.
                        foreach ($nilaiInsert as $key => $value) {
                            // Bind tiap nilai dengan referensi.
                            $param[] = &$nilaiInsert[$key];
                        }
                        // Jalankan bind_param dinamis.
                        call_user_func_array("mysqli_stmt_bind_param", $param);

                        // Jika insert berhasil.
                        if (mysqli_stmt_execute($stmt)) {
                            // Simpan pesan sukses.
                            $_SESSION["flash_pesan"] = "Data peserta berhasil ditambahkan.";
                            // Tutup statement.
                            mysqli_stmt_close($stmt);
                            // Redirect ke halaman yang sama.
                            header("Location: kelolapeserta.php");
                            // Hentikan proses.
                            exit();
                        }

                        // Simpan error jika execute gagal.
                        $pesan = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
                        // Tutup statement.
                        mysqli_stmt_close($stmt);
                    } else {
                        // Simpan error jika prepare gagal.
                        $pesan = "Prepare query gagal: " . mysqli_error($koneksi);
                    }
                } else {
                    // Susun query update dasar.
                    $sql = "UPDATE $namaTabel SET $kolomNama = ?, $kolomUmur = ?, $kolomJK = ?, $kolomPelatihan = ?";
                    // Siapkan nilai update dasar.
                    $nilaiUpdate = [$nama, (int)$umur, $jenisKelamin, $pelatihan];
                    // Siapkan tipe data dasar.
                    $tipeUpdate = "siss";

                    // Jika kolom foto tersedia.
                    if ($kolomFoto) {
                        // Tambahkan update kolom foto.
                        $sql .= ", $kolomFoto = ?";
                        // Tambahkan nilai foto.
                        $nilaiUpdate[] = $fotoUntukDisimpan;
                        // Tambahkan tipe string.
                        $tipeUpdate .= "s";
                    }

                    // Tambahkan kondisi WHERE berdasarkan id.
                    $sql .= " WHERE $kolomId = ? LIMIT 1";
                    // Tambahkan id peserta ke nilai update.
                    $nilaiUpdate[] = $idPeserta;
                    // Tambahkan tipe integer.
                    $tipeUpdate .= "i";

                    // Prepare query update.
                    $stmt = mysqli_prepare($koneksi, $sql);
                    // Jika prepare berhasil.
                    if ($stmt) {
                        // Siapkan parameter dinamis update.
                        $param = [$stmt, $tipeUpdate];
                        // Tambahkan semua nilai update ke parameter.
                        foreach ($nilaiUpdate as $key => $value) {
                            // Bind tiap nilai dengan referensi.
                            $param[] = &$nilaiUpdate[$key];
                        }
                        // Jalankan bind_param dinamis.
                        call_user_func_array("mysqli_stmt_bind_param", $param);

                        // Jika update berhasil.
                        if (mysqli_stmt_execute($stmt)) {
                            // Jika ada foto baru dan foto lama berbeda.
                            if ($namaFileFoto !== "" && $fotoLama !== "" && $fotoLama !== $namaFileFoto) {
                                // Hapus foto lama dari folder uploads.
                                hapusFotoPeserta($fotoLama);
                            }
                            // Simpan pesan sukses update.
                            $_SESSION["flash_pesan"] = "Data peserta berhasil diperbarui.";
                            // Tutup statement.
                            mysqli_stmt_close($stmt);
                            // Redirect ke halaman yang sama.
                            header("Location: kelolapeserta.php");
                            // Hentikan proses.
                            exit();
                        }

                        // Simpan pesan error update.
                        $pesan = "Gagal memperbarui data: " . mysqli_stmt_error($stmt);
                        // Tutup statement.
                        mysqli_stmt_close($stmt);
                    } else {
                        // Simpan pesan jika prepare update gagal.
                        $pesan = "Prepare query edit gagal: " . mysqli_error($koneksi);
                    }
                }

                // Jika proses gagal setelah upload file baru.
                if ($pesan !== "" && $namaFileFoto !== "") {
                    // Hapus file baru agar tidak menjadi sampah.
                    hapusFotoPeserta($namaFileFoto);
                }
            }
        }
    }
}

// Jika semua kolom penting tersedia.
if ($kolomNama && $kolomUmur && $kolomJK && $kolomPelatihan && $kolomId) {
    // Buat bagian select untuk kolom foto.
    $selectFoto = $kolomFoto ? ", $kolomFoto AS foto" : ", '' AS foto";
    // Susun query untuk menampilkan data peserta.
    $sqlTampil = "SELECT
        $kolomId AS id,
        $kolomNama AS nama,
        $kolomUmur AS umur,
        $kolomJK AS jenis_kelamin,
        $kolomPelatihan AS pelatihan
        $selectFoto
        FROM $namaTabel
        WHERE $kolomNama IS NOT NULL AND TRIM($kolomNama) <> ''
        AND $kolomUmur IS NOT NULL
        AND $kolomJK IS NOT NULL AND TRIM($kolomJK) <> ''
        AND $kolomPelatihan IS NOT NULL AND TRIM($kolomPelatihan) <> ''
        ORDER BY $kolomId ASC";
    // Jalankan query tampil.
    $queryTampil = mysqli_query($koneksi, $sqlTampil);
    // Jika query tampil berhasil.
    if ($queryTampil) {
        // Loop semua hasil query.
        while ($row = mysqli_fetch_assoc($queryTampil)) {
            // Simpan setiap baris ke array daftar peserta.
            $daftarPeserta[] = $row;
        }
    } elseif ($pesan === "") {
        // Tampilkan error query jika belum ada pesan lain.
        $pesan = "Gagal mengambil data peserta: " . mysqli_error($koneksi);
    }
} elseif ($pesan === "") {
    // Pesan jika struktur tabel belum siap.
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
                <span><?php echo htmlspecialchars($_SESSION["username"] ?: "Dinas Sosial"); ?></span>
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
