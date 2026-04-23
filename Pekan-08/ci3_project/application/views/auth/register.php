<?php
// Memanggil partial head untuk kebutuhan tampilan halaman register.
$this->load->view('partials/head', array('title' => $title));
?>
<main class="auth-body">
    <section class="auth-container">
        <div class="auth-copy">
            <p class="auth-badge">Registrasi</p>
            <h1>Buat akun baru</h1>
            <p class="auth-subtitle">Akun ini dipakai untuk masuk ke dashboard utama aplikasi.</p>
        </div>

        <?php
        // Menampilkan pesan validasi atau status proses register.
        $this->load->view('partials/alerts');
        ?>

        <?php
        // Form register mengirim data akun baru ke controller Auth.
        echo form_open('register', array('class' => 'auth-form'));
        ?>
            <label>Nama Lengkap</label>
            <input type="text" name="username" value="<?php echo set_value('username'); ?>" placeholder="Masukkan nama lengkap" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Masukkan email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 5 karakter" required>

            <button type="submit" class="btn-primary">Daftar</button>
        <?php echo form_close(); ?>

        <p class="auth-switch">Sudah punya akun? <a href="<?php echo site_url('login'); ?>">Login</a></p>
    </section>
</main>
<?php
// Memanggil partial footer untuk menutup struktur halaman.
$this->load->view('partials/footer');
?>
