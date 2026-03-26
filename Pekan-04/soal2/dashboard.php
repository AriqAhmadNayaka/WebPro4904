<?php
require 'db.php';

// Ambil ID dari URL
$user_id = $_GET['id'] ?? null;

// Jika tidak ada ID di URL, paksa kembali ke login
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Ambil Nama User dari Database
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$nama = $user['name'] ?? "Guest";

// Simpan Catatan Baru (Create)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['keluhan'])) {
    $tanggal = $_POST['tanggal'];
    $keluhan = $conn->real_escape_string($_POST['keluhan']);

    $ins = $conn->prepare("INSERT INTO catatan_medis (id_user, tanggal, keluhan) VALUES (?, ?, ?)");
    $ins->bind_param("iss", $user_id, $tanggal, $keluhan);
    $ins->execute();

    // Redirect kembali agar data tidak terkirim dua kali saat refresh
    header("Location: dashboard.php?id=$user_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Nuids</title>
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h2>Halo, <?= htmlspecialchars($nama) ?>!</h2>
    <p>ID Pasien Anda: #<?= htmlspecialchars($user_id) ?></p>

    <div class="card">
        <h3>Tambah Catatan Konsultasi</h3>
        <form method="POST">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
            <label>Keluhan</label>
            <textarea name="keluhan" placeholder="Apa yang Anda rasakan hari ini?" required></textarea>
            <button type="submit">Simpan Catatan</button>
        </form>
    </div>

    <div class="card">
        <h3>Riwayat Kesehatan Anda</h3>
        <?php
        $sql = "SELECT * FROM catatan_medis WHERE id_user = ? ORDER BY tanggal DESC";
        $stmt_list = $conn->prepare($sql);
        $stmt_list->bind_param("i", $user_id);
        $stmt_list->execute();
        $riwayat = $stmt_list->get_result();

        if ($riwayat->num_rows > 0) {
            echo "<ul>";
            while ($row = $riwayat->fetch_assoc()) {
                echo "<li>[{$row['tanggal']}] - {$row['keluhan']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Belum ada riwayat catatan.</p>";
        }
        ?>
    </div>

    <br>
    <a href="login.php" style="color: red;">Keluar / Logout</a>
    
    <script src="main.js"></script>
</body>
</html>