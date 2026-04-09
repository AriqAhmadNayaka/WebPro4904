<?php

require_once __DIR__ . '/classes/AppConfig.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/FlashMessage.php';
require_once __DIR__ . '/classes/FileUploader.php';
require_once __DIR__ . '/classes/TransactionRepository.php';

$auth = new Auth();
$flash = new FlashMessage();
$loginError = '';
$databaseError = '';
$edit = null;

function redirectToDashboard(): void
{
    header('Location: dompet.php');
    exit;
}

function redirectWithFlash(FlashMessage $flash, string $message, string $type = 'success'): void
{
    $flash->set($message, $type);
    redirectToDashboard();
}

if (isset($_GET['logout'])) {
    $auth->logout();
    header('Location: dompet.php');
    exit;
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$auth->login($username, $password)) {
        $loginError = 'Login gagal. Gunakan username admin dan password 12345.';
    } else {
        redirectToDashboard();
    }
}

if (!$auth->check()) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Aplikasi Dompet</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                min-height: 100vh;
                display: grid;
                place-items: center;
                background: linear-gradient(135deg, #dff4ff 0%, #f6f7fb 55%, #dfffe9 100%);
            }

            .login-box {
                width: min(90vw, 380px);
                background: #ffffff;
                padding: 28px;
                border-radius: 18px;
                box-shadow: 0 18px 45px rgba(30, 41, 59, 0.14);
            }

            h1 {
                margin: 0 0 10px;
                color: #0f172a;
                font-size: 28px;
            }

            p {
                color: #475569;
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-bottom: 6px;
                font-weight: 700;
                color: #1e293b;
            }

            input {
                width: 100%;
                padding: 12px 14px;
                margin-bottom: 16px;
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                box-sizing: border-box;
            }

            button {
                width: 100%;
                padding: 12px 14px;
                border: none;
                border-radius: 999px;
                background: #0f766e;
                color: #ffffff;
                font-weight: 700;
                cursor: pointer;
            }

            .error {
                background: #fee2e2;
                color: #b91c1c;
                padding: 12px;
                border-radius: 10px;
                margin-bottom: 16px;
            }

            .hint {
                margin-top: 16px;
                font-size: 13px;
                color: #64748b;
            }
        </style>
    </head>
    <body>
        <form class="login-box" method="post">
            <h1>Login</h1>
            <p>Masuk terlebih dahulu untuk membuka halaman utama aplikasi CRUD dompet.</p>

            <?php if ($loginError !== '') : ?>
                <div class="error"><?= htmlspecialchars($loginError) ?></div>
            <?php endif; ?>

            <label for="username">Username</label>
            <input id="username" type="text" name="username" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <button type="submit" name="login">Login</button>
            <div class="hint">Demo akun: <code>admin</code> / <code>12345</code></div>
        </form>
    </body>
    </html>
    <?php
    exit;
}

