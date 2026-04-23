<div class="container">
    <div class="card" style="max-width: 640px; margin: 0 auto;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
            <a href="<?php echo base_url('wishlist'); ?>" class="btn btn-secondary" style="padding:7px 12px;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 style="margin:0;"><i class="fas fa-edit" style="color:#f39c12;"></i> Edit Destinasi Wisata</h2>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <!-- Form edit — satu tombol Simpan untuk update data + upload foto sekaligus -->
        <?php echo form_open_multipart('Wishlist/update/' . $wishlist->id); ?>

            <div class="form-group">
                <label><i class="fas fa-map-pin"></i> Nama Tempat <span style="color:red;">*</span></label>
                <input type="text" name="nama" required
                       value="<?php echo set_value('nama', htmlspecialchars($wishlist->nama)); ?>">
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Deskripsi <span style="color:red;">*</span></label>
                <textarea name="deskripsi" required><?php echo set_value('deskripsi', htmlspecialchars($wishlist->deskripsi)); ?></textarea>
            </div>

            <div class="form-group">
                <label><i class="fas fa-location-arrow"></i> Lokasi <span style="color:red;">*</span></label>
                <input type="text" name="lokasi" required
                       value="<?php echo set_value('lokasi', htmlspecialchars($wishlist->lokasi)); ?>">
            </div>

            <div class="form-group">
                <label><i class="fas fa-ticket-alt"></i> Harga Tiket (Rp) <span style="color:red;">*</span></label>
                <input type="text" name="harga" required
                       value="<?php echo set_value('harga', htmlspecialchars($wishlist->harga)); ?>">
            </div>

            <div class="form-group">
                <label><i class="fas fa-image"></i> Ganti Foto (opsional)</label>
                <?php if ($wishlist->gambar): ?>
                    <div class="img-preview" style="margin-bottom:10px;">
                        <p style="font-size:12px; color:#888; margin-bottom:4px;">Foto saat ini:</p>
                        <img src="<?php echo base_url('uploads/wishlist/' . $wishlist->gambar); ?>"
                             alt="foto" style="width:150px; border-radius:8px; border:1px solid #ddd;">
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar" accept="image/jpeg,image/jpg,image/png,image/webp">
                <small style="color:#888; font-size:12px; display:block; margin-top:4px;">
                    Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, WebP. Maks 2MB.
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?php echo base_url('wishlist'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

        <?php echo form_close(); ?>
    </div>
</div>
