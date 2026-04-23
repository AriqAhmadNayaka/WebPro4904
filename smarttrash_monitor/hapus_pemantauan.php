<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$userId = current_user_id();

if ($id <= 0) {
    set_flash('error', 'ID pemantauan tidak valid.');
    header('Location: pemantauan.php');
    exit;
}

$selectStmt = mysqli_prepare($koneksi, 'SELECT foto_bukti FROM monitor WHERE id = ? AND user_id = ? LIMIT 1');
mysqli_stmt_bind_param($selectStmt, 'ii', $id, $userId);
mysqli_stmt_execute($selectStmt);
$selectResult = mysqli_stmt_get_result($selectStmt);
$laporan = $selectResult ? mysqli_fetch_assoc($selectResult) : null;
mysqli_stmt_close($selectStmt);

if (!$laporan) {
    set_flash('error', 'Data pemantauan tidak ditemukan.');
    header('Location: pemantauan.php');
    exit;
}

$deleteStmt = mysqli_prepare($koneksi, 'DELETE FROM monitor WHERE id = ? AND user_id = ?');
mysqli_stmt_bind_param($deleteStmt, 'ii', $id, $userId);

if (mysqli_stmt_execute($deleteStmt)) {
    mysqli_stmt_close($deleteStmt);

    if (!empty($laporan['foto_bukti'])) {
        $filePath = __DIR__ . '/' . $laporan['foto_bukti'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    set_flash('success', 'Pemantauan dan file bukti berhasil dihapus.');
    header('Location: pemantauan.php');
    exit;
}

mysqli_stmt_close($deleteStmt);
set_flash('error', 'Pemantauan gagal dihapus.');
header('Location: pemantauan.php');
exit;
