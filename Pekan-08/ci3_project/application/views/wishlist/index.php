<div class="container">

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;"><i class="fas fa-star" style="color:#f39c12;"></i> Wishlist Wisata Bandung</h2>
            <a href="<?php echo base_url('wishlist/tambah'); ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Destinasi
            </a>
        </div>

        <?php if (empty($wishlists)): ?>
            <div style="text-align:center; padding:50px; color:#aaa;">
                <i class="fas fa-compass" style="font-size:3.5rem; display:block; margin-bottom:14px;"></i>
                <p style="font-size:16px;">Wishlist masih kosong.</p>
                <a href="<?php echo base_url('wishlist/tambah'); ?>" class="btn btn-primary" style="margin-top:12px;">
                    Tambah destinasi pertama
                </a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach ($wishlists as $w): ?>
                <div class="wishlist-card">
                    <?php if ($w->gambar): ?>
                        <img src="<?php echo base_url('uploads/wishlist/' . $w->gambar); ?>"
                             alt="<?php echo htmlspecialchars($w->nama); ?>">
                    <?php else: ?>
                        <div class="no-img"><i class="fas fa-image"></i>&nbsp;Tidak ada gambar</div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h3><?php echo htmlspecialchars($w->nama); ?></h3>
                        <p><i class="fas fa-map-marker-alt" style="color:#e74c3c; width:16px;"></i>
                           <?php echo htmlspecialchars($w->lokasi); ?></p>
                        <p style="color:#666; margin-top:6px; font-size:13px; line-height:1.5;">
                            <?php echo htmlspecialchars(substr($w->deskripsi, 0, 100)) . (strlen($w->deskripsi) > 100 ? '...' : ''); ?>
                        </p>
                        <p class="harga"><i class="fas fa-ticket-alt"></i> Rp <?php echo number_format($w->harga, 0, ',', '.'); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo base_url('wishlist/edit/' . $w->id); ?>" class="btn btn-warning" style="font-size:13px; padding:7px 14px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?php echo base_url('wishlist/hapus/' . $w->id); ?>"
                           class="btn btn-danger" style="font-size:13px; padding:7px 14px;"
                           onclick="return confirm('Yakin ingin menghapus destinasi ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
