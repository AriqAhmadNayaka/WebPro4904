<?php if (!isset($_SESSION)) session_start(); ?>
<nav>
    <span class="brand">SmartTraffic Cam</span>
    <div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Halo, <?= htmlspecialchars($_SESSION['nama']) ?></span>
            <a href="monitoring.php">Monitoring</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>