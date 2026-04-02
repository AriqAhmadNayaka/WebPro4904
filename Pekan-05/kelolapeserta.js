// Mengambil elemen modal utama.
const modal = document.getElementById("modalForm");
// Tombol untuk membuka modal tambah peserta.
const openModal = document.getElementById("openModal");
// Tombol untuk menutup modal.
const closeModal = document.getElementById("closeModal");
// Form tambah dan edit peserta.
const pesertaForm = document.getElementById("pesertaForm");
// Judul modal.
const modalTitle = document.getElementById("modalTitle");
// Input hidden untuk membedakan aksi tambah atau edit.
const aksiInput = document.getElementById("aksiInput");
// Input hidden untuk id peserta saat edit.
const idInput = document.getElementById("idInput");
// Input hidden untuk menyimpan nama file foto lama.
const fotoLamaInput = document.getElementById("fotoLamaInput");
// Input nama peserta.
const namaInput = document.getElementById("namaInput");
// Input usia peserta.
const usiaInput = document.getElementById("usiaInput");
// Input jenis kelamin peserta.
const genderInput = document.getElementById("genderInput");
// Select pelatihan peserta.
const selectPelatihan = document.getElementById("inputPelatihan");
// Input file foto peserta.
const fotoInput = document.getElementById("fotoInput");
// Teks informasi foto saat ini.
const fotoInfo = document.getElementById("fotoInfo");
// Input pencarian peserta pada tabel.
const searchPeserta = document.getElementById("searchPeserta");

// Fungsi untuk memuat daftar pelatihan ke dropdown.
function loadPelatihanForPeserta(selectedValue = "") {
    // Ambil daftar pelatihan dari localStorage jika ada.
    const pelatihanList = JSON.parse(localStorage.getItem("pelatihanData")) || [];
    // Opsi fallback jika localStorage belum berisi data pelatihan.
    const fallbackOptions = ["Moshing", "Icikiwir"];
    // Kosongkan select sebelum diisi ulang.
    selectPelatihan.innerHTML = "";

    // Tentukan daftar opsi yang akan dipakai.
    const opsi = pelatihanList.length > 0
        ? pelatihanList.map((item) => item.nama ?? item.namaPelatihan ?? "").filter(Boolean)
        : fallbackOptions;

    // Tambahkan semua opsi ke elemen select.
    opsi.forEach((namaPelatihan) => {
        const option = document.createElement("option");
        option.value = namaPelatihan;
        option.textContent = namaPelatihan;
        selectPelatihan.appendChild(option);
    });

    // Jika nilai lama tidak ada dalam opsi, tambahkan secara manual.
    if (selectedValue && !opsi.includes(selectedValue)) {
        const option = document.createElement("option");
        option.value = selectedValue;
        option.textContent = selectedValue;
        selectPelatihan.appendChild(option);
    }

    // Jika ada nilai terpilih, set value select ke nilai tersebut.
    if (selectedValue) {
        selectPelatihan.value = selectedValue;
    }
}

// Fungsi untuk mereset form ke mode tambah data.
function resetFormTambah() {
    // Reset seluruh field form.
    pesertaForm.reset();
    // Set aksi ke tambah.
    aksiInput.value = "tambah";
    // Kosongkan id peserta.
    idInput.value = "";
    // Kosongkan nama file foto lama.
    fotoLamaInput.value = "";
    // Ubah judul modal.
    modalTitle.textContent = "Tambah Peserta";
    // Saat tambah, foto wajib diisi.
    fotoInput.required = true;
    // Tampilkan info foto default.
    fotoInfo.textContent = "Belum ada foto dipilih.";
    // Set gender default.
    genderInput.value = "Laki-laki";
    // Muat ulang daftar pelatihan.
    loadPelatihanForPeserta();
}

// Event saat tombol tambah peserta diklik.
openModal.addEventListener("click", () => {
    // Reset form ke mode tambah.
    resetFormTambah();
    // Tampilkan modal.
    modal.style.display = "flex";
});

// Event saat tombol batal diklik.
closeModal.addEventListener("click", () => {
    // Sembunyikan modal.
    modal.style.display = "none";
});

// Menutup modal jika area gelap di luar modal diklik.
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Fungsi untuk mengisi form ke mode edit berdasarkan data tombol yang diklik.
function editData(button) {
    // Ubah mode form menjadi edit.
    aksiInput.value = "edit";
    // Ambil id peserta dari data attribute tombol.
    idInput.value = button.dataset.id || "";
    // Ambil nama file foto lama dari data attribute tombol.
    fotoLamaInput.value = button.dataset.foto || "";
    // Isi nama peserta ke input.
    namaInput.value = button.dataset.nama || "";
    // Isi usia peserta ke input.
    usiaInput.value = button.dataset.umur || "";
    // Isi jenis kelamin peserta ke select.
    genderInput.value = button.dataset.jk || "Laki-laki";
    // Isi pelatihan sesuai data peserta.
    loadPelatihanForPeserta(button.dataset.pelatihan || "");
    // Kosongkan input file agar user bebas pilih file baru.
    fotoInput.value = "";
    // Saat edit, foto tidak wajib diganti.
    fotoInput.required = false;
    // Ganti judul modal ke edit.
    modalTitle.textContent = "Edit Peserta";
    // Tampilkan info foto lama jika ada.
    fotoInfo.textContent = button.dataset.foto
        ? `Foto saat ini: ${button.dataset.foto}. Pilih file baru jika ingin mengganti.`
        : "Belum ada foto. Pilih file untuk menambahkan foto.";
    // Tampilkan modal edit.
    modal.style.display = "flex";
}

// Daftarkan fungsi edit ke object window agar bisa dipanggil dari HTML inline.
window.editData = editData;

// Event pencarian peserta pada tabel.
searchPeserta.addEventListener("keyup", function () {
    // Ambil kata kunci pencarian.
    const keyword = this.value.toLowerCase();
    // Ambil semua baris tabel peserta.
    const rows = document.querySelectorAll("#pesertaTable tr");

    // Loop setiap baris tabel.
    rows.forEach((row) => {
        // Ambil cell nama peserta.
        const namaCell = row.children[1];
        // Jika cell tidak ada, lewati baris ini.
        if (!namaCell) {
            return;
        }

        // Ambil teks nama peserta lalu ubah ke huruf kecil.
        const nama = namaCell.textContent.toLowerCase();
        // Tampilkan hanya baris yang cocok dengan kata kunci.
        row.style.display = nama.includes(keyword) ? "" : "none";
    });
});

// Muat daftar pelatihan saat halaman pertama kali dibuka.
loadPelatihanForPeserta();
// Tampilkan info awal untuk bagian foto.
fotoInfo.textContent = "Belum ada foto dipilih.";
