<?php
// Session dipakai untuk proteksi halaman (hanya user login yang boleh masuk).
session_start();
// Koneksi database.
include "koneksi.php";

// Jika belum login, paksa kembali ke halaman register.
if(!isset($_SESSION['user'])){
    header('Location: register.php');
    exit();
}

// Flash message: diisi sebelum redirect, lalu ditampilkan sekali.
$pesan = $_SESSION["flash_pesan"] ?? "";
unset($_SESSION["flash_pesan"]);
// Array penampung data peserta untuk ditampilkan di tabel.
$daftarPeserta = [];

// Pakai tabel user sesuai struktur database saat ini.
$namaTabel = "user";
$kolomUser = [];
// Ambil daftar kolom agar query fleksibel walau nama kolom sedikit berbeda.
$cekKolom = mysqli_query($koneksi, "SHOW COLUMNS FROM $namaTabel");
if ($cekKolom) {
    while ($k = mysqli_fetch_assoc($cekKolom)) {
        $kolomUser[] = $k["Field"];
    }
}

// Mapping nama kolom yang mungkin berbeda antar database.
$kolomNama = in_array("nama", $kolomUser, true) ? "nama" : (in_array("username", $kolomUser, true) ? "username" : null);
$kolomUmur = in_array("umur", $kolomUser, true) ? "umur" : (in_array("usia", $kolomUser, true) ? "usia" : null);
$kolomJK = in_array("jenis_kelamin", $kolomUser, true) ? "jenis_kelamin" : (in_array("jenisKelamin", $kolomUser, true) ? "jenisKelamin" : null);
$kolomPelatihan = in_array("pelatihan", $kolomUser, true) ? "pelatihan" : null;
$kolomId = in_array("id", $kolomUser, true) ? "id" : (in_array("id_user", $kolomUser, true) ? "id_user" : null);

// =========================
// PROSES INSERT PESERTA
// =========================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nama"])) {
    $nama = trim($_POST["nama"] ?? "");
    $umur = trim($_POST["umur"] ?? "");
    $jenisKelamin = trim($_POST["jenis_kelamin"] ?? "");
    $pelatihan = trim($_POST["pelatihan"] ?? "");

    if ($nama === "" || $umur === "" || $jenisKelamin === "" || $pelatihan === "") {
        $pesan = "Nama, umur, jenis kelamin, dan pelatihan wajib diisi.";
    } else {
        if ($kolomNama && $kolomUmur && $kolomJK && $kolomPelatihan) {
            $kolomInsert = [$kolomNama, $kolomUmur, $kolomJK, $kolomPelatihan];
            $nilaiInsert = [$nama, (int)$umur, $jenisKelamin, $pelatihan];
            $tipeInsert = "siss";

            // Bentuk query dinamis sesuai kolom yang aktif.
            $placeholders = implode(", ", array_fill(0, count($kolomInsert), "?"));
            $listKolom = implode(", ", $kolomInsert);
            $sql = "INSERT INTO $namaTabel ($listKolom) VALUES ($placeholders)";
            $stmt = mysqli_prepare($koneksi, $sql);

            if ($stmt) {
                // bind_param dinamis memerlukan referensi tiap elemen array.
                $param = [$stmt, $tipeInsert];
                foreach ($nilaiInsert as $key => $value) {
                    $param[] = &$nilaiInsert[$key];
                }
                call_user_func_array("mysqli_stmt_bind_param", $param);
                if (mysqli_stmt_execute($stmt)) {
                    // PRG pattern: redirect setelah submit agar refresh tidak insert ulang.
                    $_SESSION["flash_pesan"] = "Data peserta berhasil ditambahkan.";
                    header("Location: kelolapeserta.php");
                    exit();
                } else {
                    $pesan = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $pesan = "Prepare query gagal: " . mysqli_error($koneksi);
            }
        } else {
            $pesan = "Kolom tabel user belum lengkap. Tambahkan kolom: jenis_kelamin dan pelatihan.";
        }
    }
}

