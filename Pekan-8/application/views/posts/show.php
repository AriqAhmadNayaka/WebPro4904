<?php $this->load->view('posts/header'); ?>

<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&lt; Back to List</a>

<div class="card">
    <h2 style="margin-bottom: 6px;"><?php echo html_escape($post->nama); ?></h2>
    <p class="muted" style="margin-bottom: 24px;">Detail user dari tabel `datauser`.</p>

    <div class="detail-grid">
        <div class="text-center">
            <img src="<?php echo base_url('../Pekan-6/img/' . rawurlencode($post->foto)); ?>" class="post-detail-image" alt="<?php echo html_escape($post->nama); ?>">
        </div>
        <div class="detail-box">
            <h3>Informasi User</h3>
            <p><strong>ID:</strong> <?php echo (int) $post->id; ?></p>
            <p><strong>Nama:</strong> <?php echo html_escape($post->nama); ?></p>
            <p><strong>Email:</strong> <?php echo html_escape($post->email); ?></p>
            <p><strong>Password:</strong> Tersimpan dalam bentuk terenkripsi.</p>
        </div>
    </div>

    <div style="display: flex; gap: 10px; margin-top: 30px;">
        <a href="<?php echo site_url('posts/edit/' . $post->id); ?>" class="btn btn-warning">Edit User</a>
        <a href="<?php echo site_url('posts/delete/' . $post->id); ?>" class="btn btn-danger" onclick="return confirm('Hapus data ini?');">Delete User</a>
    </div>
</div>

<?php $this->load->view('posts/footer'); ?>
