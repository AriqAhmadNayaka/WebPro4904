<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #203040;
        }
        .topbar {
            background: #17324d;
            color: #fff;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .topbar a {
            color: #fff;
            text-decoration: none;
            background: #ef4444;
            padding: 10px 14px;
            border-radius: 10px;
        }
        .container {
            max-width: 1100px;
            margin: 24px auto;
            padding: 0 16px 32px;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 20px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }
        .metric {
            display: inline-block;
            margin-top: 12px;
            background: #dff3e8;
            color: #166534;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 700;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }
        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            border: 1px solid #d8e0ea;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 140px;
            resize: vertical;
        }
        .btn {
            display: inline-block;
            padding: 12px 16px;
            border-radius: 10px;
            border: 0;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
        }
        .btn-primary {
            background: #1f7a4f;
            color: #fff;
        }
        .btn-secondary {
            background: #dbe4ee;
            color: #17324d;
        }
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
        }
        .alert-error {
            background: #ffe4e6;
            color: #9f1239;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            color: #52606d;
            font-size: 14px;
        }
        .thumb {
            width: 88px;
            height: 66px;
            object-fit: cover;
            border-radius: 10px;
            background: #e5e7eb;
        }
        .actions a {
            margin-right: 8px;
            text-decoration: none;
        }
        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead {
                display: none;
            }
            tr {
                border-bottom: 1px solid #e5e7eb;
                margin-bottom: 16px;
                padding-bottom: 8px;
            }
            td {
                border: 0;
                padding: 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div>
            <h1 style="margin:0;">Dashboard</h1>
            <div>Selamat datang, <?php echo html_escape($this->session->userdata('nama_lengkap')); ?></div>
        </div>
        <a href="<?php echo site_url('logout'); ?>">Logout</a>
    </div>

    <div class="container">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <div class="card" style="margin-bottom:20px;">
            <h2 style="margin-top:0;">Manajemen Data Tugas Besar</h2>
            <p>Dashboard ini hanya dapat diakses setelah login. Gunakan satu tombol <strong>Simpan</strong> untuk menambah atau memperbarui data sekaligus upload file gambar.</p>
            <span class="metric">Total data: <?php echo $total_posts; ?></span>
        </div>

        <div class="grid">
            <div class="card">
                <h3 style="margin-top:0;"><?php echo $edit_data ? 'Edit Data' : 'Tambah Data'; ?></h3>
                <?php echo form_open_multipart('dashboard/save'); ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data ? $edit_data->id : ''; ?>">

                    <label for="title">Judul</label>
                    <input type="text" id="title" name="title" value="<?php echo set_value('title', $edit_data ? $edit_data->title : ''); ?>" placeholder="Contoh: Data Pasien Kanker">

                    <label for="author">Penulis</label>
                    <input type="text" id="author" name="author" value="<?php echo set_value('author', $edit_data ? $edit_data->author : $this->session->userdata('nama_lengkap')); ?>" placeholder="Nama petugas">

                    <label for="article">Deskripsi</label>
                    <textarea id="article" name="article" placeholder="Masukkan ringkasan data atau keterangan"><?php echo set_value('article', $edit_data ? $edit_data->article : ''); ?></textarea>

                    <label for="image">Upload Gambar</label>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">

                    <?php if ($edit_data && !empty($edit_data->image)): ?>
                        <p>File saat ini:</p>
                        <img class="thumb" src="<?php echo base_url('application/uploads/post/' . $edit_data->image); ?>" alt="preview">
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <?php if ($edit_data): ?>
                        <a class="btn btn-secondary" href="<?php echo site_url('dashboard'); ?>">Batal Edit</a>
                    <?php endif; ?>
                <?php echo form_close(); ?>
            </div>

            <div class="card">
                <h3 style="margin-top:0;">Daftar Data</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Deskripsi</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="5">Belum ada data.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo html_escape($post->title); ?></td>
                                    <td><?php echo html_escape($post->author); ?></td>
                                    <td><?php echo nl2br(html_escape($post->article)); ?></td>
                                    <td>
                                        <?php if (!empty($post->image)): ?>
                                            <img class="thumb" src="<?php echo base_url('application/uploads/post/' . $post->image); ?>" alt="gambar">
                                        <?php else: ?>
                                            <span>Tidak ada file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a href="<?php echo site_url('dashboard?edit=' . $post->id); ?>">Edit</a>
                                        <a href="<?php echo site_url('dashboard/delete/' . $post->id); ?>" onclick="return confirm('Hapus data ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
