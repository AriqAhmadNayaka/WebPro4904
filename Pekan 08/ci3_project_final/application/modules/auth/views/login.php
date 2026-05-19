<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &mdash; Manajemen Proyek</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="login-body">

<div class="login-card">
    <div class="login-logo">
        <i class="fas fa-folder-open"></i>
    </div>
    <h1 class="login-title">Masuk</h1>
    <p class="login-subtitle">Manajemen Proyek &mdash; CodeIgniter 3</p>

    <!-- Flash Error -->
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger py-2" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= $this->session->flashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- Flash Success (dari register) -->
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success py-2" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= $this->session->flashdata('success') ?>
    </div>
    <?php endif; ?>

    <form action="<?= site_url('auth/do_login') ?>" method="post">
        <div class="mb-3">
            <label class="form-label" for="username">
                <i class="fas fa-user me-1 text-muted"></i> Username
            </label>
            <input type="text"
                   id="username"
                   name="username"
                   class="form-control"
                   placeholder="Masukkan username"
                   required autofocus>
        </div>

        <div class="mb-4">
            <label class="form-label" for="password">
                <i class="fas fa-lock me-1 text-muted"></i> Password
            </label>
            <div class="input-group">
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       placeholder="Masukkan password"
                       required>
                <button class="btn btn-outline-secondary" type="button" id="togglePass">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" id="btn-login" class="btn btn-primary w-100 py-2">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:13.5px; color:var(--text-muted)">
        Belum punya akun?
        <a href="<?= site_url('auth/register') ?>" class="fw-600" style="color:var(--primary)">
            Daftar sekarang
        </a>
    </p>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    // Toggle show/hide password
    $('#togglePass').on('click', function() {
        const input = $('#password');
        const icon  = $('#eyeIcon');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>
</body>
</html>
