<?php $this->load->view('posts/header'); ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
        <div>
            <h2><?php echo html_escape($title); ?></h2>
            <p class="muted" style="margin-top: 6px;">Menampilkan data dari database `cybervault` tabel `datauser`.</p>
        </div>
    </div>

    <?php if (empty($posts)): ?>
        <div class="text-center" style="padding: 40px 20px; border: 1px dashed rgba(0, 212, 255, 0.3); border-radius: 12px;">
            <h3 style="margin-bottom: 10px; color: #00d4ff;">Belum ada data user</h3>
            <p class="muted">Silakan tambahkan user baru dari halaman ini.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Identity</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img
                                    src="<?php echo base_url('../Pekan-6/img/' . rawurlencode($post->foto)); ?>"
                                    class="post-image"
                                    alt="<?php echo html_escape($post->nama); ?>"
                                >
                            </td>
                            <td style="font-weight: 600; color: #fff;"><?php echo html_escape($post->nama); ?></td>
                            <td class="muted"><?php echo html_escape($post->email); ?></td>
                            <td class="text-center">
                                <div class="action-row" style="justify-content: center;">
                                    <a href="<?php echo site_url('posts/show/' . $post->id); ?>" class="btn btn-info">Detail</a>
                                    <a href="<?php echo site_url('posts/edit/' . $post->id); ?>" class="btn btn-warning">Edit</a>
                                    <a href="<?php echo site_url('posts/delete/' . $post->id); ?>" class="btn btn-danger" onclick="return confirm('Hapus data ini?');">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php $this->load->view('posts/footer'); ?>
