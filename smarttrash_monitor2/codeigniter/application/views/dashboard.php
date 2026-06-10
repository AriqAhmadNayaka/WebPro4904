<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Air Quality Monitoring</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/cybervault-auth.css'); ?>">
    <style>
        .workspace { max-width: 1240px; margin: 32px auto; padding: 0 20px 40px; }
        .panel { background: rgba(8, 16, 31, 0.92); border: 1px solid rgba(0, 212, 255, 0.12); border-radius: 20px; padding: 24px; box-shadow: 0 20px 45px rgba(0,0,0,.2); }
        .hero-grid, .grid-2 { display: grid; gap: 20px; }
        .hero-grid { grid-template-columns: 1.3fr .8fr; margin-bottom: 24px; }
        .grid-2 { grid-template-columns: 2.5fr .8fr; }
        .kpi { font-size: 2rem; font-weight: 700; color: #55e6c1; }
        .toolbar-form, .stack { display: grid; gap: 14px; }
        .table-wrapper { overflow-x: auto; margin-top: 16px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 14px 12px; border-bottom: 1px solid rgba(255,255,255,.08); text-align: left; vertical-align: top; }
        .data-table th { color: #7edcff; }
        .action-row, .actions-inline { display: flex; gap: 10px; flex-wrap: wrap; }
        .chip { display: inline-block; padding: 6px 10px; border-radius: 999px; background: rgba(85, 230, 193, .14); color: #9ff9e1; font-size: .85rem; }
        .inline-note { color: #b8c7d9; margin-top: 8px; }
        textarea.input { min-height: 120px; resize: vertical; }
        @media (max-width: px) { .hero-grid, .grid-2 { grid-template-columns: 1fr; } .workspace { padding: 0 14px 32px; } }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">Smart Air Quality Monitoring</div>
            <ul class="nav-links">
                <li><a href="<?php echo site_url('dashboard'); ?>">Kategori</a></li>
                <li><a href="<?php echo site_url('auth/logout'); ?>">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="workspace">
        <div class="hero-grid">
            <section class="panel">
                <h1 style="margin-top:0;">Dashboard Smart Air Quality Monitoring</h1>
                <p>Selamat datang, <strong><?php echo html_escape($user['name']); ?></strong>. Setelah login, halaman utama sekarang langsung fokus ke kategori seperti organik, non-organik, dan B3.</p>
                <div class="action-row">
                    <span class="chip"><?php echo count($categories); ?> kategori</span>
                    <span class="chip"><?php echo (int) $items_count; ?> barang katalog</span>
                    <span class="chip"><?php echo count($users); ?> pengguna</span>
                </div>
            </section>
            <section class="panel">
                <p style="margin:0 0 6px;">User aktif</p>
                <div class="kpi"><?php echo html_escape($user['email']); ?></div>
                <p class="inline-note">Masuk sebagai <strong><?php echo strtoupper(html_escape($user['role'])); ?></strong>. Pilih kategori untuk masuk ke katalog barang di dalamnya.</p>
            </section>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error banner"><?php echo html_escape($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success banner"><?php echo html_escape($success); ?></div>
        <?php endif; ?>

        <div class="grid-2">
            <section class="panel">
                <h2 style="margin-top:0;"><?php echo !empty($edit_category) ? 'Edit Kategori' : 'Tambah Kategori'; ?></h2>
                <form action="" method="POST" class="toolbar-form">
                    <input type="hidden" name="action" value="save_category">
                    <input type="hidden" name="category_id" value="<?php echo !empty($edit_category) ? (int) $edit_category['id'] : 0; ?>">

                    <div class="input-group">
                        <label>Lokasi</label>
                        <input type="text" name="category_name" class="input" required value="<?php echo html_escape(!empty($edit_category['category_name']) ? $edit_category['category_name'] : ''); ?>" placeholder="Contoh: Organik">
                    </div>

                    <div class="input-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="input" placeholder="Deskripsi kategori barang..."><?php echo html_escape(!empty($edit_category['description']) ? $edit_category['description'] : ''); ?></textarea>
                    </div>

                    <div class="action-row">
                        <button type="submit" class="btn-login"><?php echo !empty($edit_category) ? 'Update Kategori' : 'Simpan Kategori'; ?></button>
                        <?php if (!empty($edit_category)): ?>
                            <a href="<?php echo site_url('dashboard'); ?>" class="social-item">Batal Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="panel">
                <h2 style="margin-top:0;">Daftar Kategori</h2>
                <p class="inline-note">Klik tombol katalog untuk masuk ke CRUD barang di dalam kategori tersebut.</p>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Lokasi</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="3">Belum ada kategori.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?php echo html_escape($category['category_name']); ?></td>
                                        <td><?php echo html_escape($category['description'] ?: '-'); ?></td>
                                        <td>
                                            <div class="actions-inline">
                                                <a href="<?php echo site_url('dashboard?edit=' . $category['id']); ?>" class="social-item">Edit</a>
                                                <a href="<?php echo site_url('dashboard/kategori/' . $category['id']); ?>" class="social-item">Buka Katalog</a>
                                                <a href="<?php echo site_url('dashboard/delete_category/' . $category['id']); ?>" class="social-item" onclick="return confirm('Hapus kategori ini beserta seluruh katalog barangnya?')">Hapus</a>
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
