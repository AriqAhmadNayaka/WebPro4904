```php
<?php
session_start();

if(!isset($_SESSION['laporan'])){
    $_SESSION['laporan'] = [
        ["2024-09-01","Penjualan Kopi","Pemasukan",1500000],
        ["2024-09-01","Pembelian Bahan","Pengeluaran",500000]
    ];
}

if(isset($_POST['simpan'])){

    $tgl = $_POST['tanggal'];
    $ket = $_POST['keterangan'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];

    if($tgl != "" && $ket != "" && $jumlah != ""){
        $_SESSION['laporan'][] = [$tgl,$ket,$jenis,$jumlah];
    }
}

$totalMasuk = 0;
$totalKeluar = 0;

foreach($_SESSION['laporan'] as $data){

    if($data[2] == "Pemasukan"){
        $totalMasuk += $data[3];
    }else{
        $totalKeluar += $data[3];
    }
}

$saldoAkhir = $totalMasuk - $totalKeluar;
$labaRugi = $saldoAkhir;

?>
<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Penjualan & Keuangan</title>

<style>

body{
font-family:Poppins, sans-serif;
background:#ebfbfa;
margin:0;
}

.header{
background:white;
padding:20px;
text-align:center;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

.page-title{
font-size:28px;
font-weight:700;
color:#3a71ff;
}

.main{
max-width:1200px;
margin:auto;
padding:20px;
}

.row{
display:flex;
flex-wrap:wrap;
gap:16px;
}

.col-3{
flex:0 0 calc(25% - 12px);
}

.card-stats{
padding:15px;
border-radius:12px;
color:white;
}

.card-red{background:#dc2626;}
.card-blue{background:#2563eb;}
.card-yellow{background:#facc15;color:black;}
.card-green{background:#16a34a;}
.card-purple{background:#7c3aed;}
.card-teal{background:#0f766e;}
.card-orange{background:#ea580c;}
.card-maroon{background:#b91c1c;}

.value{
font-size:18px;
font-weight:600;
}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
}

th,td{
padding:8px;
border-bottom:1px solid #ddd;
}

thead{
background:#eef2ff;
}

.form-card{
background:white;
padding:15px;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

input,select{
width:100%;
padding:8px;
margin-bottom:10px;
}

button{
padding:8px 14px;
border:none;
border-radius:20px;
cursor:pointer;
}

.btn{
background:#3a71ff;
color:white;
}

.table-card{
background:white;
padding:15px;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
flex:1;
}

</style>
</head>

<body>

<header class="header">
<h1 class="page-title">Laporan Keuangan</h1>
</header>

<main class="main">

<div class="row">

<div class="col-3">
<div class="card-stats card-purple">
<small>Saldo akhir</small>
<div class="value">Rp <?php echo number_format($saldoAkhir,0,",","."); ?></div>
</div>
</div>

<div class="col-3">
<div class="card-stats card-teal">
<small>Total pemasukan</small>
<div class="value">Rp <?php echo number_format($totalMasuk,0,",","."); ?></div>
</div>
</div>

<div class="col-3">
<div class="card-stats card-orange">
<small>Total pengeluaran</small>
<div class="value">Rp <?php echo number_format($totalKeluar,0,",","."); ?></div>
</div>
</div>

<div class="col-3">
<div class="card-stats card-maroon">
<small>Laba / Rugi</small>
<div class="value">Rp <?php echo number_format($labaRugi,0,",","."); ?></div>
</div>
</div>

</div>

<section style="margin-top:30px">

<div class="row">

<div class="form-card">

<h3>Input Data</h3>

<form method="POST">

<label>Tanggal</label>
<input type="date" name="tanggal" required>

<label>Keterangan</label>
<input type="text" name="keterangan" required>

<label>Jenis</label>
<select name="jenis">
<option value="Pemasukan">Pemasukan</option>
<option value="Pengeluaran">Pengeluaran</option>
</select>

<label>Jumlah</label>
<input type="number" name="jumlah" required>

<button type="submit" name="simpan" class="btn">Simpan</button>

</form>

</div>

<div class="table-card">

<h3>Data Laporan</h3>

<table>

<thead>
<tr>
<th>Tanggal</th>
<th>Keterangan</th>
<th>Jenis</th>
<th>Jumlah</th>
</tr>
</thead>

<tbody>

<?php
foreach($_SESSION['laporan'] as $data){
?>

<tr>
<td><?php echo $data[0]; ?></td>
<td><?php echo $data[1]; ?></td>
<td><?php echo $data[2]; ?></td>
<td><?php echo number_format($data[3],0,",","."); ?></td>
</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</section>

</main>

</body>
</html>
```
