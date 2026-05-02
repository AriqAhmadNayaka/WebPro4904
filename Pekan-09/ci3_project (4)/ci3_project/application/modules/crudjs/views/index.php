<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD AJAX Posts</title>
    <style>
        body { background: #f4f6f8; color: #243041; font-family: Arial, Helvetica, sans-serif; margin: 0; }
        .container { max-width: 1120px; margin: 0 auto; padding: 24px 20px; }
        .card { background: #fff; border: 1px solid #e1e7ef; border-radius: 8px; padding: 20px; }
        .toolbar { align-items: center; display: flex; justify-content: space-between; margin-bottom: 16px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #e1e7ef; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #eef3f7; }
        input, textarea { border: 1px solid #cbd5df; border-radius: 6px; font: inherit; padding: 9px; width: 100%; }
        textarea { min-height: 90px; resize: vertical; }
        label { display: block; font-weight: 700; margin: 10px 0 5px; }
        .btn { border: 0; border-radius: 6px; color: #fff; cursor: pointer; display: inline-block; font-weight: 700; margin: 3px; padding: 9px 12px; text-decoration: none; }
        .primary { background: #2f6f9f; }
        .success { background: #2c7a4b; }
        .warning { background: #c47f13; }
        .danger { background: #b63a35; }
        .grid { display: grid; gap: 18px; grid-template-columns: 360px 1fr; }
        .post-image { border-radius: 6px; height: 64px; object-fit: cover; width: 88px; }
        .message { margin: 10px 0; min-height: 22px; }
        @media (max-width: 860px) { .grid { grid-template-columns: 1fr; } table { display: block; overflow-x: auto; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="toolbar">
            <h1>CRUD AJAX Posts</h1>
            <a class="btn primary" href="<?php echo base_url('posts'); ?>">Posts HMVC</a>
        </div>

        <div class="grid">
            <section class="card">
                <h2 id="formTitle">Tambah Post</h2>
                <form id="postForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" required>
                    <label for="author">Author</label>
                    <input type="text" name="author" id="author" required>
                    <label for="article">Article</label>
                    <textarea name="article" id="article" required></textarea>
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif">
                    <div class="message" id="message"></div>
                    <button class="btn success" type="submit">Simpan</button>
                    <button class="btn danger" type="button" id="resetBtn">Reset</button>
                </form>
            </section>

            <section class="card">
                <h2>Data Posts</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Article</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="postRows"></tbody>
                </table>
            </section>
        </div>
    </div>

    <script>
        const baseUrl = '<?php echo base_url(); ?>';
        const form = document.getElementById('postForm');
        const rows = document.getElementById('postRows');
        const message = document.getElementById('message');
        const resetBtn = document.getElementById('resetBtn');
        const formTitle = document.getElementById('formTitle');

        function escapeHtml(value) {
            return String(value || '').replace(/[&<>"']/g, function (char) {
                return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[char];
            });
        }

        function resetForm() {
            form.reset();
            document.getElementById('id').value = '';
            formTitle.textContent = 'Tambah Post';
        }

        function loadPosts() {
            fetch(baseUrl + 'crudjs/list')
                .then(response => response.json())
                .then(result => {
                    rows.innerHTML = '';
                    result.data.forEach(post => {
                        rows.innerHTML += `
                            <tr>
                                <td>${post.id}</td>
                                <td>${post.image_url ? `<img class="post-image" src="${post.image_url}" alt="">` : '-'}</td>
                                <td>${escapeHtml(post.title)}</td>
                                <td>${escapeHtml(post.author)}</td>
                                <td>${escapeHtml(post.article).slice(0, 120)}</td>
                                <td>
                                    <button class="btn warning" onclick="editPost(${post.id})">Edit</button>
                                    <button class="btn danger" onclick="deletePost(${post.id})">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        function editPost(id) {
            fetch(baseUrl + 'crudjs/get/' + id)
                .then(response => response.json())
                .then(result => {
                    const post = result.data;
                    document.getElementById('id').value = post.id;
                    document.getElementById('title').value = post.title;
                    document.getElementById('author').value = post.author;
                    document.getElementById('article').value = post.article;
                    formTitle.textContent = 'Edit Post';
                });
        }

        function deletePost(id) {
            if (!confirm('Hapus post ini?')) {
                return;
            }

            fetch(baseUrl + 'crudjs/delete/' + id, { method: 'POST' })
                .then(response => response.json())
                .then(result => {
                    message.textContent = result.message;
                    loadPosts();
                });
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const id = document.getElementById('id').value;
            const endpoint = id ? 'crudjs/update/' + id : 'crudjs/store';

            fetch(baseUrl + endpoint, {
                method: 'POST',
                body: new FormData(form)
            })
                .then(response => response.json())
                .then(result => {
                    message.textContent = result.message;
                    if (result.status) {
                        resetForm();
                        loadPosts();
                    }
                });
        });

        resetBtn.addEventListener('click', resetForm);
        loadPosts();
    </script>
</body>
</html>
