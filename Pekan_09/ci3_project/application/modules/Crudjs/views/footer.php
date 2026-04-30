<script>
// Menyimpan URL dasar endpoint CRUDJS agar fetch AJAX lebih ringkas.
const baseUrl = "<?php echo base_url('crudjs'); ?>";

// Menjalankan kode setelah seluruh struktur HTML selesai dimuat.
document.addEventListener('DOMContentLoaded', function () {
    // Mengambil data post pertama kali saat halaman dibuka.
    loadRecords();
    // Menambahkan event submit pada form agar disimpan lewat AJAX.
    document.getElementById('postForm').addEventListener('submit', savePost);
});

// Fungsi untuk menampilkan pesan notifikasi sukses atau error.
function showAlert(message, type) {
    // Mengambil elemen alert dari halaman.
    const alert = document.getElementById('alert');
    // Mengatur class alert sesuai tipe pesan.
    alert.className = 'alert alert-' + type;
    // Mengisi teks pesan ke dalam alert.
    alert.innerHTML = message;
    // Menampilkan alert.
    alert.style.display = 'block';
    // Menyembunyikan alert otomatis setelah 3 detik.
    setTimeout(() => alert.style.display = 'none', 3000);
}

// Fungsi AJAX untuk mengambil semua data post dari server.
function loadRecords() {
    // Menampilkan indikator loading.
    document.getElementById('loading').style.display = 'block';
    // Mengirim request ke endpoint crudjs/get_all.
    fetch(baseUrl + '/get_all')
        // Mengubah response server menjadi JSON.
        .then(response => response.json())
        // Memproses data JSON yang diterima.
        .then(result => {
            // Menyembunyikan indikator loading setelah response diterima.
            document.getElementById('loading').style.display = 'none';
            // Mengambil elemen tbody tabel.
            const tbody = document.getElementById('postsBody');
            // Mengosongkan isi tabel sebelum diisi data terbaru.
            tbody.innerHTML = '';

            // Mengecek apakah response sukses dan memiliki data.
            if (result.status === 'success' && result.data.length > 0) {
                // Melakukan perulangan untuk setiap data post.
                result.data.forEach(row => {
                    // Menambahkan baris tabel baru untuk setiap post.
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.id}</td>
                            <td>${row.image_url ? `<img src="${row.image_url}" class="image-preview">` : '-'}</td>
                            <td>${escapeHtml(row.title || '')}</td>
                            <td>${escapeHtml(row.author || '')}</td>
                            <td>${escapeHtml((row.article || '').substring(0, 100))}</td>
                            <td>${row.created_at || '-'}</td>
                            <td class="actions">
                                <button class="btn btn-warning" onclick="editPost(${row.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deletePost(${row.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            // Jika data kosong atau response bukan success.
            } else {
                // Menampilkan baris keterangan bahwa belum ada data.
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Belum ada data</td></tr>';
            }
        })
        // Menangkap error jika request AJAX gagal.
        .catch(error => {
            // Menyembunyikan indikator loading.
            document.getElementById('loading').style.display = 'none';
            // Menampilkan pesan error ke user.
            showAlert('Error loading records: ' + error.message, 'error');
        });
}

// Fungsi untuk membuka modal tambah data.
function openModal() {
    // Mengatur judul modal menjadi Tambah Post.
    document.getElementById('modalTitle').innerText = 'Tambah Post';
    // Mengosongkan semua input form.
    document.getElementById('postForm').reset();
    // Mengosongkan ID agar form dianggap sebagai tambah data.
    document.getElementById('postId').value = '';
    // Mengosongkan preview gambar lama.
    document.getElementById('currentImage').innerHTML = '';
    // Menampilkan modal ke layar.
    document.getElementById('postModal').style.display = 'block';
}

// Fungsi untuk menutup modal.
function closeModal() {
    // Menyembunyikan modal dari layar.
    document.getElementById('postModal').style.display = 'none';
}

// Fungsi untuk menyimpan data tambah atau edit menggunakan AJAX.
function savePost(event) {
    // Mencegah form reload halaman secara default.
    event.preventDefault();
    // Mengambil nilai ID untuk menentukan tambah atau update.
    const id = document.getElementById('postId').value;
    // Mengambil semua data form termasuk file gambar.
    const formData = new FormData(document.getElementById('postForm'));
    // Menentukan URL tujuan: update jika ada ID, store jika tidak ada ID.
    const url = id ? baseUrl + '/update/' + id : baseUrl + '/store';

    // Mengirim data form ke server menggunakan method POST.
    fetch(url, { method: 'POST', body: formData })
        // Mengubah response server menjadi JSON.
        .then(response => response.json())
        // Memproses hasil simpan/update dari server.
        .then(result => {
            // Mengecek apakah proses simpan/update berhasil.
            if (result.status === 'success') {
                // Menutup modal setelah sukses.
                closeModal();
                // Memuat ulang data tabel agar data terbaru tampil.
                loadRecords();
                // Menampilkan pesan sukses.
                showAlert(result.message, 'success');
            // Jika server mengirim status error.
            } else {
                // Menampilkan pesan error dari server.
                showAlert(result.message, 'error');
            }
        })
        // Menangkap error jika request AJAX gagal.
        .catch(error => showAlert('Error saving record: ' + error.message, 'error'));
}

// Fungsi untuk mengambil data yang akan diedit berdasarkan ID.
function editPost(id) {
    // Mengirim request ke endpoint get_record dengan ID post.
    fetch(baseUrl + '/get_record/' + id)
        // Mengubah response server menjadi JSON.
        .then(response => response.json())
        // Memproses data post yang diterima.
        .then(result => {
            // Mengecek apakah data berhasil ditemukan.
            if (result.status === 'success') {
                // Menyimpan data post ke variabel row.
                const row = result.data;
                // Mengubah judul modal menjadi Edit Post.
                document.getElementById('modalTitle').innerText = 'Edit Post';
                // Mengisi input hidden ID dengan ID post.
                document.getElementById('postId').value = row.id;
                // Mengisi input title dengan data dari server.
                document.getElementById('title').value = row.title || '';
                // Mengisi input author dengan data dari server.
                document.getElementById('author').value = row.author || '';
                // Mengisi textarea article dengan data dari server.
                document.getElementById('article').value = row.article || '';
                // Menampilkan preview gambar jika post memiliki gambar.
                document.getElementById('currentImage').innerHTML = row.image_url ? `<img src="${row.image_url}" class="image-preview">` : '';
                // Menampilkan modal edit.
                document.getElementById('postModal').style.display = 'block';
            // Jika data gagal ditemukan.
            } else {
                // Menampilkan pesan error dari server.
                showAlert(result.message, 'error');
            }
        });
}

// Fungsi untuk menghapus post berdasarkan ID.
function deletePost(id) {
    // Menampilkan konfirmasi sebelum data dihapus.
    if (!confirm('Yakin ingin menghapus data ini?')) return;

    // Mengirim request hapus ke endpoint delete dengan method POST.
    fetch(baseUrl + '/delete/' + id, { method: 'POST' })
        // Mengubah response server menjadi JSON.
        .then(response => response.json())
        // Memproses hasil hapus dari server.
        .then(result => {
            // Mengecek apakah data berhasil dihapus.
            if (result.status === 'success') {
                // Memuat ulang data tabel setelah hapus.
                loadRecords();
                // Menampilkan pesan sukses.
                showAlert(result.message, 'success');
            // Jika hapus gagal.
            } else {
                // Menampilkan pesan error dari server.
                showAlert(result.message, 'error');
            }
        })
        // Menangkap error jika request AJAX gagal.
        .catch(error => showAlert('Error deleting record: ' + error.message, 'error'));
}

// Fungsi untuk mengamankan teks agar tidak menjalankan HTML/script berbahaya.
function escapeHtml(text) {
    // Membuat elemen div sementara.
    const div = document.createElement('div');
    // Memasukkan teks sebagai textContent agar karakter HTML di-escape.
    div.textContent = text;
    // Mengembalikan teks yang sudah aman dalam bentuk HTML.
    return div.innerHTML;
}

// Event global untuk menutup modal saat area luar modal diklik.
window.onclick = function (event) {
    // Mengambil elemen modal.
    const modal = document.getElementById('postModal');
    // Mengecek apakah area yang diklik adalah background modal.
    if (event.target === modal) {
        // Menutup modal jika user klik area luar konten modal.
        closeModal();
    }
};
</script>
</body>
</html>
