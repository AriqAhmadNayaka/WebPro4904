<?php
$is_edit = !empty($menu);
$action = site_url('menu/simpan');
?>
<section style="padding: 28px 0 40px;">
    <div class="card" style="max-width:700px; margin:0 auto;">
        <h2 style="margin-top:0;"><?php echo $is_edit ? 'Edit Menu' : 'Tambah Menu'; ?></h2>
        <p>Satu tombol <strong>Simpan</strong> di bawah akan menjalankan proses simpan data dan upload file dalam satu kali submit.</p>

        <?php if (!empty($upload_error)): ?>
            <div class="flash flash-error"><?php echo html_escape($upload_error); ?></div>
        <?php endif; ?>

        <?php echo form_open_multipart($action); ?>
            <input type="hidden" name="id" value="<?php echo $is_edit ? (int) $menu->id : 0; ?>">

            <div class="form-group">
                <label for="nama">Nama Menu</label>
                <input type="text" id="nama" name="nama" value="<?php echo set_value('nama', $is_edit ? $menu->nama : ''); ?>" required>
                <?php echo form_error('nama', '<div class="error-text">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required><?php echo set_value('deskripsi', $is_edit ? $menu->deskripsi : ''); ?></textarea>
                <?php echo form_error('deskripsi', '<div class="error-text">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" step="0.1" min="0" max="5" id="rating" name="rating" value="<?php echo set_value('rating', $is_edit ? $menu->rating : ''); ?>" required>
                <?php echo form_error('rating', '<div class="error-text">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="gambar">Upload Gambar <?php echo $is_edit ? '(opsional saat edit)' : '(wajib)'; ?></label>
                <?php if ($is_edit && !empty($menu->gambar) && file_exists(FCPATH . 'uploads/menu/' . $menu->gambar)): ?>
                    <img class="preview" src="<?php echo base_url('uploads/menu/' . $menu->gambar); ?>" alt="<?php echo html_escape($menu->nama); ?>">
                <?php endif; ?>
                <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.gif,.webp" <?php echo $is_edit ? '' : 'required'; ?>>
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit">Simpan</button>
                <a class="btn btn-outline" href="<?php echo site_url('dashboard'); ?>">Kembali</a>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>
