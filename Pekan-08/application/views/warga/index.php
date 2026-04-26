<!-- Card pertama ini buat form tambah atau edit data warga -->
<div class="card">
    <h2><?php echo $edit_data ? 'Edit Data Warga' : 'Tambah Data Warga'; ?></h2>
    <p>Gunakan tombol <strong>Simpan</strong> untuk menjalankan proses tambah atau update sekaligus upload file dalam satu kali submit.</p>

    <!-- Satu form ini dipakai buat tambah dan edit, bedanya dilihat dari ada id atau nggak -->
    <form action="<?php echo site_url('warga/save'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $edit_data ? $edit_data->id : ''; ?>">
        <label>Nama</label>
        <input type="text" name="nama" value="<?php echo $edit_data ? html_escape($edit_data->nama) : ''; ?>" required>
        <label>Alamat</label>
        <textarea name="alamat" required><?php echo $edit_data ? html_escape($edit_data->alamat) : ''; ?></textarea>
        <label>No HP</label>
        <input type="text" name="nohp" value="<?php echo $edit_data ? html_escape($edit_data->nohp) : ''; ?>" required>
        <label>File <?php echo $edit_data ? '(kosongkan jika tidak diganti)' : ''; ?></label>
        <input type="file" name="file" <?php echo $edit_data ? '' : 'required'; ?>>
        <?php if ($edit_data && $edit_data->file): ?>
            <p>File saat ini:
                <a href="<?php echo base_url('uploads/' . $edit_data->file); ?>" target="_blank"><?php echo html_escape($edit_data->file); ?></a>
            </p>
        <?php endif; ?>
        <button type="submit" class="btn">Simpan</button>
        <?php if ($edit_data): ?>
            <a class="btn btn-secondary" href="<?php echo site_url('warga'); ?>">Batal</a>
        <?php endif; ?>
    </form>
</div>

<!-- Card kedua ini buat nampilin semua data warga yang sudah masuk -->
<div class="card">
    <h2>Daftar Warga</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($warga)): ?>
                <tr><td colspan="6">Belum ada data warga.</td></tr>
            <?php else: ?>
                <!-- Data diloop satu-satu lalu ditampilin jadi baris tabel -->
                <?php $no = 1; foreach ($warga as $row): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo html_escape($row->nama); ?></td>
                        <td><?php echo html_escape($row->alamat); ?></td>
                        <td><?php echo html_escape($row->nohp); ?></td>
                        <td>
                            <?php if ($row->file): ?>
                                <a href="<?php echo base_url('uploads/' . $row->file); ?>" target="_blank">Lihat File</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <a class="btn btn-secondary" href="<?php echo site_url('warga/index/' . $row->id); ?>">Edit</a>
                            <a class="btn btn-danger" href="<?php echo site_url('warga/delete/' . $row->id); ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
