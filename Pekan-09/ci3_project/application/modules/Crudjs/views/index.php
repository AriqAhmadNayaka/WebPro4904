<?php $this->load->view('crudjs/header'); // Memuat bagian header, CSS, dan judul halaman CRUD AJAX. ?>

<!-- Container utama untuk isi halaman CRUD AJAX. -->
<div class="container">
    <!-- Tempat menampilkan pesan sukses atau error dari proses AJAX. -->
    <div id="alert" class="alert"></div>

    <!-- Card pembungkus tombol aksi dan tabel data post. -->
    <div class="card">
        <!-- Tombol untuk membuka modal tambah post. -->
        <button class="btn btn-primary" onclick="openModal()">Tambah Post</button>
        <!-- Tombol untuk memuat ulang data dari server menggunakan AJAX. -->
        <button class="btn btn-secondary" onclick="loadRecords()">Refresh Data</button>

        <!-- Indikator loading saat data sedang diambil dari server. -->
        <div id="loading" class="loading" style="display:none;">Loading...</div>

        <!-- Tabel untuk menampilkan data post hasil response AJAX. -->
        <table class="table" id="postsTable">
            <!-- Bagian kepala tabel. -->
            <thead>
                <!-- Baris judul kolom tabel. -->
                <tr>
                    <!-- Kolom ID post. -->
                    <th>ID</th>
                    <!-- Kolom gambar post. -->
                    <th>Image</th>
                    <!-- Kolom judul post. -->
                    <th>Title</th>
                    <!-- Kolom penulis post. -->
                    <th>Author</th>
                    <!-- Kolom isi artikel post. -->
                    <th>Article</th>
                    <!-- Kolom tanggal dibuat. -->
                    <th>Created</th>
                    <!-- Kolom tombol aksi edit dan delete. -->
                    <th>Actions</th>
                </tr>
            </thead>
            <!-- Bagian body tabel akan diisi otomatis oleh JavaScript AJAX. -->
            <tbody id="postsBody"></tbody>
        </table>
    </div>
</div>

<!-- Modal/form pop-up untuk tambah dan edit post. -->
<div id="postModal" class="modal">
    <!-- Kotak isi modal. -->
    <div class="modal-content">
        <!-- Tombol untuk menutup modal. -->
        <span class="close" onclick="closeModal()">&times;</span>
        <!-- Judul modal yang berubah antara Tambah Post dan Edit Post. -->
        <h2 id="modalTitle">Tambah Post</h2>
        <!-- Form post dengan enctype multipart agar bisa upload gambar. -->
        <form id="postForm" enctype="multipart/form-data">
            <!-- Input hidden untuk menyimpan ID saat proses edit. -->
            <input type="hidden" id="postId" name="id">
            <!-- Grup input judul post. -->
            <div class="form-group">
                <!-- Label input title. -->
                <label for="title">Title</label>
                <!-- Input teks untuk judul post. -->
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <!-- Grup input author. -->
            <div class="form-group">
                <!-- Label input author. -->
                <label for="author">Author</label>
                <!-- Input teks untuk nama penulis post. -->
                <input type="text" id="author" name="author" class="form-control" required>
            </div>
            <!-- Grup input article. -->
            <div class="form-group">
                <!-- Label textarea article. -->
                <label for="article">Article</label>
                <!-- Textarea untuk isi artikel post. -->
                <textarea id="article" name="article" class="form-control" required></textarea>
            </div>
            <!-- Grup input image. -->
            <div class="form-group">
                <!-- Label input image. -->
                <label for="image">Image</label>
                <!-- Input file untuk memilih gambar dari komputer. -->
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                <!-- Tempat menampilkan gambar saat sedang edit post. -->
                <div id="currentImage" style="margin-top:10px;"></div>
            </div>
            <!-- Tombol submit untuk menyimpan tambah/edit data. -->
            <button type="submit" class="btn btn-success">Simpan</button>
            <!-- Tombol untuk membatalkan dan menutup modal. -->
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
        </form>
    </div>
</div>

<?php $this->load->view('crudjs/footer'); // Memuat JavaScript AJAX dan penutup HTML. ?>
