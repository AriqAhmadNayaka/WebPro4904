<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard NaviBiz</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(8, 145, 178, 0.18), transparent 24%),
                radial-gradient(circle at bottom right, rgba(34, 197, 94, 0.14), transparent 24%),
                #f8fafc;
            color: #0f172a;
        }

        .header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .header-inner,
        .main {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
        }

        .header-inner {
            padding: 22px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
        }

        .subtitle {
            margin: 6px 0 0;
            color: #64748b;
        }

        .logout {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 700;
        }

        .main {
            padding: 24px 0 40px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-weight: 700;
        }

        .alert.success {
            background: #dcfce7;
            color: #166534;
        }

        .alert.error {
            background: #fee2e2;
            color: #b91c1c;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .card {
            border-radius: 18px;
            padding: 18px;
            color: #ffffff;
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        }

        .card strong {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .card span {
            font-size: 24px;
            font-weight: 700;
        }

        .cyan {
            background: linear-gradient(135deg, #0891b2, #0f766e);
        }

        .green {
            background: linear-gradient(135deg, #15803d, #22c55e);
        }

        .orange {
            background: linear-gradient(135deg, #c2410c, #f97316);
        }

        .slate {
            background: linear-gradient(135deg, #334155, #0f172a);
        }

        .panel {
            background: #ffffff;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
            margin-bottom: 22px;
        }

        .panel h2 {
            margin: 0 0 8px;
        }

        .panel p {
            margin: 0 0 16px;
            color: #64748b;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
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
        select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
        }

        .helper {
            font-size: 13px;
            color: #64748b;
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
            padding: 12px 18px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: #0f766e;
            color: #ffffff;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #0f172a;
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
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge.in {
            background: #dcfce7;
            color: #166534;
        }

        .badge.out {
            background: #ffedd5;
            color: #c2410c;
        }

        .table-actions a {
            text-decoration: none;
            font-weight: 700;
            margin-right: 12px;
            color: #0f766e;
        }
    </style>
</head>
<body>
    <?php
    $total_masuk = isset($summary['total_masuk']) ? (int) $summary['total_masuk'] : 0;
    $total_keluar = isset($summary['total_keluar']) ? (int) $summary['total_keluar'] : 0;
    ?>
    <header class="header">
        <div class="header-inner">
            <div>
                <h1 class="page-title">NaviBiz Dashboard</h1>
                <p class="subtitle">Dashboard ini hanya bisa dibuka setelah login berhasil, sesuai kebutuhan tugas besar pekan 7.</p>
            </div>
            <div>
                <div style="margin-bottom: 8px;">Login sebagai <strong><?php echo html_escape($username); ?></strong></div>
                <a class="logout" href="<?php echo site_url('logout'); ?>">Logout</a>
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
            <div class="card cyan">
                <strong>Saldo Akhir</strong>
                <span>Rp <?php echo number_format($saldo, 0, ',', '.'); ?></span>
            </div>
            <div class="card green">
                <strong>Total Pemasukan</strong>
                <span>Rp <?php echo number_format($total_masuk, 0, ',', '.'); ?></span>
            </div>
            <div class="card orange">
                <strong>Total Pengeluaran</strong>
                <span>Rp <?php echo number_format($total_keluar, 0, ',', '.'); ?></span>
            </div>
            <div class="card slate">
                <strong>Jumlah Data</strong>
                <span><?php echo count($rows); ?> Transaksi</span>
            </div>
        </section>

        <section class="panel">
            <h2><?php echo $edit ? 'Edit Data Transaksi' : 'Input Data Transaksi'; ?></h2>
            <p>Tombol <strong>Simpan</strong> memproses create/update sekaligus upload file dalam satu kali submit.</p>

            <form method="post" action="<?php echo site_url('dashboard/simpan'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $edit ? (int) $edit->id : 0; ?>">
                <input type="hidden" name="mode" value="<?php echo $edit ? 'update' : 'create'; ?>">
                <input type="hidden" name="existing_file" value="<?php echo $edit ? html_escape($edit->foto) : ''; ?>">

                <div class="form-grid">
                    <div>
                        <label for="tanggal">Tanggal</label>
                        <input id="tanggal" type="date" name="tanggal" value="<?php echo $edit ? html_escape($edit->tanggal) : ''; ?>" required>
                    </div>

                    <div>
                        <label for="jenis">Jenis</label>
                        <select id="jenis" name="jenis" required>
                            <option value="">Pilih jenis transaksi</option>
                            <option value="Pemasukan" <?php echo ($edit && $edit->jenis === 'Pemasukan') ? 'selected' : ''; ?>>Pemasukan</option>
                            <option value="Pengeluaran" <?php echo ($edit && $edit->jenis === 'Pengeluaran') ? 'selected' : ''; ?>>Pengeluaran</option>
                        </select>
                    </div>

                    <div class="full">
                        <label for="keterangan">Keterangan</label>
                        <input id="keterangan" type="text" name="keterangan" value="<?php echo $edit ? html_escape($edit->keterangan) : ''; ?>" placeholder="Contoh: gaji, listrik, penjualan, belanja stok" required>
                    </div>

                    <div>
                        <label for="jumlah">Jumlah</label>
                        <input id="jumlah" type="number" name="jumlah" min="1" value="<?php echo $edit ? (int) $edit->jumlah : ''; ?>" required>
                    </div>

                    <div>
                        <label for="file">Upload File</label>
                        <input id="file" type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <div class="helper">Format file: JPG, JPEG, PNG, PDF, DOC, DOCX. Maksimal 4 MB.</div>
                        <?php if ($edit && !empty($edit->foto)) : ?>
                            <div class="helper">
                                File saat ini:
                                <a href="<?php echo base_url('uploads/' . rawurlencode($edit->foto)); ?>" target="_blank">
                                    <?php echo html_escape($edit->foto); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn-primary" type="submit">Simpan</button>
                    <?php if ($edit) : ?>
                        <a class="btn-secondary" href="<?php echo site_url('dashboard'); ?>">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="panel">
            <h2>Data Laporan</h2>
            <p>Bagian ini menampilkan fungsi Read, lalu kolom aksi dipakai untuk Update dan Delete.</p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rows)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo html_escape($row->tanggal); ?></td>
                                    <td><?php echo html_escape($row->keterangan); ?></td>
                                    <td>
                                        <span class="badge <?php echo $row->jenis === 'Pemasukan' ? 'in' : 'out'; ?>">
                                            <?php echo html_escape($row->jenis); ?>
                                        </span>
                                    </td>
                                    <td>Rp <?php echo number_format((int) $row->jumlah, 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if (!empty($row->foto)) : ?>
                                            <a href="<?php echo base_url('uploads/' . rawurlencode($row->foto)); ?>" target="_blank">Lihat File</a>
                                        <?php else : ?>
                                            Tidak ada file
                                        <?php endif; ?>
                                    </td>
                                    <td class="table-actions">
                                        <a href="<?php echo site_url('dashboard') . '?edit=' . (int) $row->id; ?>">Edit</a>
                                        <a href="<?php echo site_url('dashboard/hapus/' . (int) $row->id); ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7">Belum ada data transaksi yang tersimpan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
