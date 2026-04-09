<?php
session_start();
require 'koneksi.php';
require 'Keuangan.php';

// PROTEKSI: Harus login dulu
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$lib = new Keuangan($conn);

// PROSES SIMPAN (CREATE)
if (isset($_POST['simpan'])) {
    // Tidak ada lagi $_FILES, hanya mengambil teks/angka
    $lib->simpanData($_POST['tgl'], $_POST['ket'], $_POST['jenis'], $_POST['jumlah']);
    
    // Refresh halaman agar data baru muncul
    header("Location: dompet.php"); 
    exit();
}

// PROSES HAPUS (DELETE)
if (isset($_GET['hapus'])) {
    $lib->hapus($_GET['hapus']);
    header("Location: dompet.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Keuangan - NaviBiz</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .flex-container { display: flex; gap: 20px; margin-top: 20px; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff; }
        .form-input { width: 100%; padding: 8px; margin: 8px 0 15px 0; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #0056b3; color: white; }
        .btn-simpan { background: #28a745; color: white; border: none; padding: 10px 15px; cursor: pointer; width: 100%; border-radius: 5px; font-weight: bold; }
        .btn-simpan:hover { background: #218838; }
        .btn-hapus { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px; }
        .btn-hapus:hover { background: #c82333; }
        .logout { float: right; color: #dc3545; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="logout.php" class="logout">Keluar (Logout)</a>
    <h2>Selamat Datang, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengguna'; ?>!</h2>
    <hr>

    <div class="flex-container">
        <div class="card" style="flex: 1;">
            <h3>➕ Input Transaksi</h3>
            <form method="POST" action="">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tgl" class="form-input" required>
                
                <label>Keterangan</label>
                <input type="text" name="ket" class="form-input" placeholder="Contoh: Beli bahan baku" required>
                
                <label>Jenis Transaksi</label>
                <select name="jenis" class="form-input" required>
                    <option value="Pemasukan">Pemasukan</option>
                    <option value="Pengeluaran">Pengeluaran</option>
                </select>
                
                <label>Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="form-input" placeholder="Contoh: 500000" required>
                
                <button type="submit" name="simpan" class="btn-simpan">SIMPAN DATA</button>
            </form>
        </div>

        <div class="card" style="flex: 2; overflow-x: auto;">
            <h3>📊 Riwayat Transaksi</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $data = $lib->getAll();
                    
                    if ($data && $data->num_rows > 0) {
                        while($row = $data->fetch_assoc()) {
                            $jumlah_format = number_format((float)$row['jumlah'], 0, ',', '.');
                            
                            echo "<tr>
                                <td>{$row['tanggal']}</td>
                                <td>{$row['keterangan']}</td>
                                <td>{$row['jenis']}</td>
                                <td>Rp {$jumlah_format}</td>
                                <td>
                                    <a href='dompet.php?hapus={$row['id']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")' class='btn-hapus'>Hapus</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data transaksi.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>