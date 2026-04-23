<div class="container">

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <!-- Sambutan -->
    <div class="card" style="background: linear-gradient(135deg, #0f4c3a, #00b894); color: white; margin-bottom: 24px;">
        <h2 style="color: white; font-size: 22px; margin-bottom: 6px;">
            <i class="fas fa-sun"></i> Selamat datang, <?php echo htmlspecialchars($user); ?>!
        </h2>
        <p style="opacity: 0.85; font-size: 14px;">Kelola wishlist destinasi wisata Bandung kamu dari sini.</p>
    </div>

    <!-- Statistik -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🗺️</div>
            <div class="stat-number"><?php echo $total_wishlist; ?></div>
            <div class="stat-label">Total Wishlist</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📍</div>
            <div class="stat-number"><?php echo count($wishlists); ?></div>
            <div class="stat-label">Destinasi Tersimpan</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🕐</div>
            <div class="stat-number"><?php echo date('H:i'); ?></div>
            <div class="stat-label"><?php echo date('d M Y'); ?></div>
        </div>
    </div>

    <!-- Preview wishlist terbaru -->
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h2 style="margin:0;">Destinasi Wishlist</h2>
            <a href="<?php echo base_url('wishlist/tambah'); ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Destinasi
            </a>
        </div>

        <?php if (empty($wishlists)): ?>
            <div style="text-align:center; padding:40px; color:#aaa;">
                <i class="fas fa-map-signs" style="font-size:3rem; margin-bottom:12px; display:block;"></i>
                Belum ada wishlist. <a href="<?php echo base_url('wishlist/tambah'); ?>" style="color:#00b894;">Tambah sekarang!</a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach (array_slice($wishlists, 0, 3) as $w): ?>
                <div class="wishlist-card">
                    <?php if ($w->gambar): ?>
                        <img src="<?php echo base_url('uploads/wishlist/' . $w->gambar); ?>" alt="<?php echo htmlspecialchars($w->nama); ?>">
                    <?php else: ?>
                        <div class="no-img"><i class="fas fa-image"></i>&nbsp;Tidak ada gambar</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h3><?php echo htmlspecialchars($w->nama); ?></h3>
                        <p><i class="fas fa-map-marker-alt" style="color:#e74c3c;"></i> <?php echo htmlspecialchars($w->lokasi); ?></p>
                        <p class="harga">Rp <?php echo number_format($w->harga, 0, ',', '.'); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($wishlists) > 3): ?>
                <div style="text-align:center; margin-top:16px;">
                    <a href="<?php echo base_url('wishlist'); ?>" class="btn btn-primary">
                        Lihat semua <?php echo count($wishlists); ?> destinasi →
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
