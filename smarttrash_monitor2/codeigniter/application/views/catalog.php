<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartTrash - Katalog <?php echo html_escape($category['category_name']); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/cybervault-auth.css'); ?>">
    <style>
        .workspace { max-width: 1260px; margin: 32px auto; padding: 0 20px 40px; }
        .panel { background: rgba(8, 16, 31, 0.92); border: 1px solid rgba(0, 212, 255, 0.12); border-radius: 20px; padding: 24px; box-shadow: 0 20px 45px rgba(0,0,0,.2); }
        .hero { display:flex; justify-content:space-between; gap:16px; align-items:flex-start; margin-bottom:24px; flex-wrap:wrap; }
        .grid-2 { display:grid; grid-template-columns: .95fr 1.25fr; gap:20px; }
        .toolbar-form { display:grid; gap:14px; }
        .table-wrapper { overflow-x:auto; }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th, .data-table td { padding:14px 12px; border-bottom:1px solid rgba(255,255,255,.08); text-align:left; vertical-align:top; }
        .data-table th { color:#7edcff; }
        .thumb { width:80px; height:80px; border-radius:12px; object-fit:cover; border:1px solid rgba(255,255,255,.12); background:#07111d; }
        .muted { color:#b8c7d9; }
        .action-row, .actions-inline { display:flex; gap:10px; flex-wrap:wrap; }
        textarea.input { min-height:120px; resize:vertical; }
        .badge { padding:6px 10px; border-radius:999px; font-size:.82rem; }
        .badge-ok { background:rgba(67, 233, 123, .14); color:#abffc2; }
        .badge-clean { background:rgba(255, 215, 84, .14); color:#ffe58b; }
        .badge-residu { background:rgba(255, 107, 129, .16); color:#ffc0ca; }
        @media (max-width: 960px) { .grid-2 { grid-template-columns:1fr; } .workspace { padding: 0 14px 32px; } }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">SmartTrash</div>
            <ul class="nav-links">
                <li><a href="<?php echo site_url('dashboard'); ?>">Kembali ke Kategori</a></li>
                <li><a href="<?php echo site_url('auth/logout'); ?>">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="workspace">
        <div class="hero panel">
            <div>
                <h1 style="margin:0;"><?php echo html_escape($category['category_name']); ?></h1>
                <p class="muted"><?php echo html_escape($category['description'] ?: 'Kategori ini belum memiliki deskripsi.'); ?></p>
            </div>
            <div class="action-row">
                <span class="social-item"><?php echo count($items); ?> barang</span>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error banner"><?php echo html_escape($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success banner"><?php echo html_escape($success); ?></div>
        <?php endif; ?>

        <div class="grid-2">
            <section class="panel">
                <h2 style="margin-top:0;"><?php echo !empty($edit_item) ? 'Edit Barang Katalog' : 'Tambah Barang Katalog'; ?></h2>
                <form action="" method="POST" enctype="multipart/form-data" class="toolbar-form">
                    <input type="hidden" name="action" value="save_item">
                    <input type="hidden" name="item_id" value="<?php echo !empty($edit_item) ? (int) $edit_item['id'] : 0; ?>">

                    <div class="input-group">
                        <label>Lokasi</label>
                        <input type="text" name="item_name" class="input" required value="<?php echo html_escape(!empty($edit_item['item_name']) ? $edit_item['item_name'] : ''); ?>" placeholder="Contoh: Botol Plastik">
                    </div>

                    <div class="input-group">
                        <label>ISPU</label>
                        <input type="text" name="item_type" class="input" required value="<?php echo html_escape(!empty($edit_item['item_type']) ? $edit_item['item_type'] : ''); ?>" placeholder="Contoh: Plastik PET">
                    </div>

                    <div class="input-group">
                        <label>Kondisi</label>
                        <?php $selected_condition = !empty($edit_item['item_condition']) ? $edit_item['item_condition'] : 'Baik'; ?>
                        <select name="item_condition" class="input">
                            <option value="Baik" <?php echo $selected_condition === 'Baik' ? 'selected' : ''; ?>>Baik</option>
                            <option value="Sedang" <?php echo $selected_condition === 'Sedang' ? 'selected' : ''; ?>>Sedang</option>
                            <option value="Tidak Sehat" <?php echo $selected_condition === 'Tidak Sehat' ? 'Tidak Sehat' : ''; ?>>Tidak Sehat</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Foto Contoh Pencemaran Udara</label>
                        <input type="file" name="example_photo" class="input file-input" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <?php if (!empty($edit_item['example_photo'])): ?>
                        <div class="input-group">
                            <label>Foto Saat Ini</label>
                            <img src="<?php echo base_url('uploads/items/' . $edit_item['example_photo']); ?>" alt="Foto Barang" class="thumb">
                        </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <label>Catatan</label>
                        <textarea name="notes" class="input" placeholder="Cara pemilahan, contoh barang, atau catatan lain..."><?php echo html_escape(!empty($edit_item['notes']) ? $edit_item['notes'] : ''); ?></textarea>
                    </div>

                    <div class="action-row">
                        <button type="submit" class="btn-login"><?php echo !empty($edit_item) ? 'Update Barang' : 'Simpan Barang'; ?></button>
                        <?php if (!empty($edit_item)): ?>
                            <a href="<?php echo site_url('dashboard/kategori/' . $category['id']); ?>" class="social-item">Batal Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="panel">
                <h2 style="margin-top:0;">Daftar Laporan Kualitas Udara</h2>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Lokasi</th>
                                <th>ISPU</th>
                                <th>Kondisi</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="6">Belum ada barang pada kategori ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($item['example_photo'])): ?>
                                                <img src="<?php echo base_url('uploads/items/' . $item['example_photo']); ?>" alt="<?php echo html_escape($item['item_name']); ?>" class="thumb">
                                            <?php else: ?>
                                                <span class="muted">Belum ada foto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo html_escape($item['item_name']); ?></td>
                                        <td><?php echo html_escape($item['item_type']); ?></td>
                                        <td>
                                            <?php
                                                $class_name = 'badge-ok';
                                                if ($item['item_condition'] === 'Sedang') {
                                                    $class_name = 'badge-clean';
                                                } elseif ($item['item_condition'] === 'Tidak Sehat') {
                                                    $class_name = 'badge-Tidak Sehat';
                                                }
                                            ?>
                                            <span class="badge <?php echo $class_name; ?>"><?php echo html_escape($item['item_condition']); ?></span>
                                        </td>
                                        <td><?php echo html_escape($item['notes'] ?: '-'); ?></td>
                                        <td>
                                            <div class="actions-inline">
                                                <a href="<?php echo site_url('dashboard/kategori/' . $category['id'] . '?edit=' . $item['id']); ?>" class="social-item">Edit</a>
                                                <a href="<?php echo site_url('dashboard/delete_item/' . $item['id']); ?>" class="social-item" onclick="return confirm('Hapus barang katalog ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
