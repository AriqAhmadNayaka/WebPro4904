<?php
// Memanggil partial head untuk elemen <head> dan asset utama.
$this->load->view('partials/head', array('title' => $title));
?>
<main class="auth-body">
    <section class="auth-container">
        <div class="auth-copy">
            <p class="auth-badge">CodeIgniter 3</p>
            <h1>Masuk ke InkluSkill</h1>
            <p class="auth-subtitle">Dashboard hanya bisa diakses setelah login berhasil, sesuai instruksi modul.</p>
        </div>

        <?php
        // Menampilkan notifikasi error atau sukses jika ada.
        $this->load->view('partials/alerts');
        ?>

        <?php
        // Form login mengirim data ke route login.
        echo form_open('login', array('class' => 'auth-form'));
        ?>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="Masukkan email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>

            <button type="submit" class="btn-primary">Login</button>
        <?php echo form_close(); ?>

        <p class="auth-switch">Belum punya akun? <a href="<?php echo site_url('register'); ?>">Daftar</a></p>
    </section>
</main>
<?php
// Memanggil partial footer untuk script JS penutup.
$this->load->view('partials/footer');
?>
