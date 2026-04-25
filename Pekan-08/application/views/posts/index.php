<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) && $title !== '' ? html_escape($title) : 'Data Posts'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pekan06-theme.css'); ?>">
</head>
<body>
    <?php $image_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp'); ?>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="<?php echo base_url('index.php/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('index.php/posts/create'); ?>">Tambah Post</a></li>
                <li><a href="<?php echo base_url('index.php/auth/logout'); ?>">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-shell">
        <div class="table-box">
            <div class="top" style="text-align:left;">
                <h2><?php echo $title; ?></h2>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="message-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>

            <div class="table-wrapper">
                <table class="data-table">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                    <?php if (!empty($posts)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($posts as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo html_escape($row->judul); ?></td>
                                <td><?php echo nl2br(html_escape($row->deskripsi)); ?></td>
                                <td>
                                    <?php if (!empty($row->file)): ?>
                                        <?php
                                        $file_url = base_url('uploads/' . $row->file);
                                        $file_ext = strtolower(pathinfo($row->file, PATHINFO_EXTENSION));
                                        ?>
                                        <?php if (in_array($file_ext, $image_extensions)): ?>
                                            <div class="file-preview-card">
                                                <a href="<?php echo $file_url; ?>" target="_blank">
                                                    <img class="post-thumb" src="<?php echo $file_url; ?>" alt="<?php echo html_escape($row->judul); ?>">
                                                </a>
                                                <a class="file-name-link" href="<?php echo $file_url; ?>" target="_blank"><?php echo html_escape($row->file); ?></a>
                                            </div>
                                        <?php else: ?>
                                            <a class="file-name-link" href="<?php echo $file_url; ?>" target="_blank"><?php echo html_escape($row->file); ?></a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge">Tidak ada file</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions-inline">
                                        <a class="action-link" href="<?php echo base_url('index.php/posts/edit/' . $row->id); ?>">Edit</a>
                                        <a class="action-link" href="<?php echo base_url('index.php/posts/delete/' . $row->id); ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="table-empty" colspan="5">Belum ada data post.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <footer class="cyber-footer">
        <div class="footer-container">
            <div>
                <h3 class="footer-logo">CyberVault</h3>
            </div>
        </div>
    </footer>
</body>
</html>
