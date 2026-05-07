<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html_escape($title); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pekan09-theme.css'); ?>">
</head>
<body>
    <header class="hero">
        <div class="hero__inner">
            <div>
                <p class="eyebrow">Mencoba CodeIgniter 3 HMVC REST API</p>
                <h1>Manajemen Posts</h1>
                <p class="hero__text">Silahkan tambahkan atau edit data Laporan Anda.</p>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="panel">
            <div class="panel__head">
                <div>
                    <h2>Form Post</h2>
                    <p>Tambah atau edit data Laporan.</p>
                </div>
                <button class="ghost-button" id="resetButton" type="button">Reset Form</button>
            </div>

            <div id="messageBox" class="message" hidden></div>

            <form id="postForm">
                <input type="hidden" id="postId">
                <div class="form-grid">
                    <div class="field">
                        <label for="title">Title</label>
                        <input id="title" name="title" type="text" placeholder="Masukkan judul Laporan">
                    </div>
                    <div class="field">
                        <label for="file">Foto Post</label>
                        <input id="file" name="file" type="file" accept=".jpg,.jpeg,.png,.gif,.webp">
                    </div>
                    <div class="field field--full">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" placeholder="Masukkan isi Laporan"></textarea>
                    </div>
                    <div class="field field--full" id="currentFileField" hidden>
                        <label>Foto Saat Ini</label>
                        <div class="current-file-card" id="currentFileCard"></div>
                    </div>
                </div>
                <div class="actions">
                    <button class="primary-button" type="submit" id="submitButton">Simpan Post</button>
                </div>
            </form>
        </section>

        <section class="panel">
            <div class="panel__head">
                <div>
                    <h2>Daftar Posts</h2>
                </div>
                <button class="ghost-button" id="refreshButton" type="button">Refresh Data</button>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Foto</th>
                            <th>Content</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="postsTableBody">
                        <tr>
                            <td colspan="5" class="empty">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        const apiUrl = <?php echo json_encode($base_api_url); ?>;
        const form = document.getElementById('postForm');
        const postIdInput = document.getElementById('postId');
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const fileInput = document.getElementById('file');
        const submitButton = document.getElementById('submitButton');
        const resetButton = document.getElementById('resetButton');
        const refreshButton = document.getElementById('refreshButton');
        const tableBody = document.getElementById('postsTableBody');
        const messageBox = document.getElementById('messageBox');
        const currentFileField = document.getElementById('currentFileField');
        const currentFileCard = document.getElementById('currentFileCard');

        function showMessage(text, type) {
            messageBox.hidden = false;
            messageBox.textContent = text;
            messageBox.className = `message message--${type}`;
        }

        function clearMessage() {
            messageBox.hidden = true;
            messageBox.textContent = '';
            messageBox.className = 'message';
        }

        function resetForm() {
            form.reset();
            postIdInput.value = '';
            submitButton.textContent = 'Simpan Post';
            currentFileField.hidden = true;
            currentFileCard.innerHTML = '';
            clearMessage();
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, function(char) {
                return map[char];
            });
        }

        function renderFilePreview(post) {
            if (!post || !post.file_url) {
                currentFileField.hidden = true;
                currentFileCard.innerHTML = '';
                return;
            }

            currentFileField.hidden = false;
            currentFileCard.innerHTML = `
                ${post.is_image ? `<a href="${post.file_url}" target="_blank" rel="noopener"><img class="post-thumb post-thumb--large" src="${post.file_url}" alt="${escapeHtml(post.title)}"></a>` : ''}
                <a class="file-link" href="${post.file_url}" target="_blank" rel="noopener">${escapeHtml(post.file)}</a>
            `;
        }

        function renderTableFileCell(post) {
            if (!post.file_url) {
                return '<span class="empty-file">Tidak ada foto</span>';
            }

            if (post.is_image) {
                return `
                    <div class="file-preview-card">
                        <a href="${post.file_url}" target="_blank" rel="noopener">
                            <img class="post-thumb" src="${post.file_url}" alt="${escapeHtml(post.title)}">
                        </a>
                        <a class="file-link" href="${post.file_url}" target="_blank" rel="noopener">${escapeHtml(post.file)}</a>
                    </div>
                `;
            }

            return `<a class="file-link" href="${post.file_url}" target="_blank" rel="noopener">${escapeHtml(post.file)}</a>`;
        }

        async function loadPosts() {
            tableBody.innerHTML = '<tr><td colspan="5" class="empty">Memuat data...</td></tr>';

            try {
                const response = await fetch(apiUrl);
                const result = await response.json();

                if (!result.status) {
                    throw new Error(result.message || 'Gagal mengambil data');
                }

                const posts = result.data || [];

                if (!posts.length) {
                    tableBody.innerHTML = '<tr><td colspan="5" class="empty">Belum ada data posts.</td></tr>';
                    return;
                }

                tableBody.innerHTML = posts.map(function(post) {
                    return `
                        <tr>
                            <td>${post.id}</td>
                            <td>${escapeHtml(post.title)}</td>
                            <td>${renderTableFileCell(post)}</td>
                            <td>${escapeHtml(post.content)}</td>
                            <td>
                                <div class="row-actions">
                                    <button class="table-button" type="button" onclick="editPost(${post.id})">Edit</button>
                                    <button class="table-button table-button--danger" type="button" onclick="deletePost(${post.id})">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            } catch (error) {
                tableBody.innerHTML = `<tr><td colspan="5" class="empty">${escapeHtml(error.message)}</td></tr>`;
            }
        }

        async function submitPost(event) {
            event.preventDefault();
            clearMessage();

            const id = postIdInput.value.trim();
            const formData = new FormData();
            formData.append('title', titleInput.value.trim());
            formData.append('content', contentInput.value.trim());

            if (fileInput.files[0]) {
                formData.append('file', fileInput.files[0]);
            }

            let requestUrl = apiUrl;
            let requestMethod = 'POST';

            if (id) {
                formData.append('_method', 'PUT');
                requestUrl = `${apiUrl}/${id}`;
            }

            try {
                const response = await fetch(requestUrl, {
                    method: requestMethod,
                    body: formData
                });
                const result = await response.json();

                if (!response.ok || !result.status) {
                    throw new Error(result.message || 'Gagal menyimpan data');
                }

                showMessage(result.message, 'success');
                resetForm();
                await loadPosts();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        async function editPost(id) {
            clearMessage();

            try {
                const response = await fetch(`${apiUrl}/${id}`);
                const result = await response.json();

                if (!result.status) {
                    throw new Error(result.message || 'Gagal mengambil detail post');
                }

                postIdInput.value = result.data.id;
                titleInput.value = result.data.title;
                contentInput.value = result.data.content;
                renderFilePreview(result.data);
                submitButton.textContent = 'Update Post';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        async function deletePost(id) {
            clearMessage();

            if (!window.confirm('Yakin ingin menghapus post ini?')) {
                return;
            }

            try {
                const response = await fetch(`${apiUrl}/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (!result.status) {
                    throw new Error(result.message || 'Gagal menghapus data');
                }

                showMessage(result.message, 'success');
                if (postIdInput.value === String(id)) {
                    resetForm();
                }
                await loadPosts();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        form.addEventListener('submit', submitPost);
        resetButton.addEventListener('click', resetForm);
        refreshButton.addEventListener('click', loadPosts);

        window.editPost = editPost;
        window.deletePost = deletePost;

        loadPosts();
    </script>
</body>
</html>
