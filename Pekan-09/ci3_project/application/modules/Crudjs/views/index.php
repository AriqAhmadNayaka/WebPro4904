<?php $this->load->view('crudjs/header', array('title' => $title)); ?>

<main class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">CRUD with AJAX</h1>
            <p class="text-secondary mb-0">Module HMVC Crudjs untuk praktikum CodeIgniter 3.</p>
        </div>
        <button class="btn btn-primary" type="button" onclick="openCreateModal()">Tambah Post</button>
    </div>

    <div id="alertBox"></div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 72px;">ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Artikel</th>
                        <th style="width: 120px;">Gambar</th>
                        <th style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="postTable">
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" id="postForm" enctype="multipart/form-data">
            <div class="modal-header">
                <h2 class="modal-title h5" id="modalTitle">Tambah Post</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="postId">

                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input class="form-control" type="text" id="title" name="title" maxlength="255" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="author">Author</label>
                    <input class="form-control" type="text" id="author" name="author" maxlength="255" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="article">Article</label>
                    <textarea class="form-control" id="article" name="article" rows="5" required></textarea>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="image">Image</label>
                    <input class="form-control" type="file" id="image" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                    <div class="form-text">Format jpg, jpeg, png. Maksimal 2 MB.</div>
                </div>

                <div id="currentImage" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
const baseUrl = '<?php echo site_url('crudjs'); ?>';
const uploadUrl = '<?php echo base_url('uploads/'); ?>';
const modalElement = document.getElementById('postModal');
const postModal = new bootstrap.Modal(modalElement);
const form = document.getElementById('postForm');

document.addEventListener('DOMContentLoaded', loadPosts);

form.addEventListener('submit', function (event) {
    event.preventDefault();

    const id = document.getElementById('postId').value;
    const url = id ? `${baseUrl}/update/${id}` : `${baseUrl}/store`;
    const formData = new FormData(form);
    const saveButton = document.getElementById('saveButton');

    saveButton.disabled = true;
    saveButton.textContent = 'Menyimpan...';

    fetch(url, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                showAlert(result.message, 'success');
                postModal.hide();
                loadPosts();
            } else {
                showAlert(result.message || 'Terjadi kesalahan', 'danger');
            }
        })
        .catch(error => showAlert(error.message, 'danger'))
        .finally(() => {
            saveButton.disabled = false;
            saveButton.textContent = 'Simpan';
        });
});

function loadPosts() {
    fetch(`${baseUrl}/get_all`)
        .then(response => response.json())
        .then(result => {
            const tbody = document.getElementById('postTable');

            if (result.status !== 'success') {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">${escapeHtml(result.message || 'Gagal memuat data')}</td></tr>`;
                return;
            }

            if (!result.data.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-secondary py-4">Belum ada data.</td></tr>';
                return;
            }

            tbody.innerHTML = result.data.map(record => {
                const image = record.image
                    ? `<img src="${uploadUrl}${record.image}" alt="${escapeHtml(record.title)}" class="rounded object-fit-cover" style="width: 84px; height: 56px;">`
                    : '<span class="text-secondary">-</span>';

                return `
                    <tr>
                        <td>${record.id}</td>
                        <td>${escapeHtml(record.title)}</td>
                        <td>${escapeHtml(record.author)}</td>
                        <td>${escapeHtml(shortText(record.article, 90))}</td>
                        <td>${image}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning" type="button" onclick="editPost(${record.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" type="button" onclick="deletePost(${record.id})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        })
        .catch(error => showAlert(error.message, 'danger'));
}

function openCreateModal() {
    form.reset();
    document.getElementById('postId').value = '';
    document.getElementById('modalTitle').textContent = 'Tambah Post';
    document.getElementById('currentImage').innerHTML = '';
    postModal.show();
}

function editPost(id) {
    fetch(`${baseUrl}/get_record/${id}`)
        .then(response => response.json())
        .then(result => {
            if (result.status !== 'success') {
                showAlert(result.message || 'Data tidak ditemukan', 'danger');
                return;
            }

            const record = result.data;
            form.reset();
            document.getElementById('postId').value = record.id;
            document.getElementById('title').value = record.title || '';
            document.getElementById('author').value = record.author || '';
            document.getElementById('article').value = record.article || '';
            document.getElementById('modalTitle').textContent = 'Edit Post';
            document.getElementById('currentImage').innerHTML = record.image
                ? `<img src="${uploadUrl}${record.image}" alt="${escapeHtml(record.title)}" class="rounded border" style="max-width: 180px;">`
                : '';
            postModal.show();
        })
        .catch(error => showAlert(error.message, 'danger'));
}

function deletePost(id) {
    if (!confirm('Yakin ingin menghapus data ini?')) {
        return;
    }

    fetch(`${baseUrl}/delete/${id}`, { method: 'POST' })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                showAlert(result.message, 'success');
                loadPosts();
            } else {
                showAlert(result.message || 'Gagal menghapus data', 'danger');
            }
        })
        .catch(error => showAlert(error.message, 'danger'));
}

function showAlert(message, type) {
    document.getElementById('alertBox').innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    `;
}

function shortText(text, maxLength) {
    text = text || '';
    return text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
}

function escapeHtml(value) {
    return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>

<?php $this->load->view('crudjs/footer'); ?>
