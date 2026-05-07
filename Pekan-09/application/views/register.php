<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - CyberVault</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/cybervault-auth.css'); ?>">
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="<?php echo site_url('auth/login'); ?>">Masuk</a></li>
            </ul>
        </nav>
    </header>

    <div class="login-wrapper">
        <div class="image-box image-box--register"></div>

        <div class="form-box">
            <div class="top">
                <div class="brand-mark">CV</div>
                <h2>Registrasi Akun</h2>
                <p>Bergabunglah dengan CyberVault untuk pengalaman keamanan digital yang lebih baik.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo html_escape($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="name" class="input" placeholder="Nama lengkap" required value="<?php echo html_escape(set_value('name')); ?>">
                </div>
                <div class="input-group">
                    <input type="email" name="email" class="input" placeholder="Alamat email" required value="<?php echo html_escape(set_value('email')); ?>">
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="input" placeholder="Password (Min. 6 karakter)" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" class="input" placeholder="Konfirmasi password" required>
                </div>
                <button type="submit" class="btn-login">Daftar Sekarang</button>
            </form>

            <p class="helper-text">
                Sudah memiliki akun?
                <a href="<?php echo site_url('auth/login'); ?>">Masuk di sini</a>
            </p>
        </div>
    </div>

    <footer class="cyber-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3 class="footer-logo">CyberVault</h3>
                <p>Melindungi data dan privasi Anda melalui edukasi keamanan siber modern.</p>
            </div>
            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li>Bandung, Indonesia</li>
                    <li>support@cybervault.id</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Connect</h4>
                <div class="social-links">
                    <a href="#" class="social-item">Instagram</a>
                    <a href="#" class="social-item">GitHub</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 CyberVault Project - Kelompok 10 SIKC.</p>
        </div>
    </footer>
</body>
</html>
