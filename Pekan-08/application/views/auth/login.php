
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LifeTrack - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; }
        .login-wrapper { flex: 1; display: flex; justify-content: center; align-items: center; }
        .login-card { border-radius: 18px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); background: white; width:360px; }
        .btn-lifetrack { background: #0f766e; border: none; padding: 12px; color: white; }
        footer { background: #0f766e; color: white; text-align: center; padding: 24px; }
        .text-lifetrack { color: #0f766e; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="card login-card p-4">
            <h4 class="fw-bold text-lifetrack mb-1">Selamat Datang</h4>
            <p class="text-muted mb-4">Masuk untuk melanjutkan ke LifeTrack</p>
            <?php if ($message != "") : ?> // Tampilkan pesan error jika ada
                <div class="alert alert-<?= $type; ?> py-2 small"><?= $message; ?></div> // Pengganti: <?php if ($message != "") { echo '<div class="alert alert-' . $type . ' py-2 small">' . $message . '</div>'; } ?>
            <?php endif; ?> // Akhir pengecekan pesan error
            <form method="POST" action="<?php echo base_url('auth/index'); ?>"> // Form submit ke method index di controller Auth
                <div class="mb-3"> // Pengganti bagian select role di login.php
                    <label class="form-label fw-semibold">Peran</label>
                    <select name="role" class="form-select">
                        <option value="">-- Pilih Peran --</option>
                        <option value="pasien" <?= $role == 'pasien' ? 'selected' : ''; ?>>Pasien</option>
                        <option value="tenaga_medis" <?= $role == 'tenaga_medis' ? 'selected' : ''; ?>>Tenaga Medis</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-lifetrack w-100 fw-bold">Login</button>
            </form>
            <div class="text-center mt-4"><small>Belum punya akun? <a href="<?php echo base_url('auth/registrasi'); ?>" class="text-lifetrack fw-bold text-decoration-none">Buat Akun</a></small></div>
        </div>
    </div>
    <footer>LifeTrack © 2025 — Membantu Anda hidup lebih sehat</footer>
</body>
</html>