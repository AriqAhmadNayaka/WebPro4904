<!-- Card ini buat sambutan singkat setelah user berhasil login -->
<div class="card">
    <h2>Dashboard</h2>
    <p>Selamat datang, <strong><?php echo html_escape($username); ?></strong>. Halaman ini hanya bisa diakses setelah login berhasil.</p>
</div>

<!-- Bagian bawah ini isi ringkasan sama tombol menuju menu utama -->
<div class="grid">
    <div class="card">
        <div>Total Data Warga</div>
        <div class="stat"><?php echo $total_warga; ?></div>
    </div>
    <div class="card">
        <div>Menu Utama</div>
        <p>Kelola data warga dan upload file.</p>
        <a class="btn" href="<?php echo site_url('warga'); ?>">Buka Data Warga</a>
    </div>
</div>
