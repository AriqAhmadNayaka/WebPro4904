<div class="container">
    <div class="card" style="max-width: 640px; margin: 0 auto;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
            <a href="<?php echo base_url('wishlist'); ?>" class="btn btn-secondary" style="padding:7px 12px;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 style="margin:0;"><i class="fas fa-plus-circle" style="color:#00b894;"></i> Tambah Destinasi Wisata</h2>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <!-- Satu form dengan tombol Simpan untuk CRUD + upload sekaligus -->
        <?php echo form_open_multipart('Wishlist/simpan'); ?>

            <div class="form-group">
                <label><i class="fas fa-map-pin"></i> Nama Tempat <span style="color:red;">*</span></label>
                <input type="text" name="nama" placeholder="Contoh: Kawah Putih" required value="<?php echo set_value('nama'); ?>">
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Deskripsi <span style="color:red;">*</span></label>
                <textarea name="deskripsi" placeholder="Deskripsikan tempat wisata ini..." required><?php echo set_value('deskripsi'); ?></textarea>
            </div>

            <div class="form-group">
                <label><i class="fas fa-location-arrow"></i> Lokasi <span style="color:red;">*</span></label>
                <input type="text" name="lokasi" placeholder="Contoh: Ciwidey, Kabupaten Bandung" required value="<?php echo set_value('lokasi'); ?>">
            </div>

            <div class="form-group">
                <label><i class="fas fa-ticket-alt"></i> Harga Tiket (Rp) <span style="color:red;">*</span></label>
                <input type="text" name="harga" placeholder="Contoh: 25000" required value="<?php echo set_value('harga'); ?>">
            </div>

            <div class="form-group">
                <label><i class="fas fa-image"></i> Foto Tempat <span style="color:red;">*</span></label>
                <input type="file" name="gambar" accept="image/jpeg,image/jpg,image/png,image/webp" required>
                <small style="color:#888; font-size:12px; display:block; margin-top:4px;">
                    Format: JPG, JPEG, PNG, WebP. Maks 2MB.
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
