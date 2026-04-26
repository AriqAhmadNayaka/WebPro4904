<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'Aplikasi CI3'; ?></title>
    <style>
        /* Biar urusan width dan padding lebih gampang dikontrol. */
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #a5b7c4;
            color: #243640;
            min-height: 100vh;
        }
        /* Navbar ini dipakai buat menu utama setelah user login. */
        .navbar {
            background: #6f8892;
            color: #fff;
            padding: 18px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 24px rgba(67, 78, 86, 0.22);
        }
        .navbar-brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
        }
        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .container { width: 92%; max-width: 1120px; margin: 28px auto; }
        /* Card jadi pembungkus utama biar tampilan tiap halaman tetap seragam. */
        .card {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 28px;
            padding: 24px;
            box-shadow: 0 18px 36px rgba(67, 78, 86, 0.18);
            margin-bottom: 22px;
        }
        .alert { padding: 12px 16px; border-radius: 18px; margin-bottom: 16px; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        /* Tombol dibikin bulat biar senada sama halaman login. */
        .btn {
            background: #6f8892;
            color: #fff;
            border: 0;
            padding: 12px 22px;
            border-radius: 999px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 700;
        }
        .btn-secondary { background: #7e8f97; }
        .btn-danger { background: #c56666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d8e0e5; padding: 10px; text-align: left; }
        th { background: #dbe4e8; }
        input[type="text"], input[type="password"], input[type="file"], textarea {
            width: 100%;
            padding: 12px 14px;
            border: none;
            border-radius: 18px;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 14px;
            background: #efefef;
        }
        textarea { min-height: 96px; resize: vertical; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
        .stat { font-size: 32px; font-weight: bold; color: #6f8892; }
        .table-actions a { margin-right: 8px; }
        @media (max-width: 768px) {
            /* Di HP, navbar dibikin turun biar link-nya nggak sempit-sempitan. */
            .navbar {
                padding: 18px;
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }
            .navbar-menu {
                width: 100%;
            }
            .container {
                width: calc(100% - 24px);
            }
        }
    </style>
</head>
<body>
    <!-- Header navigasi buat semua halaman internal -->
    <div class="navbar">
        <div class="navbar-brand">WEB DESA KITA</div>
        <div class="navbar-menu">
            <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
            <a href="<?php echo site_url('warga'); ?>">Data Warga</a>
            <a href="<?php echo site_url('auth/logout'); ?>">Logout</a>
        </div>
    </div>
    <div class="container">
        <!-- Kalau ada pesan sukses atau error dari aksi sebelumnya, tampil di sini -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
