<section class="hero">
    <div class="hero-box">
        <h1 style="margin-top:0;">Dashboard EcoTaste</h1>
        <p>Halaman ini hanya bisa diakses setelah login berhasil, sesuai kebutuhan soal tugas besar.</p>
        <div class="stats">Total menu tersimpan: <strong><?php echo (int) $total_menu; ?></strong></div>
    </div>
</section>

<section style="padding: 12px 0 32px;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:18px;">
        <h2 style="margin:0;">Data Menu Kuliner</h2>
        <a class="btn" href="<?php echo site_url('menu/tambah'); ?>">Tambah Data</a>
    </div>

    <?php if (empty($menus)): ?>
        <div class="card">
            <p style="margin:0;">Belum ada data menu. Silakan tambahkan data baru.</p>
        </div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($menus as $item): ?>
                <div class="card">
                    <?php if (!empty($item->gambar) && file_exists(FCPATH . 'uploads/menu/' . $item->gambar)): ?>
                        <img class="menu-image" src="<?php echo base_url('uploads/menu/' . $item->gambar); ?>" alt="<?php echo html_escape($item->nama); ?>">
                    <?php else: ?>
                        <div class="menu-image" style="display:grid; place-items:center;">Tidak ada gambar</div>
                    <?php endif; ?>
                    <h3><?php echo html_escape($item->nama); ?></h3>
                    <p><?php echo nl2br(html_escape($item->deskripsi)); ?></p>
                    <p><strong>Rating:</strong> <?php echo html_escape($item->rating); ?></p>
                    <div class="menu-actions">
                        <a class="btn btn-outline" href="<?php echo site_url('menu/edit/' . $item->id); ?>">Edit</a>
                        <a class="btn btn-danger" href="<?php echo site_url('menu/hapus/' . $item->id); ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
