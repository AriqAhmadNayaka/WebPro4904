<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) && $title !== '' ? html_escape($title) : 'Form Post'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pekan06-theme.css'); ?>">
</head>
<body>
    <?php $image_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp'); ?>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="<?php echo base_url('index.php/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('index.php/posts'); ?>">Kembali ke Posts</a></li>
                <li><a href="<?php echo base_url('index.php/auth/logout'); ?>">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-shell">
        <div class="form-box" style="max-width:760px; margin:0 auto;">
            <div class="top">
                <h2><?php echo $title; ?></h2>
            </div>

            <?php echo validation_errors('<div class="message-error">', '</div>'); ?>

            <?php if (!empty($upload_error)): ?>
                <div class="message-error"><?php echo $upload_error; ?></div>
            <?php endif; ?>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Judul</label>
                    <input class="input" type="text" name="judul" value="<?php echo set_value('judul', isset($post->judul) ? $post->judul : ''); ?>" placeholder="Masukkan judul post">
                </div>

                <div class="input-group">
                    <label>Deskripsi</label>
                    <textarea class="textarea" name="deskripsi" rows="5" cols="40" placeholder="Masukkan deskripsi post"><?php echo set_value('deskripsi', isset($post->deskripsi) ? $post->deskripsi : ''); ?></textarea>
                </div>

                <div class="input-group">
                    <label>File</label>
                    <input class="file-input" type="file" name="file">
                    <?php if (!empty($post->file)): ?>
                        <?php
                        $current_file_url = base_url('uploads/' . $post->file);
                        $current_file_ext = strtolower(pathinfo($post->file, PATHINFO_EXTENSION));
                        ?>
                        <?php if (in_array($current_file_ext, $image_extensions)): ?>
                            <div class="current-file-box">
                                <a href="<?php echo $current_file_url; ?>" target="_blank">
                                    <img class="post-thumb large" src="<?php echo $current_file_url; ?>" alt="<?php echo html_escape($post->judul); ?>">
                                </a>
                            </div>
                        <?php endif; ?>
                        <p class="file-note">File saat ini: <a class="file-name-link" href="<?php echo $current_file_url; ?>" target="_blank"><?php echo html_escape($post->file); ?></a></p>
                    <?php endif; ?>
                </div>

                <button class="toolbar-button" type="submit">Simpan</button>
            </form>
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
