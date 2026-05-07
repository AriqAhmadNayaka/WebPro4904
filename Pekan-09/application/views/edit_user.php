<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - CyberVault</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/cybervault-auth.css'); ?>">
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo site_url('auth/logout'); ?>">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-shell">
        <div class="form-box form-box--page">
            <div class="top">
                <h2>Edit User</h2>
                <p>Perbarui data akun pengguna dari dashboard admin.</p>
            </div>

            <?php if (!empty($success)): ?>
                <div class="success"><?php echo html_escape($success); ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo html_escape($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="input" required value="<?php echo html_escape($user['name']); ?>">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" class="input" required value="<?php echo html_escape($user['email']); ?>">
                </div>
                <div class="input-group">
                    <label>Role</label>
                    <select name="role" class="input">
                        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="input" placeholder="Kosongkan jika tidak diubah">
                </div>
                <div class="input-group">
                    <label>Foto Profil</label>
                    <input type="file" name="photo" class="input file-input" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="actions-inline">
                    <button type="submit" class="btn-login">Simpan Perubahan</button>
                    <a href="<?php echo site_url('dashboard'); ?>" class="action-link">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
