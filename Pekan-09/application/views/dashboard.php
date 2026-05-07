<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberVault - Dashboard Management</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/cybervault-auth.css'); ?>">
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo site_url('posts'); ?>">Posts AJAX</a></li>
                <li><a href="<?php echo site_url('auth/logout'); ?>">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-shell">
        <div class="dashboard-box">
            <div class="welcome-card">
                <h2>Halo, <?php echo html_escape($user['name']); ?>!</h2>
                <p>Role Anda: <strong class="welcome-role"><?php echo strtoupper(html_escape($user['role'])); ?></strong></p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error banner"><?php echo html_escape($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success banner"><?php echo html_escape($success); ?></div>
            <?php endif; ?>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3><?php echo $user['role'] === 'admin' ? 'Tambah Anggota Baru' : 'Update Foto Profil Anda'; ?></h3>
                    <p><?php echo $user['role'] === 'admin' ? 'Tambahkan user baru dan unggah foto profil langsung dari dashboard.' : 'Gunakan form ini untuk memperbarui foto profil keamanan Anda di database.'; ?></p>

                    <form action="" method="POST" enctype="multipart/form-data" class="toolbar-form">
                        <?php if ($user['role'] === 'admin'): ?>
                            <div class="input-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="input" required>
                            </div>
                            <div class="input-group">
                                <label>Email</label>
                                <input type="email" name="email" class="input" required>
                            </div>
                            <div class="input-group">
                                <label>Password</label>
                                <input type="password" name="password" class="input" required>
                            </div>
                        <?php else: ?>
                            <div class="dashboard-note">
                                <p>Data akun Anda tersimpan aman. Yang dapat diperbarui dari form ini adalah foto profil.</p>
                            </div>
                        <?php endif; ?>

                        <div class="input-group">
                            <label>Pilih File Foto</label>
                            <input type="file" name="photo" class="input file-input" accept=".jpg,.jpeg,.png,.webp" required>
                        </div>

                        <button type="submit" name="submit_action" value="1" class="toolbar-button">
                            <?php echo $user['role'] === 'admin' ? 'Simpan & Upload User' : 'Update Foto Saya'; ?>
                        </button>
                    </form>
                </div>

                <div class="dashboard-card">
                    <h3>Ringkasan Sistem</h3>
                    <p>Total user: <strong><?php echo count($users); ?></strong></p>
                    <p>Total posts: <strong><?php echo (int) $posts_count; ?></strong></p>
                    <p>Email aktif: <strong><?php echo html_escape($user['email']); ?></strong></p>
                    <div class="action-row">
                        <a class="action-link" href="<?php echo site_url('posts'); ?>">Buka CRUD Posts</a>
                        <a class="action-link" href="<?php echo site_url('api/posts'); ?>" target="_blank">Lihat API JSON</a>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="table-empty">Belum ada data user.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $row): ?>
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <?php if (!empty($row['photo'])): ?>
                                                <img src="<?php echo base_url('uploads/profiles/' . $row['photo']); ?>" alt="<?php echo html_escape($row['name']); ?>" class="user-img">
                                            <?php else: ?>
                                                <span><?php echo strtoupper(substr($row['name'], 0, 1)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo html_escape($row['name']); ?></td>
                                    <td><?php echo html_escape($row['email']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $row['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                            <?php echo strtoupper(html_escape($row['role'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <div class="actions-inline">
                                                <a href="<?php echo site_url('dashboard/edit_user/' . $row['id']); ?>" class="action-link">Edit</a>
                                                <?php if ($row['email'] !== 'admin@cybervault.com'): ?>
                                                    <a href="<?php echo site_url('dashboard/delete_user/' . $row['id']); ?>" class="action-link action-link-danger" onclick="return confirm('Hapus user?')">Hapus</a>
                                                <?php else: ?>
                                                    <span class="readonly-note">Admin Utama</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="readonly-note">Read Only</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="cyber-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3 class="footer-logo">CyberVault</h3>
                <p>Platform edukasi keamanan siber terpercaya untuk melindungi data dan privasi Anda.</p>
            </div>
            <div class="footer-section">
                <h4>Contact Us</h4>
                <ul>
                    <li>Bandung, Indonesia</li>
                    <li>support@cybervault.id</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#" class="social-item">Instagram</a>
                    <a href="#" class="social-item">GitHub</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 CyberVault Project. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
