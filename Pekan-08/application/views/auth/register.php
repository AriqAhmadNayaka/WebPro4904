<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Registrasi - CyberVault</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
<style>
.form-box { width:380px; margin:100px auto; }
</style>
</head>
<body>

<div class="form-box">
  <h2>Form Registrasi</h2>
  
  <?php if($this->session->flashdata('error')): ?>
    <div class="result" style="color:#ff6464;"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>
  <?php if($this->session->flashdata('success')): ?>
    <div class="result" style="color:#00ff88;"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="email" name="email" class="input" placeholder="Masukkan Email" required>
    <input type="password" name="password" class="input" placeholder="Masukkan Password" required>
    <button type="submit" class="btn-login">Registrasi</button>
  </form>

  <div style="text-align:center; margin-top:15px;">
    <a href="<?= site_url('auth/login') ?>" style="color:#00d4ff;">Sudah punya akun? Login</a>
  </div>
</div>

</body>
</html>