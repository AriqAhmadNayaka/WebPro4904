<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StokKita CRUD</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(255, 190, 92, 0.28), transparent 28%),
                radial-gradient(circle at top right, rgba(31, 113, 103, 0.18), transparent 26%),
                linear-gradient(180deg, #fff9ef 0%, #f4efe7 100%);
            color: #23313b;
        }

        .header {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #153c3a 0%, #23635f 55%, #f08a4b 100%);
            color: #fffdf8;
        }

        .header-inner,
        .main {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .header-inner {
            padding: 34px 0 38px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .page-title {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(32px, 4vw, 54px);
            line-height: 1.05;
        }

        .subtitle {
            margin: 12px 0 0;
            max-width: 640px;
            color: rgba(255, 253, 248, 0.86);
            font-size: 16px;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.16);
            color: #fffdf8;
            padding: 10px 14px;
            border-radius: 999px;
            font-weight: 700;
            backdrop-filter: blur(6px);
        }

        .main {
            padding: 28px 0 42px;
        }

        .alert {
            padding: 16px 18px;
            border-radius: 18px;
            margin-bottom: 20px;
            font-weight: 700;
            box-shadow: 0 16px 24px rgba(35, 49, 59, 0.08);
        }

        .alert.success {
            background: #e1f5ea;
            color: #17593e;
        }

        .alert.error {
            background: #ffe5de;
            color: #9b2f20;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .card {
            border-radius: 24px;
            padding: 22px;
            color: #fffdf8;
            box-shadow: 0 22px 34px rgba(35, 49, 59, 0.12);
            position: relative;
            overflow: hidden;
        }

        .card strong {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .card span {
            font-size: 28px;
            font-weight: 700;
        }

        .card::after {
            content: "";
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            right: -28px;
            bottom: -28px;
            background: rgba(255, 255, 255, 0.14);
        }

        .teal {
            background: linear-gradient(135deg, #0f4c4c, #1f7167);
        }

        .orange {
            background: linear-gradient(135deg, #c85f2b, #f3a261);
        }

        .ink {
            background: linear-gradient(135deg, #30475e, #1e2d3b);
        }

        .sand {
            background: linear-gradient(135deg, #9b5d39, #d79f54);
        }

        .panel {
            background: rgba(255, 253, 248, 0.92);
            border: 1px solid rgba(35, 49, 59, 0.08);
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 22px 34px rgba(35, 49, 59, 0.08);
            margin-bottom: 24px;
        }

        .panel h2 {
            margin: 0 0 8px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 28px;
        }

        .panel p {
            margin: 0 0 16px;
            color: #586873;
        }

        .two-column {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.7fr);
            gap: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #d7d0c7;
            background: #fffdf9;
            color: #23313b;
            font: inherit;
        }

        textarea {
            min-height: 122px;
            resize: vertical;
        }

        .helper {
            font-size: 13px;
            color: #6f7c84;
            margin-top: 6px;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-block;
            border: 0;
            border-radius: 999px;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0f4c4c, #1f7167);
            color: #ffffff;
            box-shadow: 0 14px 24px rgba(31, 113, 103, 0.22);
        }

        .btn-secondary {
            background: #efe6d7;
            color: #23313b;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ece3d8;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #faf4ea;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge.ready {
            background: #dff5eb;
            color: #17593e;
        }

        .badge.pre {
            background: #fff0d9;
            color: #9a5a1d;
        }

        .badge.empty {
            background: #ffe1d9;
            color: #9b2f20;
        }

        .table-actions a {
            text-decoration: none;
            font-weight: 700;
            margin-right: 12px;
            color: #1f7167;
        }

        .meta {
            display: grid;
            gap: 14px;
        }

        .meta-card {
            background: linear-gradient(180deg, #fffdf8 0%, #f7efe2 100%);
            border: 1px solid #ece3d8;
            border-radius: 20px;
            padding: 18px;
        }

        .meta-card strong {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .meta-card p {
            margin: 0;
            color: #5b6972;
        }

        .search-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(220px, 0.7fr) auto;
            gap: 14px;
            align-items: end;
        }

        .muted {
            color: #6f7c84;
            font-size: 14px;
        }

        .footer-note {
            margin-top: 10px;
            font-size: 13px;
            color: #6f7c84;
        }

        @media (max-width: 920px) {
            .two-column {
                grid-template-columns: 1fr;
            }

            .search-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php $edit_mode = !empty($edit); ?>
    <header class="header">
        <div class="header-inner">
            <div>
                <h1 class="page-title">StokKita CRUD</h1>
                <p class="subtitle">Aplikasi inventaris produk berbasis CodeIgniter 3 dengan pola MVC, data tersimpan ke database MySQL lokal XAMPP, dan siap dipakai untuk tambah, baca, ubah, serta hapus produk.</p>
                <div class="chips">
                    <span class="chip">CodeIgniter 3</span>
                    <span class="chip">HMVC MVC</span>
                    <span class="chip">MySQL Database</span>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <?php if (!empty($message)) : ?>
            <div class="alert <?php echo html_escape($message['type']); ?>">
                <?php echo html_escape($message['text']); ?>
            </div>
        <?php endif; ?>

        <section class="cards">
            <div class="card teal">
                <strong>Total Produk</strong>
                <span><?php echo (int) $summary['total_produk']; ?> Item</span>
            </div>
            <div class="card orange">
                <strong>Total Stok</strong>
                <span><?php echo number_format((int) $summary['total_stok'], 0, ',', '.'); ?> Unit</span>
            </div>
            <div class="card ink">
                <strong>Nilai Inventaris</strong>
                <span>Rp <?php echo number_format((int) $summary['total_nilai'], 0, ',', '.'); ?></span>
            </div>
            <div class="card sand">
                <strong>Stok Menipis</strong>
                <span><?php echo (int) $summary['stok_menipis']; ?> Produk</span>
            </div>
        </section>

        <section class="two-column">
            <div class="panel">
                <h2><?php echo $edit_mode ? 'Edit Produk' : 'Tambah Produk'; ?></h2>
                <p>Form ini menjalankan proses create dan update produk langsung ke database MySQL lokal proyek.</p>

                <form method="post" action="<?php echo site_url('dashboard/simpan'); ?>">
                    <input type="hidden" name="id" value="<?php echo $edit_mode ? (int) $edit->id : 0; ?>">
                    <input type="hidden" name="mode" value="<?php echo $edit_mode ? 'update' : 'create'; ?>">

                    <div class="form-grid">
                        <div>
                            <label for="kode_produk">Kode Produk</label>
                            <input id="kode_produk" type="text" name="kode_produk" value="<?php echo $edit_mode ? html_escape($edit->kode_produk) : ''; ?>" placeholder="Contoh: PRD-004" required>
                        </div>

                        <div>
                            <label for="kategori">Kategori</label>
                            <input id="kategori" list="kategori-list" type="text" name="kategori" value="<?php echo $edit_mode ? html_escape($edit->kategori) : ''; ?>" placeholder="Contoh: ATK, Elektronik" required>
                            <datalist id="kategori-list">
                                <?php foreach ($category_options as $category) : ?>
                                    <option value="<?php echo html_escape($category); ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="full">
                            <label for="nama_produk">Nama Produk</label>
                            <input id="nama_produk" type="text" name="nama_produk" value="<?php echo $edit_mode ? html_escape($edit->nama_produk) : ''; ?>" placeholder="Masukkan nama produk" required>
                        </div>

                        <div>
                            <label for="harga">Harga</label>
                            <input id="harga" type="number" name="harga" min="0" value="<?php echo $edit_mode ? (int) $edit->harga : 0; ?>" required>
                        </div>

                        <div>
                            <label for="stok">Stok</label>
                            <input id="stok" type="number" name="stok" min="0" value="<?php echo $edit_mode ? (int) $edit->stok : 0; ?>" required>
                        </div>

                        <div>
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="">Pilih status produk</option>
                                <?php foreach ($status_options as $status) : ?>
                                    <option value="<?php echo html_escape($status); ?>" <?php echo ($edit_mode && $edit->status === $status) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($status); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="full">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" placeholder="Tuliskan deskripsi singkat produk"><?php echo $edit_mode ? html_escape($edit->deskripsi) : ''; ?></textarea>
                            <div class="helper">Semua data tersimpan ke database `db_latihan_pekan08_crud` dan skemanya tersedia di folder `application/database`.</div>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="btn-primary" type="submit"><?php echo $edit_mode ? 'Perbarui Produk' : 'Simpan Produk'; ?></button>
                        <?php if ($edit_mode) : ?>
                            <a class="btn-secondary" href="<?php echo site_url('dashboard'); ?>">Batal Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="meta">
                <div class="panel">
                    <h2>Filter Data</h2>
                    <p>Cari produk berdasarkan kode, nama, atau kategori, lalu sempitkan lagi dengan status stok.</p>

                    <form method="get" action="<?php echo site_url('dashboard'); ?>">
                        <div class="search-grid">
                            <div>
                                <label for="q">Kata Kunci</label>
                                <input id="q" type="text" name="q" value="<?php echo html_escape($filters['keyword']); ?>" placeholder="Cari kode, nama, atau kategori">
                            </div>

                            <div>
                                <label for="status_filter">Status</label>
                                <select id="status_filter" name="status">
                                    <option value="">Semua status</option>
                                    <?php foreach ($status_options as $status) : ?>
                                        <option value="<?php echo html_escape($status); ?>" <?php echo $filters['status'] === $status ? 'selected' : ''; ?>>
                                            <?php echo html_escape($status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="actions" style="margin-top: 0;">
                                <button class="btn-primary" type="submit">Cari</button>
                                <a class="btn-secondary" href="<?php echo site_url('dashboard'); ?>">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="meta-card">
                    <strong>Sudah MVC dan terintegrasi database</strong>
                    <p>Controller menangani request, model mengelola query MySQL, dan view menampilkan dashboard CRUD yang bisa langsung dipakai.</p>
                </div>

                <div class="meta-card">
                    <strong>Seed data otomatis</strong>
                    <p>Saat database kosong, aplikasi akan mengisi beberapa produk contoh supaya tampilan awal tidak polos dan bisa langsung diuji.</p>
                </div>
            </div>
        </section>

        <section class="panel">
            <h2>Daftar Produk</h2>
            <p>Bagian ini adalah fungsi read. Tombol edit dan hapus melengkapi alur update serta delete.</p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rows)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo html_escape($row->kode_produk); ?></td>
                                    <td><?php echo html_escape($row->nama_produk); ?></td>
                                    <td><?php echo html_escape($row->kategori); ?></td>
                                    <td>Rp <?php echo number_format((int) $row->harga, 0, ',', '.'); ?></td>
                                    <td><?php echo number_format((int) $row->stok, 0, ',', '.'); ?> unit</td>
                                    <td>
                                        <?php
                                        $badge_class = 'ready';

                                        if ($row->status === 'Pre Order') {
                                            $badge_class = 'pre';
                                        } elseif ($row->status === 'Habis') {
                                            $badge_class = 'empty';
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>">
                                            <?php echo html_escape($row->status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row->deskripsi !== '' ? html_escape($row->deskripsi) : '<span class="muted">Tidak ada deskripsi</span>'; ?></td>
                                    <td><?php echo html_escape(date('d M Y H:i', strtotime($row->updated_at))); ?></td>
                                    <td class="table-actions">
                                        <a href="<?php echo site_url('dashboard') . '?edit=' . (int) $row->id; ?>">Edit</a>
                                        <a href="<?php echo site_url('dashboard/hapus/' . (int) $row->id); ?>" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10">Belum ada data produk yang cocok dengan filter saat ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="footer-note">Skema tabel disimpan juga di file `application/database/latihan_pekan08_schema.sql` untuk referensi struktur database.</div>
        </section>
    </main>
</body>
</html>
