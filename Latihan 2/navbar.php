<?php if (!isset($_SESSION)) session_start(); ?>
<nav class="navbar">
    <div class="nav-brand">SmartParking Report</div>
    <div class="nav-links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Halo, <?= htmlspecialchars($_SESSION['nama']) ?></span>
            <a href="dashboard.php">Dashboard</a>
            <a href="laporan.php">Laporan</a>
            <a href="tambah_laporan.php">+ Tambah</a>
            <a href="login.php?logout=1" class="btn-logout">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>