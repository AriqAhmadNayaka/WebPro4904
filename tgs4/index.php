<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'tgs4';
$tableName = 'stok';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$message = '';
$error = '';
$editData = null;
$items = [];

try {
    $conn = new mysqli($host, $user, $pass);
    $conn->set_charset('utf8mb4');
    $conn->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $conn->select_db($dbName);
    $conn->query("
        CREATE TABLE IF NOT EXISTS `$tableName` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama_barang VARCHAR(100) NOT NULL,
            qty INT NOT NULL DEFAULT 0,
            harga DECIMAL(12,2) NOT NULL DEFAULT 0
        )
    ");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $namaBarang = trim($_POST['nama_barang'] ?? '');
        $qty = isset($_POST['qty']) ? (int) $_POST['qty'] : 0;
        $harga = isset($_POST['harga']) ? (float) $_POST['harga'] : 0;

        if ($action === 'delete' && $id > 0) {
            $stmt = $conn->prepare("DELETE FROM `$tableName` WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            header('Location: index.php?status=deleted');
            exit;
        }

        if ($namaBarang === '' || $qty < 0 || $harga < 0) {
            throw new Exception('Nama barang wajib diisi, qty dan harga tidak boleh negatif.');
        }

        if ($action === 'update' && $id > 0) {
            $stmt = $conn->prepare("UPDATE `$tableName` SET nama_barang = ?, qty = ?, harga = ? WHERE id = ?");
            $stmt->bind_param('sidi', $namaBarang, $qty, $harga, $id);
            $stmt->execute();
            header('Location: index.php?status=updated');
            exit;
        }

        if ($action === 'create') {
            $stmt = $conn->prepare("INSERT INTO `$tableName` (nama_barang, qty, harga) VALUES (?, ?, ?)");
            $stmt->bind_param('sid', $namaBarang, $qty, $harga);
            $stmt->execute();
            header('Location: index.php?status=created');
            exit;
        }
    }

    if (isset($_GET['edit'])) {
        $id = (int) $_GET['edit'];
        $stmt = $conn->prepare("SELECT id, nama_barang, qty, harga FROM `$tableName` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $editData = $stmt->get_result()->fetch_assoc();
    }

    $result = $conn->query("SELECT id, nama_barang, qty, harga FROM `$tableName` ORDER BY id DESC");
    $items = $result->fetch_all(MYSQLI_ASSOC);

    $statusMessages = [
        'created' => 'Data barang berhasil ditambahkan.',
        'updated' => 'Data barang berhasil diperbarui.',
        'deleted' => 'Data barang berhasil dihapus.',
    ];
    $message = $statusMessages[$_GET['status'] ?? ''] ?? '';
} catch (Throwable $e) {
    $error = $e->getMessage();
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Stok Barang</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .container {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
            color: #0f172a;
        }

        .badge {
            padding: 10px 14px;
            border-radius: 6px;
            background: #dcfce7;
            color: #166534;
            font-weight: 700;
            white-space: nowrap;
        }

        .layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 20px;
            align-items: start;
        }

        .panel {
            background: #ffffff;
            border: 1px solid #dbe4ef;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        h2 {
            margin: 0 0 16px;
            font-size: 18px;
            color: #111827;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 11px 12px;
            margin-bottom: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        button,
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 10px 14px;
            border: 0;
            border-radius: 6px;
            background: #2563eb;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .button.secondary {
            background: #64748b;
        }

        .button.edit {
            background: #0891b2;
        }

        .danger {
            background: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #eef2ff;
            color: #1e293b;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .row-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .notice,
        .error {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 6px;
            font-weight: 700;
        }

        .notice {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty {
            padding: 28px;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 860px) {
            .header,
            .layout {
                display: block;
            }

            .badge {
                display: inline-block;
                margin-top: 12px;
            }

            .panel {
                margin-bottom: 20px;
            }

            table,
            thead,
            tbody,
            tr,
            th,
            td {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                border-bottom: 1px solid #e2e8f0;
                padding: 10px 0;
            }

            td {
                border-bottom: 0;
                padding: 8px 0;
            }

            td::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 4px;
                font-size: 12px;
                font-weight: 700;
                color: #64748b;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="header">
            <div>
                <h1>Aplikasi CRUD Stok Barang</h1>
                <p>Kelola data stok barang dengan field ID, nama barang, qty, dan harga.</p>
            </div>
            <div class="badge">Database: <?= e($dbName) ?></div>
        </div>

        <?php if ($message): ?>
            <div class="notice"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error">Error: <?= e($error) ?></div>
        <?php endif; ?>

        <div class="layout">
            <section class="panel">
                <h2><?= $editData ? 'Edit Barang' : 'Tambah Barang' ?></h2>
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?= e($editData['id']) ?>">
                    <?php endif; ?>

                    <label for="nama_barang">Nama Barang</label>
                    <input
                        type="text"
                        id="nama_barang"
                        name="nama_barang"
                        value="<?= e($editData['nama_barang'] ?? '') ?>"
                        placeholder="Contoh: Keyboard"
                        required
                    >

                    <label for="qty">Qty</label>
                    <input
                        type="number"
                        id="qty"
                        name="qty"
                        value="<?= e($editData['qty'] ?? '') ?>"
                        min="0"
                        placeholder="0"
                        required
                    >

                    <label for="harga">Harga</label>
                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        value="<?= e($editData['harga'] ?? '') ?>"
                        min="0"
                        step="0.01"
                        placeholder="0"
                        required
                    >

                    <div class="actions">
                        <button type="submit"><?= $editData ? 'Simpan Perubahan' : 'Tambah Barang' ?></button>
                        <?php if ($editData): ?>
                            <a class="button secondary" href="index.php">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="panel">
                <h2>Daftar Stok Barang</h2>
                <?php if ($items): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td data-label="ID"><?= e($item['id']) ?></td>
                                    <td data-label="Nama Barang"><?= e($item['nama_barang']) ?></td>
                                    <td data-label="Qty"><?= e($item['qty']) ?></td>
                                    <td data-label="Harga">Rp <?= number_format((float) $item['harga'], 0, ',', '.') ?></td>
                                    <td data-label="Aksi">
                                        <div class="row-actions">
                                            <a class="button edit" href="index.php?edit=<?= e($item['id']) ?>">Edit</a>
                                            <form method="post" action="index.php" onsubmit="return confirm('Hapus data barang ini?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                                <button class="danger" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty">Belum ada data barang. Tambahkan data pertama dari form di sebelah kiri.</div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>
</html>
