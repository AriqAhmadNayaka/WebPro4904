<?php
session_start();

if (!isset($_SESSION['kegiatan_anak'])) {//melihat apakah session untuk kegiatan anak sudah ada, jika belum maka buat dengan data awal
    $_SESSION['kegiatan_anak'] = [//data awal untuk kegiatan anak
        ['tgl' => '2023-10-01', 'aktivitas' => 'Terapi Wicara', 'status' => 'Selesai'],//data kegiatan pertama dengan tanggal, nama aktivitas, dan statusnya
        ['tgl' => '2023-10-03', 'aktivitas' => 'Terapi Okupasi', 'status' => 'Selesai'],//data kegiatan kedua dengan tanggal, nama aktivitas, dan statusnya
        ['tgl' => '2023-10-05', 'aktivitas' => 'Latihan Kemandirian', 'status' => 'Mendatang']//data kegiatan ketiga dengan tanggal, nama aktivitas, dan statusnya
    ];
}

// 2. Logika CREATE: Jika form disubmit, tambah data ke session
if (isset($_POST['submit_kegiatan'])) {//melihat apakah form untuk tambah kegiatan sudah disubmit
    $baru = [
        'tgl' => $_POST['tanggal'],//tanggal kegiatan yang diinputkan
        'aktivitas' => $_POST['aktivitas'],//nama aktivitas yang diinputkan
        'status' => $_POST['status']//status kegiatan yang diinputkan
    ];
    array_push($_SESSION['kegiatan_anak'], $baru);//data baru ditambahkan ke kegiatan anak
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua - InkluSkill</title>//judul  halaman dashboard orang tua
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>//link untuk css
    <link rel="stylesheet" href="styles.css">//link untuk css
    <style>
        /* Tambahan style dikit biar form-nya cakep */
        .input-group { margin-bottom: 10px; }
        .input-group input, .input-group select { padding: 8px; border-radius: 5px; border: 1px solid #ddd; }
        .btn-tambah { background: #6a11cb; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>

<div class="sidebar">//sidebar untuk navigasinya di sebelah kiri yang silih sambung gitu
    <h2 class="logo">InkluSkill</h2>//logo inluskill
    <a href="#" class="active"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="#"><i class="ri-user-3-line"></i>Data Anak</a>
    <a href="#"><i class="ri-calendar-check-line"></i>Jadwal</a>
    <button class="logout"><i class="ri-logout-circle-line"></i>Logout</button>
</div>

<div class="content">//kalo ini di bagian kanan
    <div class="header">
        <h1><i class="ri-bar-chart-fill"></i> Statistik Anak</h1>//judulnya
        <p>Ringkasan perkembangan & aktivitas anak Anda</p>//subjudulnya
    </div>

    <div class="big-card">
        <h3><i class="ri-add-circle-line"></i> Tambah Kegiatan Baru</h3>//judul buat form
        <form method="POST" action="">
            <div class="input-group">
                <input type="date" name="tanggal" required>//input untuk tanggal kegiatan
                <input type="text" name="aktivitas" placeholder="Nama Kegiatan (misal: Terapi)" required>//input untuk nama kegiatan
                <select name="status">
                    <option value="Mendatang">Mendatang</option>//
                    <option value="Selesai">Selesai</option>
                </select>
                <button type="submit" name="submit_kegiatan" class="btn-tambah">Tambah Data</button>//button untuk submit form tambah kegiatan
            </div>
        </form>
    </div>

    <div class="big-card">
        <h3><i class="ri-history-line"></i> Riwayat & Jadwal Kegiatan</h3>//judul untuk tabel riwayat kegiatan
        <table>
            <thead>
                <tr>//baris tabel
                    <th>Tanggal</th>
                    <th>Aktivitas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['kegiatan_anak'] as $item) : ?>//untuk menampilan data anak
                <tr>
                    <td><?php echo $item['tgl']; ?></td>//menampilkan tanggal kegiatan
                    <td><?php echo $item['aktivitas']; ?></td>//menampilkan nama kegiatan
                    <td><span class="badge"><?php echo $item['status']; ?></span></td>//menampilkan status kegiatan dengan badge
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="big-card">
        <h3>Perkembangan Anak</h3>//judul untuk bagian perkembangan anak
        <div class="progress-item">
            <span>Komunikasi</span>//label untuk komunikasi
            <div class="progress-bar"><div class="fill" style="width: 70%;"></div></div>
        </div>
        <div class="progress-item">
            <span>Kemandirian</span>
            <div class="progress-bar"><div class="fill" style="width: 50%;"></div></div>
        </div>
    </div>

</div>

</body>
</html>