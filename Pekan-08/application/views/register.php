<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pekan06-theme.css'); ?>">
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="<?php echo base_url('index.php/auth'); ?>">Login</a></li>
                <li><a href="<?php echo base_url('index.php/auth/register'); ?>">Register</a></li>
            </ul>
        </nav>
    </header>

    <div class="login-wrapper">
        <div class="image-box"></div>

        <div class="form-box">
            <div class="top">
                <h2>Registrasi Akun</h2>
                <p>Bergabunglah dengan CyberVault untuk mengelola data post melalui arsitektur MVC CodeIgniter 3.</p>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="message-error"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <?php echo validation_errors('<div class="message-error">', '</div>'); ?>

            <form action="<?php echo base_url('index.php/auth/register'); ?>" method="post">
                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input class="input" type="text" name="nama" value="<?php echo set_value('nama'); ?>" placeholder="Masukkan nama lengkap">
                </div>

                <div class="input-group">
                    <label>Username</label>
                    <input class="input" type="text" name="username" value="<?php echo set_value('username'); ?>" placeholder="Masukkan username">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input class="input" type="password" name="password" placeholder="Minimal 6 karakter">
                </div>

                <div class="input-group">
                    <label>Konfirmasi Password</label>
                    <input class="input" type="password" name="confirm_password" placeholder="Ulangi password">
                </div>

                <button class="btn-login" type="submit">Daftar Sekarang</button>
            </form>

            <p class="helper-text">Sudah punya akun? <a href="<?php echo base_url('index.php/auth'); ?>">Masuk di sini</a></p>
        </div>
    </div>

    <footer class="cyber-footer">
        <div class="footer-container">
            <div>
                <h3 class="footer-logo">CyberVault</h3>
            </div>
        </div>
    </footer>
</body>
</html>
