<?php
session_start();

$_SESSION["user"] ?? header("Location: login.php") & exit;

$user = $_SESSION["user"];

$_SESSION["data_pasien"] = $_SESSION["data_pasien"] ?? [];

// post: tambah data pasien (create)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["data_pasien"][] = [
        "nama"    => htmlspecialchars(trim($_POST["nama"])),
        "usia"    => htmlspecialchars(trim($_POST["usia"])),
        "keluhan" => htmlspecialchars(trim($_POST["keluhan"])),
        "tanggal" => date("d-m-Y"),
    ];
    header("Location: beranda.php");
    exit;
}

// get: hapus data pasien
if (isset($_GET["hapus"])) {
    array_splice($_SESSION["data_pasien"], (int)$_GET["hapus"], 1);
    header("Location: beranda.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LifeTrack - Beranda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <span class="bulb"></span>
        LifeTrack
    </div>
    <div><?php echo htmlspecialchars($user); ?></div>
</nav>

<div class="konten">

    <h1>Halo, <?php echo htmlspecialchars($user); ?></h1>
    <p class="sub">Selamat datang kembali di LifeTrack</p>

    <div class="boxes">
        <div class="box">
            <h3>Input Data Pasien</h3>
            <p>Masukkan data pasien baru</p>
            <a href="#form-input">Input Data</a>
        </div>
        <div class="box">
            <h3>Daftar Pasien</h3>
            <p><?php echo count($_SESSION["data_pasien"]); ?> data pasien</p>
            <a href="#tabel-pasien">Lihat Daftar</a>
        </div>
        <div class="box">
            <h3>Jadwal</h3>
            <p>25 Desember 2025</p>
            <a href="#">Lihat Detail</a>
        </div>
        <div class="box">
            <h3>Nutrisi dan Gizi</h3>
            <p>Panduan nutrisi pasien</p>
            <a href="#">Lihat Detail</a>
        </div>
    </div>

    <?php // form tambah data pasien-create (POST)?>
    <div class="form-box" id="form-input">
        <h3>Input Data Pasien</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Pasien</label>
                <input type="text" name="nama" placeholder="Masukkan nama pasien" required>
            </div>
            <div class="form-group">
                <label>Usia</label>
                <input type="number" name="usia" placeholder="Masukkan usia" required>
            </div>
            <div class="form-group">
                <label>Keluhan</label>
                <textarea name="keluhan" placeholder="Tuliskan keluhan pasien..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">Simpan Data</button>
        </form>
    </div>

    <?php // tabel daftar pasien-read ?>
    <div class="tabel-box" id="tabel-pasien">
        <h3>Daftar Pasien (<?php echo count($_SESSION["data_pasien"]); ?> data)</h3>

        <?php if (count($_SESSION["data_pasien"]) == 0): ?>
            <p class="kosong">Belum ada data pasien.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Usia</th>
                    <th>Keluhan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($_SESSION["data_pasien"] as $i => $p): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $p["nama"]; ?></td>
                        <td><?php echo $p["usia"]; ?> thn</td>
                        <td><?php echo $p["keluhan"]; ?></td>
                        <td><?php echo $p["tanggal"]; ?></td>
                        <td>
                            <?php // get: hapus lewat URL ?>
                            <a href="?hapus=<?php echo $i; ?>"
                               class="btn-hapus"
                               onclick="return confirm('Hapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
