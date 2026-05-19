<?php $this->load->view('template/header', $data); ?>

<!-- Form Create / Update (setara form di pekan8/index.php) -->
<div class="card" style="max-width:700px;margin:0 auto;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-<?= empty($proyek['id']) ? 'plus' : 'edit' ?>"></i>
            <?= empty($proyek['id']) ? 'Tambah Proyek Baru' : 'Edit Proyek' ?>
        </h5>
    </div>
    <div class="card-body">

        <form action="<?= $form_action ?>" method="post" enctype="multipart/form-data" id="form-proyek">

            <!-- Hidden ID & file lama — setara input hidden di pekan8/index.php -->
            <input type="hidden" name="file_lama" value="<?= htmlspecialchars($proyek['nama_file'] ?? '') ?>">

            <!-- Nama Proyek -->
            <div class="mb-3">
                <label class="form-label" for="nama_proyek">
                    <i class="fas fa-tag me-1 text-muted"></i> Nama Proyek <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="nama_proyek"
                       name="nama_proyek"
                       class="form-control"
                       placeholder="Masukkan nama proyek"
                       value="<?= htmlspecialchars($proyek['nama_proyek'] ?? '') ?>"
                       required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label class="form-label" for="deskripsi">
                    <i class="fas fa-align-left me-1 text-muted"></i> Deskripsi
                </label>
                <textarea id="deskripsi"
                          name="deskripsi"
                          class="form-control"
                          rows="4"
                          placeholder="Deskripsi proyek..."><?= htmlspecialchars($proyek['deskripsi'] ?? '') ?></textarea>
            </div>

            <!-- Upload File — setara input file di pekan8/index.php -->
            <div class="mb-4">
                <label class="form-label" for="berkas">
                    <i class="fas fa-paperclip me-1 text-muted"></i> Upload File
                </label>

                <?php if (!empty($proyek['nama_file'])): ?>
                <div class="mb-2 p-2 rounded" style="background:#f8fafc;border:1px dashed #cbd5e1">
                    <small class="text-muted">File saat ini:</small><br>
                    <a href="<?= base_url('uploads/' . $proyek['nama_file']) ?>" target="_blank"
                       style="color:var(--primary);font-size:13px">
                        <i class="fas fa-file me-1"></i><?= htmlspecialchars($proyek['nama_file']) ?>
                    </a>
                    <small class="text-muted d-block mt-1">Upload file baru untuk mengganti</small>
                </div>
                <?php endif; ?>

                <input type="file"
                       id="berkas"
                       name="berkas"
                       class="form-control"
                       <?= empty($proyek['id']) ? '' : '' ?>>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex gap-2">
                <button type="submit" id="btn-simpan" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    <?= empty($proyek['id']) ? 'SIMPAN DATA' : 'PERBARUI DATA' ?>
                </button>
                <a href="<?= site_url('proyek') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

        </form>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
