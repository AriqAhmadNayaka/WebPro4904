<?php
// Sidebar navigasi ditampilkan di halaman dashboard dan peserta.
?>
<aside class="sidebar">
    <h2 class="sidebar-title">InkluSkill</h2>
    <a class="<?php echo current_url() === site_url('dashboard') ? 'active' : ''; ?>" href="<?php echo site_url('dashboard'); ?>">
        <i class="ri-dashboard-line"></i> Dashboard
    </a>
    <a class="<?php echo current_url() === site_url('peserta') ? 'active' : ''; ?>" href="<?php echo site_url('peserta'); ?>">
        <i class="ri-user-3-line"></i> Kelola Peserta
    </a>
    <a href="<?php echo site_url('logout'); ?>" class="logout-link">
        <i class="ri-logout-box-line"></i> Logout
    </a>
</aside>
