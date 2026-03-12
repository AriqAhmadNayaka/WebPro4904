<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
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
                    if(isset($_POST["nama"])){?>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            <?php echo $_POST["nama"]; ?>
                        </td>
                        <td>
                            <?php echo $_POST["usia"]; ?>
                        </td>
                        <td>
                            <?php echo $_POST["jenisKelamin"]; ?>
                        </td>
                        <td>
                            <?php echo $_POST["pelatihan"]; ?>
                        </td>
                    </tr>
                    <?php
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
            <input type="number" name="usia" id="usiaInput">

            <label>Jenis Kelamin</label>
            <select id="genderInput" name="jenisKelamin">
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
                <button id="saveBtn">Simpan</button>
                <button id="closeModal">Batal</button>
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
