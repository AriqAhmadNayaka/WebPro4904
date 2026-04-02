<?php
session_start();

$_SESSION["user"] ?? header("Location: login.php") & exit;

$user = $_SESSION["user"];

// Koneksi database dari file terpisah
require_once "koneksi.php";

$edit = null;

// DELETE - hapus data pasien + file dokumen
if (isset($_GET["hapus"])) {
    $id   = (int) $_GET["hapus"];
    $stmt = $pdo->prepare("SELECT dokumen FROM data_pasien WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        if ($row["dokumen"] && file_exists("uploads/" . $row["dokumen"])) {
            unlink("uploads/" . $row["dokumen"]);
        }
        $pdo->prepare("DELETE FROM data_pasien WHERE id = ?")->execute([$id]);
    }
    header("Location: beranda.php");
    exit;
}

// EDIT - ambil data untuk ditampilkan di form
if (isset($_GET["edit"])) {
    $stmt = $pdo->prepare("SELECT * FROM data_pasien WHERE id = ?");
    $stmt->execute([(int) $_GET["edit"]]);
    $edit = $stmt->fetch();
}

// SIMPAN - CREATE / UPDATE + UPLOAD FILE
// Satu tombol Simpan untuk semua proses
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id      = (int) ($_POST["id"] ?? 0);
    $nama    = htmlspecialchars(trim($_POST["nama"]));
    $usia    = (int) $_POST["usia"];
    $keluhan = htmlspecialchars(trim($_POST["keluhan"]));
    $tanggal = date("Y-m-d");

    $dokNama = null;

    // PROSES UPLOAD FILE
    if (isset($_FILES["dokumen"]) && $_FILES["dokumen"]["error"] === UPLOAD_ERR_OK) {
        if (!is_dir("uploads")) mkdir("uploads", 0755, true);

        $ext     = strtolower(pathinfo($_FILES["dokumen"]["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif", "webp", "pdf"];

        if ($_FILES["dokumen"]["size"] <= 5 * 1024 * 1024 && in_array($ext, $allowed)) {
            $dokNama = "doc_" . uniqid() . "_" . time() . "." . $ext;
            if (!move_uploaded_file($_FILES["dokumen"]["tmp_name"], "uploads/" . $dokNama)) {
                $dokNama = null;
            }
        }
    }

    if ($id > 0) {
        // UPDATE 
        if ($dokNama) {
            // hapus dokumen lama
            $old = $pdo->prepare("SELECT dokumen FROM data_pasien WHERE id = ?");
            $old->execute([$id]);
            $oldRow = $old->fetch();
            if ($oldRow && $oldRow["dokumen"] && file_exists("uploads/" . $oldRow["dokumen"])) {
                unlink("uploads/" . $oldRow["dokumen"]);
            }
            $pdo->prepare("UPDATE data_pasien SET nama=?, usia=?, keluhan=?, dokumen=? WHERE id=?")
                ->execute([$nama, $usia, $keluhan, $dokNama, $id]);
        } else {
            $pdo->prepare("UPDATE data_pasien SET nama=?, usia=?, keluhan=? WHERE id=?")
                ->execute([$nama, $usia, $keluhan, $id]);
        }
    } else {
        //  CREATE 
        $pdo->prepare("INSERT INTO data_pasien (nama, usia, keluhan, dokumen, tanggal) VALUES (?, ?, ?, ?, ?)")
            ->execute([$nama, $usia, $keluhan, $dokNama, $tanggal]);
    }

    header("Location: beranda.php");
    exit;
}

// READ - ambil semua data dari database
$stmt = $pdo->query("SELECT * FROM data_pasien ORDER BY created_at DESC");
$data_pasien = $stmt->fetchAll();
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
            <p><?php echo count($data_pasien); ?> data pasien</p>
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

    <?php // form CRUD + upload file satu tombol Simpan ?>
    <div class="form-box" id="form-input">
        <h3><?php echo $edit ? "Edit Data Pasien" : "Input Data Pasien"; ?></h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Nama Pasien</label>
                <input type="text" name="nama" placeholder="Masukkan nama pasien"
                       value="<?php echo htmlspecialchars($edit['nama'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Usia</label>
                <input type="number" name="usia" placeholder="Masukkan usia"
                       value="<?php echo htmlspecialchars($edit['usia'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Keluhan</label>
                <textarea name="keluhan" placeholder="Tuliskan keluhan pasien..." required><?php echo htmlspecialchars($edit['keluhan'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Upload Dokumen (JPG, PNG, PDF - Max 5MB)</label>
                <input type="file" name="dokumen" accept="image/*,.pdf">
                <?php if ($edit && $edit['dokumen']): ?>
                    <small>File saat ini: <a href="uploads/<?php echo htmlspecialchars($edit['dokumen']); ?>" target="_blank"><?php echo htmlspecialchars($edit['dokumen']); ?></a> (kosongkan jika tidak ganti)</small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn-submit">Simpan Data</button>
            <?php if ($edit): ?>
                <a href="beranda.php" class="btn-submit" style="background:#888;text-decoration:none;display:inline-block;text-align:center;margin-left:8px;">Batal</a>
            <?php endif; ?>
        </form>
    </div>

    <?php // tabel daftar pasien read ?>
    <div class="tabel-box" id="tabel-pasien">
        <h3>Daftar Pasien (<?php echo count($data_pasien); ?> data)</h3>

        <?php if (count($data_pasien) == 0): ?>
            <p class="kosong">Belum ada data pasien.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Usia</th>
                    <th>Keluhan</th>
                    <th>Tanggal</th>
                    <th>Dokumen</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($data_pasien as $i => $p): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($p["nama"]); ?></td>
                        <td><?php echo $p["usia"]; ?> thn</td>
                        <td><?php echo htmlspecialchars($p["keluhan"]); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($p["tanggal"])); ?></td>
                        <td>
                            <?php if ($p["dokumen"] && file_exists("uploads/" . $p["dokumen"])): ?>
                                <a href="uploads/<?php echo htmlspecialchars($p['dokumen']); ?>" target="_blank">Lihat</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $p['id']; ?>#form-input" class="btn-hapus" style="background:#f0ad4e;">Edit</a>
                            <a href="?hapus=<?php echo $p['id']; ?>"
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
