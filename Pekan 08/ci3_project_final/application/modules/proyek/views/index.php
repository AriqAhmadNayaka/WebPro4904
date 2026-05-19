<?php $this->load->view('template/header', $data); ?>

<!-- Tombol Tambah -->
<div class="d-flex justify-content-end mb-3">
    <a href="<?= site_url('proyek/tambah') ?>" id="btn-tambah-proyek" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Proyek
    </a>
</div>

<!-- Tabel Proyek (setara tabel di pekan8/index.php) -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-folder-open"></i> Daftar Proyek
        </h5>
        <span class="badge" style="background:var(--primary-light);color:var(--primary)">
            <?= count($daftar_proyek) ?> data
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table datatable" id="tabel-proyek">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Proyek</th>
                        <th>Deskripsi</th>
                        <th>File</th>
                        <th width="140" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($daftar_proyek)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                            Belum ada data proyek
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($daftar_proyek as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="fw-500"><?= htmlspecialchars($row['nama_proyek']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td>
                            <?php if (!empty($row['nama_file'])): ?>
                            <a href="<?= base_url('uploads/' . $row['nama_file']) ?>"
                               target="_blank"
                               class="badge"
                               style="background:#e0f2fe;color:#0369a1;font-size:12px;text-decoration:none">
                                <i class="fas fa-file me-1"></i>
                                <?= htmlspecialchars($row['nama_file']) ?>
                            </a>
                            <?php else: ?>
                            <span class="text-muted small">Tidak ada file</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <!-- Edit — setara: href="?edit=id" di pekan8/index.php -->
                            <a href="<?= site_url('proyek/edit/' . $row['id']) ?>"
                               id="btn-edit-<?= $row['id'] ?>"
                               class="btn btn-warning btn-sm"
                               data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Hapus — setara: href="?hapus=id" di pekan8/index.php -->
                            <a href="<?= site_url('proyek/hapus/' . $row['id']) ?>"
                               id="btn-hapus-<?= $row['id'] ?>"
                               class="btn btn-danger btn-sm btn-delete"
                               data-bs-toggle="tooltip" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