// =========================
// AMBIL DATA UNTUK TABEL
// =========================
if ($kolomNama && $kolomUmur && $kolomJK && $kolomPelatihan) {
    $urutan = $kolomId ? $kolomId : $kolomNama;
    $sqlTampil = "SELECT 
        $kolomNama AS nama, 
        $kolomUmur AS umur, 
        $kolomJK AS jenis_kelamin, 
        $kolomPelatihan AS pelatihan 
        FROM $namaTabel 
        WHERE $kolomNama IS NOT NULL AND TRIM($kolomNama) <> ''
        AND $kolomUmur IS NOT NULL
        AND $kolomJK IS NOT NULL AND TRIM($kolomJK) <> ''
        AND $kolomPelatihan IS NOT NULL AND TRIM($kolomPelatihan) <> ''
        ORDER BY $urutan ASC";
    $queryTampil = mysqli_query($koneksi, $sqlTampil);
    if ($queryTampil) {
        while ($row = mysqli_fetch_assoc($queryTampil)) {
            $daftarPeserta[] = $row;
        }
    } elseif ($pesan === "") {
        $pesan = "Gagal mengambil data peserta: " . mysqli_error($koneksi);
    }
} elseif ($pesan === "") {
    $pesan = "Tabel user belum siap ditampilkan. Pastikan ada kolom nama/username, umur/usia, jenis_kelamin, pelatihan.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Peserta</title>

    <link rel="stylesheet" href="kelolapeserta.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body>
    <?php if ($pesan !== ""): ?>
        <!-- Notifikasi singkat dari proses backend -->
        <script>alert('<?php echo addslashes($pesan); ?>');</script>
    <?php endif; ?>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2 class="sidebar-title">InkluSkill</h2>

        <a href="dinas.html"><i class="ri-dashboard-line"></i> Dashboard</a>
        <a class="active" href="#"><i class="ri-user-3-line"></i> Kelola Peserta</a>
        <a href="peltihan.html"><i class="ri-calendar-check-line"></i> Pelatihan & Jadwal</a>
        <a href="kehadiran.html"><i class="ri-time-line"></i> Kehadiran</a>
        <a href="statistik.html"><i class="ri-bar-chart-box-line"></i> Statistik</a>
        <a href="laporan.html"><i class="ri-file-list-3-line"></i> Laporan</a>

        <button class="logout-btn"><i class="ri-logout-box-line"></i> Logout</button>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <header>
            <h1 class="title-page"><i class="ri-user-3-line"></i> Kelola Peserta</h1>

            <div class="profile">
                <img src="../assets/admin.jpg" alt="">
                <span>Dinas Sosial</span>
            </div>
        </header>

        <!-- ACTION BAR -->
        <div class="action-row">
            <input type="text" id="searchPeserta" placeholder="Cari peserta...">
            <button class="add-btn" id="openModal"><i class="ri-add-circle-line"></i> Tambah Peserta</button>
        </div>

        <!-- TABLE -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>Usia</th>
                        <th>Jenis Kelamin</th>
                        <th>Pelatihan Diikuti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pesertaTable">
                    <?php
                        $no = 1;
                        if (!empty($daftarPeserta)) {
                        foreach ($daftarPeserta as $data) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data["nama"]; ?></td>
                        <td><?php echo $data["umur"]; ?></td>
                        <td><?php echo $data["jenis_kelamin"]; ?></td>
                        <td><?php echo $data["pelatihan"]; ?></td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>Data belum ada</td></tr>";
                        }
                    ?>


                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal" id="modalForm">
        <div class="modal-content">
            <h3 id="modalTitle">Tambah Peserta</h3>
            <form action="" method="POST">
            <label>Nama Peserta</label>
            <input type="text" name="nama" id="namaInput">

            <label>Usia</label>
            <input type="number" name="umur" id="usiaInput">

            <label>Jenis Kelamin</label>
            <select id="genderInput" name="jenis_kelamin">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            <label>Pelatihan</label>
            
                <select
                 id="inputPelatihan" name="pelatihan">
                 <option value="Moshing">Moshing</option>
                <option value="Icikiwir">Icikiwir</option>
    <!-- otomatis terisi dari JS -->
                </select>

            <div class="modal-buttons">
                <button id="saveBtn" type="submit">Simpan</button>
                <button id="closeModal" type="button">Batal</button>
            </div>
            </form>
        </div>
    </div>
<script>
    // kelolapeserta.js

// mengambil data peserta dari localStorage
// jika belum ada data maka akan menggunakan array kosong []
let peserta = JSON.parse(localStorage.getItem("pesertaData")) || [];

// variabel untuk menyimpan index data yang sedang diedit
let editIndex = null;


// ===========================
// MENGAMBIL ELEMEN HTML
// ===========================

// mengambil elemen modal form
const modal = document.getElementById("modalForm");

// tombol untuk membuka modal
const openModal = document.getElementById("openModal");

// tombol untuk menutup modal
const closeModal = document.getElementById("closeModal");

// tombol simpan data
const saveBtn = document.getElementById("saveBtn");


// ===========================
// ELEMEN INPUT FORM
// ===========================

// input nama peserta
const namaInput = document.getElementById("namaInput");

// input usia peserta
const usiaInput = document.getElementById("usiaInput");

// input jenis kelamin peserta
const genderInput = document.getElementById("genderInput");

// dropdown pelatihan
const selectPelatihan = document.getElementById("inputPelatihan");


// ===========================
// FUNGSI SIMPAN DATA
// ===========================

// fungsi untuk menyimpan array peserta ke localStorage
function saveToLocalStorage() {

    // mengubah array peserta menjadi string JSON
    // lalu disimpan ke localStorage dengan key "pesertaData"
    localStorage.setItem("pesertaData", JSON.stringify(peserta));

}


// ===========================
// FUNGSI MENAMPILKAN DATA KE TABEL
// ===========================

function renderTable() {

    // mengambil bagian body tabel
    const tbody = document.getElementById("pesertaTable");

    // mengosongkan isi tabel terlebih dahulu
    tbody.innerHTML = "";

    // melakukan perulangan pada setiap data peserta
    peserta.forEach((p, i) => {

        // menambahkan baris tabel menggunakan template string
        tbody.innerHTML += `
            <tr>
                <td>${i + 1}</td> 
                <!-- nomor urutan -->

                <td>${p.nama}</td> 
                <!-- nama peserta -->

                <td>${p.usia}</td> 
                <!-- usia peserta -->

                <td>${p.gender}</td> 
                <!-- jenis kelamin -->

                <td>${p.pelatihan}</td> 
                <!-- pelatihan yang diikuti -->

                <td>
                    <!-- tombol edit memanggil fungsi editData dengan index -->
                    <button class="edit" onclick="editData(${i})">Edit</button>

                    <!-- tombol hapus memanggil fungsi deleteData -->
                    <button class="delete" onclick="deleteData(${i})">Hapus</button>
                </td>
            </tr>
        `;
    });

}


// ===========================
// FUNGSI MEMBUKA MODAL TAMBAH PESERTA
// ===========================

openModal.onclick = () => {

    // menandakan bahwa ini bukan mode edit
    editIndex = null;

    // mengganti judul modal
    document.getElementById("modalTitle").innerText = "Tambah Peserta";

    // mengosongkan input form
    namaInput.value = "";
    usiaInput.value = "";

    // set default gender
    genderInput.value = "Laki-laki";

    // memilih pelatihan pertama di dropdown
    selectPelatihan.selectedIndex = 0;

    // menampilkan modal
    modal.style.display = "flex";
};


// ===========================
// FUNGSI MENUTUP MODAL
// ===========================

// ketika tombol batal diklik
closeModal.onclick = () =>

    // modal akan disembunyikan
    modal.style.display = "none";

</script>
</body>
</html>
