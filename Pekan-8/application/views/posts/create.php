<?php $this->load->view('posts/header'); ?>

<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&lt; Back to List</a>

<div class="card">
    <h2>Tambah User Baru</h2>
    <p class="muted" style="margin-top: 8px; margin-bottom: 24px;">Form ini mengikuti struktur data dari `Pekan-6`.</p>

    <?php echo form_open_multipart(site_url('posts/store')); ?>

        <div class="form-group">
            <label for="nama">Full Name</label>
            <input type="text" name="nama" id="nama" placeholder="Masukkan nama user" required value="<?php echo set_value('nama'); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="name@cyber.com" required value="<?php echo set_value('email'); ?>">
        </div>

        <div class="form-group">
            <label for="password">Security Key</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password user" required>
        </div>

        <div class="form-group">
            <label for="foto">Profile Image</label>
            <input type="file" name="foto" id="foto" accept="image/jpeg,image/jpg,image/png">
            <small class="muted" style="display: block; margin-top: 5px;">Format yang diizinkan: JPG, JPEG, PNG. Maksimal 2MB.</small>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">Tambah User</button>
            <a href="<?php echo site_url('posts'); ?>" class="btn btn-danger">Batal</a>
        </div>

    <?php echo form_close(); ?>
</div>

<?php $this->load->view('posts/footer'); ?>
