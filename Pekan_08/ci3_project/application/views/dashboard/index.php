<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html_escape($title); ?> - LifeTrack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
            color: #1f2937;
        }

        .container {
            width: 100%;
            min-height: 100vh;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 23px 100px;
            margin-bottom: 30px;
            box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            left: 0;
            background-color: white;
            z-index: 10;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bulb {
            width: 16px;
            height: 16px;
            background: #f7c948;
            border-radius: 50%;
        }

        .menu {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        .menu li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .menu li a:hover {
            color: #52a8a0;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .profile-badge {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #d1fae5;
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        .page-header {
            width: 90%;
            margin: 0 auto 20px;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 5px;
            color: #064e3b;
        }

        .subtitle {
            color: #6b7280;
        }

        .box,
        .box2 {
            width: 90%;
            margin: 0 auto 20px;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #e5e8ea;
            background: white;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            outline: none;
            transition: 0.3s;
        }

        input:focus,
        select:focus {
            box-shadow: 0 0 0 2px rgba(68, 139, 132, 0.25);
        }

        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        button,
        .action-link {
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button {
            background-color: #448b84;
            color: white;
        }

        button:hover,
        .action-link:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
            transform: translateY(-1px);
        }

        .action-link.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .notice {
            width: 90%;
            margin: 0 auto 20px;
            padding: 14px 18px;
            border-radius: 12px;
            font-weight: 500;
        }

        .notice.success {
            background: #d1fae5;
            color: #065f46;
        }

        .notice.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            gap: 12px;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: center;
            vertical-align: middle;
        }

        table th {
            color: #064e3b;
            font-weight: 600;
            background: #e6f4f1;
        }

        table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .empty-state {
            text-align: center;
            color: #6b7280;
            padding: 24px 0;
        }

        .validation {
            font-size: 12px;
            color: #b91c1c;
            margin-top: 6px;
        }

        .photo-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            background: #e5e7eb;
        }

        @media (max-width: 900px) {
            .navbar {
                padding: 20px;
                flex-direction: column;
                gap: 14px;
            }

            .menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <span class="bulb"></span>
                <h2>LifeTrack</h2>
            </div>

            <ul class="menu">
                <li><a href="<?php echo site_url('dashboard'); ?>">Beranda</a></li>
                <li><a href="<?php echo site_url('dashboard'); ?>">Data Pasien</a></li>
                <li><a href="#">Jadwal</a></li>
                <li><a href="#">Rekomendasi Kegiatan</a></li>
                <li><a href="#">Nutrisi dan Gizi</a></li>
            </ul>

            <div class="profile">
                <div class="profile-badge"><?php echo strtoupper(substr($loggedInUser, 0, 1)); ?></div>
                <span><?php echo html_escape($loggedInUser); ?></span>
            </div>
        </nav>

        <div class="page-header">
            <h1>Input Data Pasien</h1>
            <p class="subtitle">Dashboard utama untuk tambah data pasien dan melihat seluruh data.</p>
        </div>

        <?php if ($message !== ''): ?>
            <div class="notice <?php echo html_escape($messageType); ?>">
                <?php echo html_escape($message); ?>
            </div>
        <?php endif; ?>

        <div class="box">
            <?php echo form_open_multipart('dashboard/save'); ?>
                <div class="form-grid">
                    <div>
                        <label>Nama</label>
                        <input type="text" name="name" placeholder="Masukkan nama" value="<?php echo set_value('name'); ?>" required>
                        <?php echo form_error('name', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Alamat</label>
                        <input type="text" name="addres" placeholder="Masukkan alamat" value="<?php echo set_value('addres'); ?>" required>
                        <?php echo form_error('addres', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Nomor Telepon</label>
                        <input type="text" name="number" placeholder="08xxxxxxxxxx" maxlength="12" value="<?php echo set_value('number'); ?>" required>
                        <?php echo form_error('number', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Jenis Kelamin</label>
                        <select name="gender" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki" <?php echo set_select('gender', 'Laki-laki'); ?>>Laki-laki</option>
                            <option value="Perempuan" <?php echo set_select('gender', 'Perempuan'); ?>>Perempuan</option>
                        </select>
                        <?php echo form_error('gender', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Tanggal Lahir</label>
                        <input type="date" name="date" value="<?php echo set_value('date'); ?>" required>
                        <?php echo form_error('date', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="weight" placeholder="Contoh: 50" value="<?php echo set_value('weight'); ?>" required>
                        <?php echo form_error('weight', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" name="height" placeholder="Contoh: 170" value="<?php echo set_value('height'); ?>" required>
                        <?php echo form_error('height', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Jenis Kanker</label>
                        <input type="text" name="text" placeholder="Masukkan jenis kanker" value="<?php echo set_value('text'); ?>" required>
                        <?php echo form_error('text', '<div class="validation">', '</div>'); ?>
                    </div>

                    <div>
                        <label>Foto Pasien</label>
                        <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
                        <div class="validation">Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</div>
                    </div>
                </div>

                <div class="button-row">
                    <button type="submit">Simpan</button>
                </div>
            <?php echo form_close(); ?>
        </div>

        <div class="box2">
            <div class="table-header">
                <h2>Daftar Pasien</h2>
                <span><?php echo (int) $total_patients; ?> data ditemukan</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>
                        <th>Jenis Kelamin</th>
                        <th>Tgl Lahir</th>
                        <th>BB (kg)</th>
                        <th>TB (cm)</th>
                        <th>Jenis Kanker</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patients)): ?>
                        <?php foreach ($patients as $p): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($p->file_pasien)): ?>
                                        <img class="photo-thumb" src="<?php echo base_url('uploads/patients/' . $p->file_pasien); ?>" alt="Foto pasien">
                                    <?php else: ?>
                                        <span>Tidak ada foto</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo html_escape($p->nama); ?></td>
                                <td><?php echo html_escape($p->alamat); ?></td>
                                <td><?php echo html_escape($p->notlp); ?></td>
                                <td><?php echo html_escape($p->jeniskelamin); ?></td>
                                <td><?php echo html_escape($p->tanggallahir); ?></td>
                                <td><?php echo html_escape($p->beratbadan); ?></td>
                                <td><?php echo html_escape($p->tinggibadan); ?></td>
                                <td><?php echo html_escape($p->jeniskanker); ?></td>
                                <td>
                                    <div class="button-row" style="margin-top: 0; justify-content: center;">
                                        <a class="action-link delete" href="<?php echo site_url('dashboard/delete/' . (int) $p->id); ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="empty-state">Belum ada data pasien yang tersimpan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