try {
    $database = new Database();
    $repository = new TransactionRepository($database->getConnection());
    $uploader = new FileUploader();

    if (isset($_POST['simpan'])) {
        $mode = $_POST['mode'] ?? 'create';
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $tanggal = trim($_POST['tanggal'] ?? '');
        $keterangan = trim($_POST['keterangan'] ?? '');
        $jenis = trim($_POST['jenis'] ?? '');
        $jumlah = isset($_POST['jumlah']) ? (int) $_POST['jumlah'] : 0;
        $existingFile = trim($_POST['existing_file'] ?? '');

        if ($tanggal === '' || $keterangan === '' || $jenis === '' || $jumlah <= 0) {
            redirectWithFlash($flash, 'Semua field wajib diisi dan jumlah harus lebih dari 0.', 'error');
        }

        if (!in_array($jenis, ['Pemasukan', 'Pengeluaran'], true)) {
            redirectWithFlash($flash, 'Jenis transaksi tidak valid.', 'error');
        }

        $uploadResult = $uploader->upload($_FILES['file'] ?? []);
        if (!$uploadResult['success']) {
            redirectWithFlash($flash, $uploadResult['message'], 'error');
        }

        $fileToSave = $uploadResult['filename'] !== '' ? $uploadResult['filename'] : $existingFile;
        $payload = [
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'foto' => $fileToSave,
        ];

        if ($mode === 'update' && $id > 0) {
            if ($uploadResult['filename'] !== '' && $existingFile !== '') {
                $uploader->delete($existingFile);
            }

            $repository->update($id, $payload);
            redirectWithFlash($flash, 'Data berhasil diperbarui.');
        }

        $repository->create($payload);
        redirectWithFlash($flash, 'Data berhasil disimpan.');
    }

    if (isset($_GET['hapus'])) {
        $idHapus = (int) $_GET['hapus'];
        $rowToDelete = $repository->findById($idHapus);

        if ($rowToDelete !== null) {
            $repository->delete($idHapus);
            $uploader->delete((string) ($rowToDelete['foto'] ?? ''));
        }

        redirectWithFlash($flash, 'Data berhasil dihapus.');
    }

    if (isset($_GET['edit'])) {
        $edit = $repository->findById((int) $_GET['edit']);
    }

    $flashData = $flash->get();
    $summary = $repository->getSummary();
    $data = $repository->getAll();
    $jumlahData = $data instanceof mysqli_result ? $data->num_rows : 0;
} catch (Throwable $exception) {
    $databaseError = $exception->getMessage();
    $flashData = ['message' => '', 'type' => 'success'];
    $summary = ['total_masuk' => 0, 'total_keluar' => 0];
    $data = false;
    $jumlahData = 0;
}

