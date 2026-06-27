<?php $this->load->view('header'); ?>

<div class="panel">
    <div id="alert" class="alert"></div>

    <div class="toolbar">
        <h2 style="margin: 0;">Daftar Posts</h2>
        <button class="btn btn-success" type="button" onclick="openCreateModal()">+ Tambah Data</button>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th style="width: 110px;">Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>Article</th>
                <th style="width: 220px;">Action</th>
            </tr>
        </thead>
        <tbody id="postRows">
            <tr>
                <td colspan="6">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal" id="postModal">
    <div class="modal-card">
        <div class="modal-head">
            <h3 id="modalTitle" style="margin: 0;">Tambah Post</h3>
            <button class="btn btn-secondary" type="button" onclick="closeModal()">Tutup</button>
        </div>

        <form id="postForm" enctype="multipart/form-data">
            <input type="hidden" id="postId" name="id">

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" required>
            </div>

            <div class="form-group">
                <label for="article">Article</label>
                <textarea id="article" name="article" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png">
                <div id="currentImage"></div>
            </div>

            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <button class="btn btn-secondary" type="button" onclick="closeModal()">Batal</button>
                <button class="btn btn-primary" type="submit" id="saveBtn">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
const baseUrl = '<?php echo base_url(); ?>';
const rows = document.getElementById('postRows');
const modal = document.getElementById('postModal');
const form = document.getElementById('postForm');
const alertBox = document.getElementById('alert');

document.addEventListener('DOMContentLoaded', loadPosts);

form.addEventListener('submit', function (event) {
    event.preventDefault();

    const id = document.getElementById('postId').value;
    const url = id ? baseUrl + 'crudjs/update/' + id : baseUrl + 'crudjs/create';
    const formData = new FormData(form);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (!result.status) {
            showAlert(result.message || 'Data gagal disimpan', 'error');
            return;
        }

        closeModal();
        showAlert(result.message || 'Data berhasil disimpan', 'success');
        loadPosts();
    })
    .catch(() => showAlert('Terjadi kesalahan request', 'error'));
});

function loadPosts() {
    rows.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';

    fetch(baseUrl + 'crudjs/get_posts')
        .then(response => response.json())
        .then(result => {
            if (!result.status || !result.data.length) {
                rows.innerHTML = '<tr><td colspan="6">Belum ada data.</td></tr>';
                return;
            }

            rows.innerHTML = result.data.map(post => `
                <tr>
                    <td>${post.id}</td>
                    <td>${post.image_url ? `<img src="${post.image_url}" class="thumb" alt="${escapeHtml(post.title)}">` : '-'}</td>
                    <td><strong>${escapeHtml(post.title)}</strong></td>
                    <td>${escapeHtml(post.author)}</td>
                    <td><div class="preview">${escapeHtml(post.article)}</div></td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-warning" type="button" onclick="openEditModal(${post.id})">Edit</button>
                            <button class="btn btn-danger" type="button" onclick="deletePost(${post.id})">Delete</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        })
        .catch(() => {
            rows.innerHTML = '<tr><td colspan="6">Gagal memuat data.</td></tr>';
        });
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Post';
    form.reset();
    document.getElementById('postId').value = '';
    document.getElementById('currentImage').innerHTML = '';
    modal.classList.add('open');
}

function openEditModal(id) {
    fetch(baseUrl + 'crudjs/get_post/' + id)
        .then(response => response.json())
        .then(result => {
            if (!result.status) {
                showAlert(result.message || 'Data tidak ditemukan', 'error');
                return;
            }

            const post = result.data;
            document.getElementById('modalTitle').textContent = 'Edit Post';
            document.getElementById('postId').value = post.id;
            document.getElementById('title').value = post.title;
            document.getElementById('author').value = post.author;
            document.getElementById('article').value = post.article;
            document.getElementById('image').value = '';
            document.getElementById('currentImage').innerHTML = post.image_url
                ? `<img src="${post.image_url}" class="current-image" alt="Current image"><small style="display:block;margin-top:4px;color:#64748b;">Kosongkan gambar jika tidak ingin mengganti.</small>`
                : '';
            modal.classList.add('open');
        })
        .catch(() => showAlert('Gagal mengambil data', 'error'));
}

function deletePost(id) {
    if (!confirm('Yakin ingin menghapus data ini?')) {
        return;
    }

    fetch(baseUrl + 'crudjs/delete/' + id, { method: 'POST' })
        .then(response => response.json())
        .then(result => {
            showAlert(result.message || 'Data dihapus', result.status ? 'success' : 'error');
            loadPosts();
        })
        .catch(() => showAlert('Gagal menghapus data', 'error'));
}

function closeModal() {
    modal.classList.remove('open');
}

function showAlert(message, type) {
    alertBox.textContent = message;
    alertBox.className = 'alert ' + type;
    alertBox.style.display = 'block';

    setTimeout(() => {
        alertBox.style.display = 'none';
    }, 3500);
}

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, function (char) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char];
    });
}
</script>

<?php $this->load->view('footer'); ?>
