<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Portofolio</title>
    <style>
        /* Variabel warna untuk dashboard admin CRUD. */
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --ink: #14213d;
            --soft: #64748b;
            --accent: #0f766e;
            --danger: #b91c1c;
            --line: #d8e1eb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: radial-gradient(circle at top left, #dff6f3, #f4f7fb 42%, #eef2f7 100%);
            color: var(--ink);
        }
        .container { width: min(1150px, calc(100% - 32px)); margin: 0 auto; }
        .topbar {
            padding: 28px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .panel {
            background: rgba(255,255,255,.92);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
        }
        .hero {
            padding: 28px;
            margin-bottom: 24px;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: center;
        }
        .hero h1 {
            margin: 0 0 10px;
            font-size: 34px;
        }
        .hero p {
            margin: 0;
            color: var(--soft);
            line-height: 1.7;
        }
        .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            border: 0;
            cursor: pointer;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-light { background: #fff; color: var(--ink); border: 1px solid var(--line); }
        .btn-danger { background: var(--danger); color: #fff; }
        .grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
            align-items: start;
        }
        .card {
            padding: 24px;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            object-fit: cover;
            display: block;
            margin-bottom: 16px;
            background: #d9f2ef;
        }
        .profile-placeholder {
            display: grid;
            place-items: center;
            font-size: 36px;
            color: var(--accent);
        }
        .muted { color: var(--soft); }
        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 16px;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
        }
        th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--soft);
        }
        .thumb {
            width: 80px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            background: #e2e8f0;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .empty {
            padding: 18px;
            border: 1px dashed var(--line);
            border-radius: 16px;
            color: var(--soft);
            text-align: center;
        }
        /* Layout admin dibuat responsif di layar kecil. */
        @media (max-width: 900px) {
            .grid, .hero-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigasi cepat kembali ke halaman publik portofolio -->
        <div class="topbar">
            <a class="btn btn-light" href="<?= site_url('portfolio'); ?>">Lihat Website Portofolio</a>
        </div>

        <!-- Header dashboard admin -->
        <section class="panel hero">
            <div class="hero-grid">
                <div>
                    <h1>Dashboard CRUD Portofolio</h1>
                    <p>Kelola profil pribadi, foto profil, dan daftar project portofolio dari satu halaman admin sederhana.</p>
                </div>
                <div class="actions">
                    <a class="btn btn-light" href="<?= site_url('portfolio/profile'); ?>">Edit Profil</a>
                    <a class="btn btn-primary" href="<?= site_url('portfolio/create'); ?>">Tambah Portofolio</a>
                </div>
            </div>
        </section>

        <!-- Flash message sukses dari session -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')); ?></div>
        <?php endif; ?>
        <!-- Flash message error dari session -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><?= html_escape($this->session->flashdata('error')); ?></div>
        <?php endif; ?>

        <div class="grid">
            <!-- Card ringkasan profil utama -->
            <section class="panel card">
                <?php if (!empty($profile->profile_photo)): ?>
                    <img class="profile-photo" src="<?= base_url($profile->profile_photo); ?>" alt="Foto Profil">
                <?php else: ?>
                    <div class="profile-photo profile-placeholder">P</div>
                <?php endif; ?>

                <h2 style="margin:0 0 8px;"><?= html_escape($profile->full_name); ?></h2>
                <div class="muted" style="margin-bottom:16px;"><?= html_escape($profile->profession); ?></div>
                <p style="line-height:1.7;"><?= nl2br(html_escape($profile->about)); ?></p>
                <hr style="border:none;border-top:1px solid var(--line);margin:20px 0;">
                <p><strong>Email:</strong><br><?= html_escape($profile->email); ?></p>
                <p><strong>Telepon:</strong><br><?= html_escape($profile->phone); ?></p>
                <p><strong>Alamat:</strong><br><?= html_escape($profile->address); ?></p>
                <p><strong>Skill:</strong><br><?= html_escape($profile->skills); ?></p>
            </section>

            <!-- Card tabel daftar project portofolio -->
            <section class="panel card">
                <h2 style="margin-top:0;">Daftar Project</h2>
                <?php if (!empty($projects)): ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Link</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop data project yang sudah tersimpan -->
                                <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($project->image)): ?>
                                                <img class="thumb" src="<?= base_url($project->image); ?>" alt="<?= html_escape($project->title); ?>">
                                            <?php else: ?>
                                                <div class="thumb profile-placeholder">+</div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= html_escape($project->title); ?></td>
                                        <td><?= html_escape($project->category); ?></td>
                                        <td><?= html_escape(strlen(strip_tags($project->description)) > 90 ? substr(strip_tags($project->description), 0, 90) . '...' : strip_tags($project->description)); ?></td>
                                        <td>
                                            <?php if (!empty($project->project_link)): ?>
                                                <a href="<?= html_escape($project->project_link); ?>" target="_blank" rel="noopener noreferrer">Buka</a>
                                            <?php else: ?>
                                                <span class="muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Tombol aksi untuk mengubah dan menghapus data -->
                                            <div class="actions">
                                                <a class="btn btn-light" href="<?= site_url('portfolio/edit/' . $project->id); ?>">Edit</a>
                                                <a class="btn btn-danger" href="<?= site_url('portfolio/delete/' . $project->id); ?>" onclick="return confirm('Hapus data portofolio ini?');">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty">Belum ada data proyek. Klik tombol "Tambah Portofolio" untuk membuat data pertama.</div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</body>
</html>
