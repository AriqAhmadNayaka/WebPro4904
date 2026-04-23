<?php $page_title = 'Dashboard Proyek';
$this->load->view('templates/header'); ?>

<div class="container">
    <div class="dashboard-header">
        <h2>Laporan Kendala</h2>
        <div class="user-info">
            <span>Halo, <strong><?= htmlspecialchars($username) ?></strong></span>
            <a href="<?= site_url('auth/logout') ?>" class="logout-btn">Logout</a>
        </div>
    </div>

    <hr>

    <!-- Form Create / Update -->
    <form action="<?= site_url('proyek/simpan') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <input type="hidden" name="file_lama" value="<?= $edit_data['nama_file'] ?>">

        <input type="text"
            name="nama_proyek"
            placeholder="Nama Proyek"
            value="<?= htmlspecialchars($edit_data['nama_proyek']) ?>"
            required>

        <textarea name="deskripsi"
            placeholder="Deskripsi Proyek"><?= htmlspecialchars($edit_data['deskripsi']) ?></textarea>

        <input type="file" name="berkas">

        <button type="submit" name="simpan">
            <?= $edit_data['id'] ? 'UPDATE DATA' : 'SIMPAN DATA & UPLOAD' ?>
        </button>
    </form>

    <hr>

    <!-- Tabel Daftar Proyek -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kendala</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($proyek)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Belum ada data laporan.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1;
                foreach ($proyek as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_proyek']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td>
                            <?php if ($row['nama_file']): ?>
                                <a href="<?= base_url('uploads/' . $row['nama_file']) ?>" target="_blank">
                                    <?= htmlspecialchars($row['nama_file']) ?>
                                </a>
                            <?php else: ?>
                                <em>—</em>
                            <?php endif; ?>
                        </td>
                        <td class="aksi">
                            <a href="<?= site_url('proyek?edit=' . $row['id']) ?>" class="btn-edit">Edit</a>
                            <a href="<?= site_url('proyek/hapus/' . $row['id']) ?>"
                                class="btn-hapus"
                                onclick="return confirm('Yakin ingin menghapus proyek ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>

</html>