<?php require_once __DIR__ . '/auth.php'; ?>
<header class="site-header">
    <div class="container nav-wrap">
        <div>
            <p class="eyebrow">Smart City Kabupaten Bandung</p>
            <h1 class="brand-title">SmartParking Report</h1>
        </div>
        <nav class="nav-links">
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="laporan.php">Data Laporan</a>
                <a href="tambah_laporan.php">Tambah Laporan</a>
                <span class="welcome-text">Halo, <?= htmlspecialchars(current_user_name()); ?></span>
                <a class="btn btn-outline" href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a class="btn btn-outline" href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
