<?php // Header dipanggil dulu supaya tampilan atas, CSS, dan navigasi sudah siap sebelum isi halaman muncul. ?>
<?php $this->load->view('templates/header'); ?>

<!-- Card ini menjadi kotak utama untuk menampilkan daftar semua post. -->
<div class="card">
    <!-- Bagian atas ini berisi judul halaman dan tombol untuk masuk ke form tambah post. -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>All Posts</h2>
        <a href="<?php echo site_url('posts/create'); ?>" class="btn btn-success">+ Create New Post</a>
    </div>

    <!-- Kalau data post masih kosong, user dikasih pesan supaya tidak bingung lihat halaman kosong. -->
    <?php if (empty($posts)): ?>
        <div class="text-center" style="padding: 40px;">
            <p style="font-size: 18px; color: #666;">No posts found. Create your first post!</p>
            <a href="<?php echo site_url('posts/create'); ?>" class="btn btn-primary" style="margin-top: 20px;">Create Post</a>
        </div>
    <?php else: ?>
        <!-- Kalau datanya ada, semua post ditampilkan dalam tabel supaya rapi dan gampang dibaca. -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 100px;">Image</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th style="width: 250px;">Article Preview</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop ini memutar data post satu per satu lalu membuat satu baris tabel untuk tiap post. -->
                <?php foreach($posts as $post): ?>
                    <tr>
                        <td><?php echo $post->id; ?></td>
                        <td>
                            <!-- Bagian ini mengecek apakah post punya gambar, kalau tidak ada ditulis No image saja. -->
                            <?php if($post->image_url): ?>
                                <img src="<?php echo $post->image_url; ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="post-image">
                            <?php else: ?>
                                <span style="color: #999;">No image</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($post->title ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($post->author ?? 'Anonymous'); ?></td>
                        <td>
                            <!-- Artikel dipotong pendek saja supaya tabel tidak jadi terlalu panjang ke bawah. -->
                            <div class="article-preview">
                                <?php 
                                $article = $post->article ?? '';
                                echo htmlspecialchars(substr($article, 0, 100)); 
                                echo strlen($article) > 100 ? '...' : ''; 
                                ?>
                            </div>
                        </td>
                        <td>
                            <!-- Tombol aksi ini untuk melihat detail, mengedit, atau menghapus post yang dipilih. -->
                            <div class="actions">
                                <a href="<?php echo site_url('posts/show/' . $post->id); ?>" class="btn btn-info">View</a>
                                <a href="<?php echo site_url('posts/edit/' . $post->id); ?>" class="btn btn-warning">Edit</a>
                                <a href="<?php echo site_url('posts/delete/' . $post->id); ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php // Footer dipanggil terakhir supaya penutup halaman tetap konsisten di semua view. ?>
<?php $this->load->view('templates/footer'); ?>