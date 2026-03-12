// kelolapeserta.js (perbaikan lengkap)

// ambil data peserta dari localStorage (atau array kosong)
let peserta = JSON.parse(localStorage.getItem("pesertaData")) || [];
let editIndex = null;

// elemen
const modal = document.getElementById("modalForm");
const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");
const saveBtn = document.getElementById("saveBtn");

// elemen form
const namaInput = document.getElementById("namaInput");
const usiaInput = document.getElementById("usiaInput");
const genderInput = document.getElementById("genderInput");
const selectPelatihan = document.getElementById("inputPelatihan");

// === SIMPAN KE LOCAL STORAGE ===
function saveToLocalStorage() {
    localStorage.setItem("pesertaData", JSON.stringify(peserta));
}

// === RENDER TABLE ===
function renderTable() {
    const tbody = document.getElementById("pesertaTable");
    tbody.innerHTML = "";

    peserta.forEach((p, i) => {
        tbody.innerHTML += `
            <tr>
                <td>${i + 1}</td>
                <td>${p.nama}</td>
                <td>${p.usia}</td>
                <td>${p.gender}</td>
                <td>${p.pelatihan}</td>
                <td>
                    <button class="edit" onclick="editData(${i})">Edit</button>
                    <button class="delete" onclick="deleteData(${i})">Hapus</button>
                </td>
            </tr>
        `;
    });
}

// === OPEN MODAL ===
openModal.onclick = () => {
    editIndex = null;
    document.getElementById("modalTitle").innerText = "Tambah Peserta";

    // kosongkan field
    namaInput.value = "";
    usiaInput.value = "";
    genderInput.value = "Laki-laki";
    // pastikan dropdown pelatihan ter-update sebelum buka modal
    loadPelatihanForPeserta();
    selectPelatihan.selectedIndex = 0;

    modal.style.display = "flex";
};

// === CLOSE MODAL ===
closeModal.onclick = () => modal.style.display = "none";

// === SAVE DATA ===
saveBtn.onclick = () => {
    const nama = namaInput.value.trim();
    const usia = usiaInput.value;
    const gender = genderInput.value;
    const pelatihan = selectPelatihan.value; // <-- gunakan select, bukan input teks

    if (!nama) {
        alert("Nama peserta wajib diisi.");
        return;
    }

    const data = { nama, usia, gender, pelatihan };

    if (editIndex === null) {
        peserta.push(data);
    } else {
        peserta[editIndex] = data;
    }

    saveToLocalStorage();  // SIMPAN
    modal.style.display = "none";
    renderTable();

    // jika ada halaman Kehadiran yang memakai peserta, mungkin perlu reload dropdown di kehadiran
    // window.dispatchEvent(new Event('pesertaUpdated')); // uncomment jika pakai event listeners
};

// === EDIT DATA ===
function editData(i) {
    editIndex = i;
    const p = peserta[i];

    document.getElementById("modalTitle").innerText = "Edit Peserta";
    namaInput.value = p.nama;
    usiaInput.value = p.usia;
    genderInput.value = p.gender;

    // pastikan dropdown terisi dari pelatihanData lalu pilih nilai p.pelatihan
    loadPelatihanForPeserta();
    // tunggu sedikit agar opsi sudah ada, lalu set value (sederhana: setTimeout 0)
    setTimeout(() => {
        if ([...selectPelatihan.options].some(opt => opt.value === p.pelatihan)) {
            selectPelatihan.value = p.pelatihan;
        } else {
            // jika pelatihan yang tersimpan tidak ada lagi di daftar pelatihan,
            // tambahkan sebagai opsi supaya user bisa melihat/mengeditnya
            let opt = document.createElement("option");
            opt.value = p.pelatihan;
            opt.textContent = p.pelatihan;
            selectPelatihan.appendChild(opt);
            selectPelatihan.value = p.pelatihan;
        }
    }, 0);

    modal.style.display = "flex";
}

// === DELETE DATA ===
function deleteData(i) {
    if (!confirm("Hapus peserta ini?")) return;

    peserta.splice(i, 1);
    saveToLocalStorage();  // SIMPAN PERUBAHAN
    renderTable();
}

// === SEARCH ===
document.getElementById("searchPeserta").addEventListener("keyup", function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll("#pesertaTable tr");

    rows.forEach(row => {
        const nama = row.children[1].textContent.toLowerCase();
        row.style.display = nama.includes(keyword) ? "" : "none";
    });
});

// === LOAD DATA SAAT HALAMAN DIBUKA ===
renderTable();


// === FUNGSI: LOAD PELATIHAN UNTUK DROPDOWN PESERTA ===
function loadPelatihanForPeserta() {
    const select = document.getElementById("inputPelatihan");
    // baca array pelatihan dari localStorage
    // Perhatian: sesuaikan property nama pelatihan: 'nama' atau 'namaPelatihan'
    let pelatihanList = JSON.parse(localStorage.getItem("pelatihanData")) || [];

    select.innerHTML = ""; // reset

    pelatihanList.forEach(p => {
        // sesuaikan p.nama jika di pelatihanData kamu propertynya 'nama'
        const namaPel = p.nama !== undefined ? p.nama : (p.namaPelatihan !== undefined ? p.namaPelatihan : "");
        let option = document.createElement("option");
        option.value = namaPel;
        option.textContent = namaPel;
        select.appendChild(option);
    });

    // jika tidak ada pelatihan, beri fallback agar tidak kosong
    if (select.options.length === 0) {
        let opt = document.createElement("option");
        opt.value = "";
        opt.textContent = "Belum ada pelatihan";
        select.appendChild(opt);
    }
}

// panggil load saat file dijalankan agar dropdown terisi
loadPelatihanForPeserta();
