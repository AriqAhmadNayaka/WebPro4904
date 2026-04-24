<?php $this->load->view('posts/header'); ?>

<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&lt; Back to List</a>

<div class="card">
    <h2>Edit User</h2>
    <p class="muted" style="margin-top: 8px; margin-bottom: 24px;">Password tidak diubah di halaman ini, sama seperti alur di `Pekan-6`.</p>

    <?php echo form_open_multipart(site_url('posts/update/' . $post->id)); ?>

        <div class="form-group">
            <label for="nama">Full Name</label>
            <input type="text" name="nama" id="nama" placeholder="Edit nama user" required value="<?php echo set_value('nama', $post->nama); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Edit email user" required value="<?php echo set_value('email', $post->email); ?>">
        </div>

        <div class="form-group">
            <label>Foto Saat Ini</label>
            <?php if (!empty($post->foto)): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?php echo base_url('../Pekan-6/img/' . rawurlencode($post->foto)); ?>" class="post-image" alt="Foto User Saat Ini">
                </div>
            <?php else: ?>
                <p class="muted" style="font-style: italic; margin-bottom: 10px;">Belum ada foto</p>
            <?php endif; ?>

            <label for="foto">Ganti Foto</label>
            <input type="file" name="foto" id="foto" accept="image/jpeg,image/jpg,image/png">
            <small class="muted" style="display: block; margin-top: 5px;">Format yang diizinkan: JPG, JPEG, PNG. Maksimal 2MB.</small>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
            <a href="<?php echo site_url('posts'); ?>" class="btn btn-danger">Batal</a>
        </div>

    <?php echo form_close(); ?>
</div>

<?php $this->load->view('posts/footer'); ?>
