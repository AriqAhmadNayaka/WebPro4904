<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) && $title !== '' ? html_escape($title) : 'Dashboard'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pekan06-theme.css'); ?>">
</head>
<body>
    <?php
    $safe_title = isset($title) && $title !== '' ? $title : 'Dashboard';
    $safe_username = isset($username) && $username !== '' ? $username : '-';
    $safe_nama = isset($nama) && $nama !== '' ? $nama : $safe_username;
    ?>

    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="<?php echo base_url('index.php/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('index.php/posts'); ?>">Kelola Posts</a></li>
                <li><a href="<?php echo base_url('index.php/auth/logout'); ?>">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-shell">
        <div class="dashboard-box">
            <div class="top" style="text-align:left;">
                <h2><?php echo html_escape($safe_title); ?></h2>
                <p>Selamat datang, <?php echo html_escape($safe_nama); ?>.</p>
            </div>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Area Terproteksi</h3>
                    <p>Halaman ini hanya bisa diakses setelah login berhasil.</p>
                    <div class="action-row">
                        <a class="action-link" href="<?php echo base_url('index.php/posts'); ?>">Buka Data Posts</a>
                        <a class="action-link" href="<?php echo base_url('index.php/auth/logout'); ?>">Logout</a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <h3>Info Login</h3>
                    <p><span class="badge">User Aktif</span></p>
                    <p>Username: <?php echo html_escape($safe_username); ?></p>
                    <p>Nama: <?php echo html_escape($safe_nama); ?></p>
                </div>
            </div>
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
