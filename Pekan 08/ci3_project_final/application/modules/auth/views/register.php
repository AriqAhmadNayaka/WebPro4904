<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun &mdash; Manajemen Proyek</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="login-body">

<div class="login-card">
    <div class="login-logo" style="background: linear-gradient(135deg,#10b981,#059669)">
        <i class="fas fa-user-plus"></i>
    </div>
    <h1 class="login-title">Daftar Akun Baru</h1>
    <p class="login-subtitle">Buat akun untuk mengelola proyek</p>

    <!-- Flash Error -->
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger py-2" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= $this->session->flashdata('error') ?>
    </div>
    <?php endif; ?>

    <form action="<?= site_url('auth/do_register') ?>" method="post">

        <div class="mb-3">
            <label class="form-label" for="username">
                <i class="fas fa-user me-1 text-muted"></i> Username
            </label>
            <input type="text"
                   id="username"
                   name="username"
                   class="form-control"
                   placeholder="Pilih username unik"
                   required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">
                <i class="fas fa-lock me-1 text-muted"></i> Password
            </label>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control"
                   placeholder="Buat password"
                   required>
        </div>

        <div class="mb-4">
            <label class="form-label" for="confirm_password">
                <i class="fas fa-lock me-1 text-muted"></i> Ulangi Password
            </label>
            <input type="password"
                   id="confirm_password"
                   name="confirm_password"
                   class="form-control"
                   placeholder="Ketik ulang password"
                   required>
        </div>

        <button type="submit" id="btn-register" class="btn w-100 py-2" style="background:#10b981;color:#fff">
            <i class="fas fa-user-plus me-2"></i> DAFTAR SEKARANG
        </button>
    </form>

    <p class="text-center mt-3 mb-0" style="font-size:13.5px; color:var(--text-muted)">
        Sudah punya akun?
        <a href="<?= site_url('auth') ?>" style="color:var(--primary); font-weight:600">
            Login di sini
        </a>
    </p>
</div>

</body>
</html>
