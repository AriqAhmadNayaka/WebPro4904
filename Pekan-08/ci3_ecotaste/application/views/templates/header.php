<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'EcoTaste'; ?></title>
    <style>
        :root {
            --green: #3f7d20;
            --green-dark: #264d13;
            --green-soft: #eef7e8;
            --cream: #fffdf6;
            --danger: #b3261e;
            --text: #243119;
            --shadow: 0 14px 30px rgba(36, 49, 25, 0.12);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(180deg, #f7fbf3 0%, var(--cream) 100%);
            color: var(--text);
        }
        .container {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
        }
        .navbar {
            background: #fff;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 0;
        }
        .brand {
            color: var(--green);
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn, button {
            display: inline-block;
            border: 0;
            border-radius: 10px;
            padding: 11px 18px;
            background: var(--green);
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-weight: 700;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--green);
            color: var(--green);
        }
        .btn-danger {
            background: var(--danger);
        }
        .card {
            background: #fff;
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 24px;
        }
        .flash {
            margin: 16px 0;
            padding: 14px 16px;
            border-radius: 12px;
        }
        .flash-success {
            background: #e9f8e2;
            color: #245313;
        }
        .flash-error {
            background: #fde9e7;
            color: var(--danger);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .menu-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 14px;
            background: var(--green-soft);
        }
        .menu-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .hero {
            padding: 36px 0 20px;
        }
        .hero-box {
            background: linear-gradient(135deg, var(--green) 0%, #7dac3f 100%);
            color: #fff;
            border-radius: 24px;
            padding: 28px;
            box-shadow: var(--shadow);
        }
        .stats {
            margin-top: 20px;
            display: inline-block;
            background: rgba(255, 255, 255, 0.16);
            padding: 12px 16px;
            border-radius: 14px;
        }
        .form-group { margin-bottom: 16px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }
        input[type="text"],
        input[type="number"],
        input[type="password"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cad8ba;
            border-radius: 10px;
            font-size: 14px;
        }
        textarea { min-height: 120px; resize: vertical; }
        .auth-wrap {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .auth-card {
            width: min(420px, 100%);
        }
        .error-text {
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
        }
        .preview {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 14px;
            margin-bottom: 12px;
            background: var(--green-soft);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #eef2e8;
            text-align: left;
            vertical-align: top;
        }
        @media (max-width: 768px) {
            .navbar-inner,
            .menu-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
<?php if ($this->session->userdata('is_logged_in')): ?>
    <div class="navbar">
        <div class="container navbar-inner">
            <a class="brand" href="<?php echo site_url('dashboard'); ?>">EcoTaste</a>
            <div class="nav-links">
                <span>Halo, <strong><?php echo html_escape($this->session->userdata('nama_lengkap')); ?></strong></span>
                <a class="btn btn-outline" href="<?php echo site_url('menu/tambah'); ?>">Tambah Menu</a>
                <a class="btn btn-danger" href="<?php echo site_url('logout'); ?>">Logout</a>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="container">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="flash flash-success"><?php echo html_escape($this->session->flashdata('success')); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="flash flash-error"><?php echo html_escape($this->session->flashdata('error')); ?></div>
    <?php endif; ?>