$totalMasuk = (int) ($summary['total_masuk'] ?? 0);
$totalKeluar = (int) ($summary['total_keluar'] ?? 0);
$saldoAkhir = $totalMasuk - $totalKeluar;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Dompet CRUD</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, 0.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.18), transparent 25%),
                #f8fafc;
            color: #0f172a;
        }

        .header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 22px 18px;
        }

        .header-inner,
        .main {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
        }

        .header-inner {
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
            color: #475569;
        }

        .logout {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 999px;
            background: #0f172a;
            color: #ffffff;
            text-decoration: none;
        }

        .main {
            padding: 24px 0 40px;
        }

        .flash {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-weight: 700;
        }

        .flash.success {
            background: #dcfce7;
            color: #166534;
        }

        .flash.error {
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
            padding: 18px;
            border-radius: 18px;
            color: #ffffff;
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        }

        .card strong {
            display: block;
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
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
            margin-top: 0;
            margin-bottom: 8px;
        }

        .panel p {
            margin-top: 0;
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
            font-weight: 700;
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #ffffff;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn-primary,
        .btn-secondary,
        .link-action {
            display: inline-block;
            padding: 12px 18px;
            border: none;
            border-radius: 999px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 700;
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
            color: #0f766e;
            text-decoration: none;
            font-weight: 700;
            margin-right: 10px;
        }

        .helper {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <div>
                <h1 class="page-title">Aplikasi Dompet Harian</h1>
                <p class="subtitle">Halaman utama setelah login berhasil. Kelola data pemasukan, pengeluaran, dan file bukti dalam satu form.</p>
            </div>
            <div>
                <div style="margin-bottom: 8px;">Login sebagai <strong><?= htmlspecialchars($auth->user()) ?></strong></div>
                <a class="logout" href="?logout=true">Logout</a>
            </div>
        </div>
    </header>

    <main class="main">
        <?php if ($databaseError !== '') : ?>
            <div class="flash error"><?= htmlspecialchars($databaseError) ?></div>
        <?php endif; ?>

        <?php if (($flashData['message'] ?? '') !== '') : ?>
            <div class="flash <?= htmlspecialchars($flashData['type'] ?? 'success') ?>"><?= htmlspecialchars($flashData['message']) ?></div>
        <?php endif; ?>

        <section class="cards">
            <div class="card cyan">
                <strong>Saldo Akhir</strong>
                <span>Rp <?= number_format($saldoAkhir, 0, ",", ".") ?></span>
            </div>
            <div class="card green">
                <strong>Total Pemasukan</strong>
                <span>Rp <?= number_format($totalMasuk, 0, ",", ".") ?></span>
            </div>
            <div class="card orange">
                <strong>Total Pengeluaran</strong>
                <span>Rp <?= number_format($totalKeluar, 0, ",", ".") ?></span>
            </div>
            <div class="card slate">
                <strong>Laba / Rugi</strong>
                <span>Rp <?= number_format($saldoAkhir, 0, ",", ".") ?></span>
            </div>
        </section>

        <section class="panel">
            <h2><?= $edit ? "Edit Data Transaksi" : "Input Data Transaksi" ?></h2>
            <p>Tombol <strong>Simpan</strong> di bawah memproses penyimpanan data CRUD dan upload file secara bersamaan sesuai soal.</p>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= (int) ($edit["id"] ?? 0) ?>">
                <input type="hidden" name="mode" value="<?= $edit ? "update" : "create" ?>">
                <input type="hidden" name="existing_file" value="<?= htmlspecialchars($edit["foto"] ?? "") ?>">

                <div class="form-grid">
                    <div>
                        <label for="tanggal">Tanggal</label>
                        <input id="tanggal" type="date" name="tanggal" value="<?= htmlspecialchars($edit["tanggal"] ?? "") ?>" required>
                    </div>

                    <div>
                        <label for="jenis">Jenis</label>
                        <select id="jenis" name="jenis" required>
                            <option value="">Pilih jenis transaksi</option>
                            <option value="Pemasukan" <?= ($edit["jenis"] ?? "") === "Pemasukan" ? "selected" : "" ?>>Pemasukan</option>
                            <option value="Pengeluaran" <?= ($edit["jenis"] ?? "") === "Pengeluaran" ? "selected" : "" ?>>Pengeluaran</option>
                        </select>
                    </div>

                    <div class="full">
                        <label for="keterangan">Keterangan</label>
                        <input id="keterangan" type="text" name="keterangan" value="<?= htmlspecialchars($edit["keterangan"] ?? "") ?>" placeholder="Contoh: pembayaran listrik / penjualan produk" required>
                    </div>

                    <div>
                        <label for="jumlah">Jumlah</label>
                        <input id="jumlah" type="number" name="jumlah" min="1" value="<?= htmlspecialchars((string) ($edit["jumlah"] ?? "")) ?>" required>
                    </div>

                    <div>
                        <label for="file">Upload File</label>
                        <input id="file" type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <div class="helper">Format: JPG, PNG, PDF, DOC, DOCX.</div>
                        <?php if (!empty($edit["foto"])) : ?>
                            <div class="helper">File saat ini: <a href="<?= AppConfig::UPLOAD_PATH . rawurlencode($edit["foto"]) ?>" target="_blank"><?= htmlspecialchars($edit["foto"]) ?></a></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn-primary" type="submit" name="simpan">Simpan</button>
                    <?php if ($edit) : ?>
                        <a class="btn-secondary" href="dompet.php">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="panel">
            <h2>Data Laporan</h2>
            <p>Bagian ini merepresentasikan fungsi Read, sedangkan kolom aksi menyediakan Update dan Delete.</p>

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
                        <?php if ($jumlahData > 0 && $data instanceof mysqli_result) : ?>
                            <?php $no = 1; ?>
                            <?php while ($row = $data->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row["tanggal"]) ?></td>
                                    <td><?= htmlspecialchars($row["keterangan"]) ?></td>
                                    <td>
                                        <span class="badge <?= $row["jenis"] === "Pemasukan" ? "in" : "out" ?>">
                                            <?= htmlspecialchars($row["jenis"]) ?>
                                        </span>
                                    </td>
                                    <td>Rp <?= number_format((int) $row["jumlah"], 0, ",", ".") ?></td>
                                    <td>
                                        <?php if (!empty($row["foto"])) : ?>
                                            <a href="<?= AppConfig::UPLOAD_PATH . rawurlencode($row["foto"]) ?>" target="_blank">Lihat File</a>
                                        <?php else : ?>
                                            Tidak ada file
                                        <?php endif; ?>
                                    </td>
                                    <td class="table-actions">
                                        <a href="?edit=<?= (int) $row["id"] ?>">Edit</a>
                                        <a href="?hapus=<?= (int) $row["id"] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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
