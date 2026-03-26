<?php
# ========================
# KONEKSI DATABASE
# ========================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_webprodata";

$conn = mysqli_connect($host, $user, $pass, $db);
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}

# ========================
# TAMBAH DATA
# ========================
if(isset($_POST['simpan'])){
    $tgl = $_POST['tanggal'];
    $ket = $_POST['keterangan'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];

    mysqli_query($conn,"INSERT INTO laporan 
    VALUES(NULL,'$tgl','$ket','$jenis','$jumlah')");
    header("Location: dompet.php");
}

# ========================
# HAPUS DATA
# ========================
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn,"DELETE FROM laporan WHERE id=$id");
    header("Location: dompet.php");
}

# ========================
# AMBIL DATA UNTUK EDIT
# ========================
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $q = mysqli_query($conn,"SELECT * FROM laporan WHERE id=$id");
    $edit = mysqli_fetch_assoc($q);
}

# ========================
# UPDATE DATA
# ========================
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $tgl = $_POST['tanggal'];
    $ket = $_POST['keterangan'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];

    mysqli_query($conn,"UPDATE laporan SET
        tanggal='$tgl',
        keterangan='$ket',
        jenis='$jenis',
        jumlah='$jumlah'
        WHERE id=$id");

    header("Location: dompet.php");
}

# ========================
# HITUNG TOTAL KEUANGAN
# ========================
$totalMasuk = 0;
$totalKeluar = 0;

$q = mysqli_query($conn,"SELECT * FROM laporan");
while($row = mysqli_fetch_assoc($q)){
    if($row['jenis'] == "Pemasukan"){
        $totalMasuk += $row['jumlah'];
    } else {
        $totalKeluar += $row['jumlah'];
    }
}

$saldoAkhir = $totalMasuk - $totalKeluar;
$labaRugi = $saldoAkhir;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Keuangan</title>

<style>
body{font-family:Poppins;background:#ebfbfa;margin:0;}
.header{background:white;padding:20px;text-align:center;box-shadow:0 5px 20px rgba(0,0,0,0.1);}
.page-title{font-size:28px;font-weight:700;color:#3a71ff;}
.main{max-width:1200px;margin:auto;padding:20px;}
.row{display:flex;flex-wrap:wrap;gap:16px;}
.card{flex:1;padding:15px;border-radius:12px;color:white;}
.purple{background:#7c3aed;}
.teal{background:#0f766e;}
.orange{background:#ea580c;}
.maroon{background:#b91c1c;}
.form-card,.table-card{background:white;padding:15px;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,0.1);margin-top:20px;}
table{width:100%;border-collapse:collapse;}
th,td{padding:8px;border-bottom:1px solid #ddd;}
button{padding:8px 14px;border:none;border-radius:20px;cursor:pointer;}
.btn{background:#3a71ff;color:white;}
</style>
</head>

<body>

<header class="header">
<h1 class="page-title">Laporan Keuangan</h1>
</header>

<main class="main">

<div class="row">
<div class="card purple">Saldo Akhir<br>Rp <?= number_format($saldoAkhir,0,",",".") ?></div>
<div class="card teal">Total Pemasukan<br>Rp <?= number_format($totalMasuk,0,",",".") ?></div>
<div class="card orange">Total Pengeluaran<br>Rp <?= number_format($totalKeluar,0,",",".") ?></div>
<div class="card maroon">Laba / Rugi<br>Rp <?= number_format($labaRugi,0,",",".") ?></div>
</div>

<div class="form-card">
<h3><?= $edit ? "Edit Data" : "Input Data" ?></h3>

<form method="POST">
<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

Tanggal
<input type="date" name="tanggal" value="<?= $edit['tanggal'] ?? '' ?>" required>

Keterangan
<input type="text" name="keterangan" value="<?= $edit['keterangan'] ?? '' ?>" required>

Jenis
<select name="jenis">
<option value="Pemasukan" <?= isset($edit)&&$edit['jenis']=="Pemasukan"?"selected":"" ?>>Pemasukan</option>
<option value="Pengeluaran" <?= isset($edit)&&$edit['jenis']=="Pengeluaran"?"selected":"" ?>>Pengeluaran</option>
</select>

Jumlah
<input type="number" name="jumlah" value="<?= $edit['jumlah'] ?? '' ?>" required>

<?php if($edit){ ?>
<button name="update" class="btn">Update</button>
<?php } else { ?>
<button name="simpan" class="btn">Simpan</button>
<?php } ?>
</form>
</div>

<div class="table-card">
<h3>Data Laporan</h3>

<table>
<tr>
<th>Tanggal</th>
<th>Keterangan</th>
<th>Jenis</th>
<th>Jumlah</th>
<th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn,"SELECT * FROM laporan ORDER BY tanggal DESC");
while($d = mysqli_fetch_assoc($data)){
?>
<tr>
<td><?= $d['tanggal'] ?></td>
<td><?= $d['keterangan'] ?></td>
<td><?= $d['jenis'] ?></td>
<td>Rp <?= number_format($d['jumlah'],0,",",".") ?></td>
<td>
<a href="?edit=<?= $d['id'] ?>">Edit</a> |
<a href="?hapus=<?= $d['id'] ?>" onclick="return confirm('Hapus data?')">Hapus</a>
</td>
</tr>
<?php } ?>

</table>
</div>

</main>
</body>
</html>