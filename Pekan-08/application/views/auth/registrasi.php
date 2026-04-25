
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LifeTrack - Buat Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; }
        .signin-wrapper { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px 0; }
        .signin-card { border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); background: white; width:360px; }
        .btn-lifetrack { background: #0f766e; border: none; padding: 10px; color: white; }
        .text-lifetrack { color: #0f766e; }
        footer { background: #0f766e; color: white; text-align: center; padding: 24px; }
    </style>
</head>
<body>
<div class="signin-wrapper">
    <div class="card signin-card p-4">
        <h4 class="fw-bold text-lifetrack mb-1">Buat Akun</h4>
        <p class="text-muted mb-4">Daftar untuk mulai menggunakan LifeTrack</p>

        <?php if ($message != "") : ?> // Tampilkan pesan error jika ada
            <div class="alert alert-<?= $type; ?> py-2 small"><?= $message; ?></div> // Pengganti: <?php if ($message != "") { echo '<div class="alert alert-' . $type . ' py-2 small">' . $message . '</div>'; } ?>
        <?php endif; ?> // Akhir pengecekan pesan error

        <form method="POST" action="<?php echo base_url('auth/registrasi'); ?>"> // Form submit ke method registrasi di controller Auth
            <div class="mb-3">
                <label class="form-label fw-semibold">Daftar sebagai</label>
                <select name="role" class="form-select">
                    <option value="">Pilih peran</option>
                    <option value="pasien" <?= $role == "pasien" ? "selected" : ""; ?>>Pasien</option> // Pengganti: <option value="pasien" <?= $role == "pasien" ? "selected" : ""; ?>>Pasien</option>
                    <option value="tenaga_medis" <?= $role == "tenaga_medis" ? "selected" : ""; ?>>Tenaga Medis</option> // Pengganti: <option value="tenaga_medis" <?= $role == "tenaga_medis" ? "selected" : ""; ?>>Tenaga Medis</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-lifetrack w-100 fw-bold">Buat Akun</button>
        </form>
        <div class="text-center mt-3"><small>Sudah punya akun? <a href="<?php echo base_url('auth'); ?>" class="text-lifetrack fw-bold text-decoration-none">Login</a></small></div>
    </div>
</div>
<footer>LifeTrack © 2025 — Membantu Anda hidup lebih sehat</footer>
</body>
</html>