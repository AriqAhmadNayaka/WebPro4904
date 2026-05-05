<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - CyberVault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a0a0a, #16213e);
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0,212,255,0.3);
        }
    </style>
</head>
<body class="d-flex align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card login-card shadow-lg border-0 mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="text-info fw-bold">🔒 CyberVault</h1>
                        <p class="text-light">Masuk ke akun Anda</p>
                    </div>

                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required>
                        </div>
                        <div class="mb-3 input-group">
                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Password" required>
                            <button class="btn btn-outline-info" type="button" onclick="togglePass()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-info btn-lg w-100">Masuk Sekarang</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-light">Belum punya akun? 
                            <a href="<?= site_url('auth/register') ?>" class="text-info">Daftar di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const pass = document.getElementById('password');
    const icon = document.querySelector('.fa-eye');
    if (pass.type === 'password') {
        pass.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pass.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>