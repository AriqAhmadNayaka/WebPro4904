<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? html_escape($title) : 'CyberVault'; ?> - CyberVault</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0d0d14;
            background-image: radial-gradient(circle at 50% 0%, #1a1a2e 0%, #0d0d14 100%);
            color: #e0e0e0;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .header-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .brand h1 {
            color: #00d4ff;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 26px;
            margin: 0;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
        }

        .brand p {
            color: #9aa4b2;
            margin-top: 6px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .user-chip {
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #d8e1eb;
            font-size: 13px;
        }

        .nav {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            padding: 14px 18px;
            margin-bottom: 24px;
        }

        .nav ul {
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav a {
            color: #e0e0e0;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 600;
        }

        .nav a:hover {
            background: rgba(0, 212, 255, 0.12);
            color: #00d4ff;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: rgba(0, 255, 170, 0.08);
            border-color: rgba(0, 255, 170, 0.2);
            color: #91ffd9;
        }

        .alert-error {
            background-color: rgba(255, 77, 77, 0.08);
            border-color: rgba(255, 77, 77, 0.2);
            color: #ffb3b3;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid transparent;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-success {
            background-color: #00d4ff;
            color: #000;
        }

        .btn-danger {
            background-color: rgba(255, 77, 77, 0.1);
            color: #ff6b6b;
            border-color: #ff4d4d;
        }

        .btn-warning {
            background-color: rgba(0, 255, 170, 0.1);
            color: #00ffaa;
            border-color: #00ffaa;
        }

        .btn-info {
            background-color: transparent;
            color: #00d4ff;
            border-color: #00d4ff;
        }

        .card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #00d4ff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #333;
            border-radius: 8px;
            font-size: 14px;
            background: rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #00d4ff;
            box-shadow: 0 0 8px rgba(0, 212, 255, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent;
            border-radius: 12px;
            overflow: hidden;
        }

        .post-image {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid rgba(0, 212, 255, 0.5);
        }

        .post-detail-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #00d4ff;
            padding: 4px;
            margin: 16px 0;
        }

        .text-center {
            text-align: center;
        }

        .muted {
            color: #9aa4b2;
        }

        footer {
            text-align: center;
            padding: 8px 0 24px;
            color: #788190;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        th,
        td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        th {
            color: #00d4ff;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.08em;
            background: rgba(255, 255, 255, 0.03);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .action-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 24px;
            align-items: center;
        }

        .detail-box {
            background: rgba(0, 0, 0, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 18px;
        }

        .detail-box h3 {
            color: #00d4ff;
            margin-bottom: 8px;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 14px;
            }

            .card {
                padding: 20px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-area">
            <div class="brand">
                <h1>CyberVault <span style="font-weight: 300; color: #ffffff;">Admin</span></h1>
            </div>
            <div class="header-actions">
                <div class="user-chip">
                    Login sebagai <?php echo html_escape((string) $this->session->userdata('nama')); ?>
                </div>
                <a href="<?php echo site_url('logout'); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin keluar?');">Log Out</a>
            </div>
        </div>

        <div class="nav">
            <ul>
                <li><a href="<?php echo site_url('posts'); ?>">Data User</a></li>
                <li><a href="<?php echo site_url('posts/create'); ?>">Tambah User</a></li>
            </ul>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
