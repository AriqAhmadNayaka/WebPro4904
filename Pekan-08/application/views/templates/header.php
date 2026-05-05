<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CyberVault' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: rgba(6,6,19,0.8); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(0,212,255,0.2); }
        th { background: #0a0a0f; color: #00d4ff; }
        img { width: 55px; height: 55px; object-fit: cover; border-radius: 50%; border: 2px solid #00d4ff; }
        .btn { padding: 8px 14px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-right: 5px; display: inline-block; }
        .btn-primary { background:#00d4ff; color:black; }
        .btn-warning { background:#ffc107; color:black; }
        .btn-danger { background:#ff3366; color:white; }
        .alert { padding: 12px; margin: 15px 0; border-radius: 8px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<header class="main-header">
    <nav class="nav-bar">
        <a href="<?= site_url('datauser') ?>" class="logo">CyberVault</a>
        <ul class="nav-links">
            <li><a href="<?= site_url('datauser') ?>">Dashboard</a></li>
            <li><a href="<?= site_url('datauser/create') ?>">Tambah Data</a></li>
            <li><a href="<?= site_url('auth/logout') ?>">Logout</a></li>
        </ul>
    </nav>
</header>

<div style="padding: 120px 40px 40px;">