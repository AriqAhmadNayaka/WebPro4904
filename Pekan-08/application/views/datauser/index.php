<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyberVault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a0a0a, #16213e);
            color: #e0e6ed;
            min-height: 100vh;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        .card {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(0,212,255,0.3);
        }
        .table {
            background: rgba(6,6,19,0.8);
        }
        .table th {
            background: #0a0a0f;
            color: #00d4ff;
        }
        img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border: 2px solid #00d4ff;
        }
        .btn-edit {
            background: #ffc107;
            color: black;
        }
        .btn-hapus {
            background: #ff3366;
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-info">Daftar Data Pengguna</h2>
        <a href="<?= site_url('datauser/create') ?>" class="btn btn-info">
            <i class="fas fa-plus"></i> Tambah Data Baru
        </a>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Belum ada data. Silakan tambah data baru.
                        </td>
                    </tr>
                    <?php else: $no = 1; foreach($users as $u): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
    <?php if(!empty($u['foto']) && file_exists(FCPATH . $u['foto'])): ?>
        <img src="<?= base_url($u['foto']) ?>" 
             class="rounded-circle" 
             width="55" height="55"
             alt="Foto Profil">
    <?php else: ?>
        <div style="width:55px;height:55px;background:#444;border-radius:50%; 
                    display:flex;align-items:center;justify-content:center; 
                    color:#888;font-size:11px;border:2px solid #00d4ff;">
            No Foto
        </div>
    <?php endif; ?>
</td>
                        <td><?= htmlspecialchars($u['nama']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <a href="<?= site_url('datauser/edit/'.$u['id']) ?>" 
                               class="btn btn-edit btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= site_url('datauser/delete/'.$u['id']) ?>" 
                               class="btn btn-hapus btn-sm"
                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>