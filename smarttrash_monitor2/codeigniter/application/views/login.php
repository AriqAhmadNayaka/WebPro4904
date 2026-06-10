<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartAir Quality</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/cybervault-auth.css'); ?>">
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">SmartAir Quality</div>
            <ul class="nav-links">
                <li><a href="<?php echo site_url('auth/register'); ?>">Daftar Akun</a></li>
            </ul>
        </nav>
    </header>

    <div class="login-wrapper">
        <div class="image-box"></div>

        <div class="form-box">
            <div class="top">
                <div class="brand-mark">CV</div>
                <h2>SmartAir Quality</h2>
                <p>Masuk ke akun Anda untuk mengakses dashboard Monitoring Udara cerdas.</p>
            </div>

            <?php if (!empty($success)): ?>
                <div class="success"><?php echo html_escape($success); ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo html_escape($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" name="email" class="input" placeholder="Alamat email" required autocomplete="email">
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="input" placeholder="Password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn-login">Masuk Sekarang</button>
            </form>

            <p class="helper-text">
                Belum punya akses?
                <a href="<?php echo site_url('auth/register'); ?>">Daftar sekarang</a>
            </p>

        </div>
    </div>

    <footer class="cyber-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3 class="footer-logo">SmartAir Quality</h3>
                <p>Platform Monitoring Kualitas udara, untuk memantau tingkat polusi dan kualitas udara di perkotaan cerdas.</p>
            </div>
            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li>Bandung, Indonesia</li>
                    <li>support@cybervault.id</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Social</h4>
                <div class="social-links">
                    <a href="#" class="social-item">Instagram</a>
                    <a href="#" class="social-item">GitHub</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 SmartAir Quality Monitor . All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
