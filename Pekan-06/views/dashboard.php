<?php
session_start();
if (!isset($_SESSION['login'])) header("Location: login.php");

include '../config/Database.php';
include '../models/Patient.php';

$db = (new Database())->connect();
$data = (new Patient($db))->getAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
<h2>Data Pasien</h2>

<a href="form_patient.php" class="btn btn-success mb-3">Tambah</a>
<a href="../logout.php" class="btn btn-danger mb-3">Logout</a>

<table class="table table-bordered">
<tr><th>Nama</th><th>Umur</th><th>Penyakit</th><th>File</th><th>Aksi</th></tr>

<?php while($r = $data->fetch_assoc()): ?>
<tr>
<td><?= $r['nama'] ?></td>
<td><?= $r['umur'] ?></td>
<td><?= $r['penyakit'] ?></td>
<td>
<?php if ($r['file']): ?>
    <img src="../uploads/<?= $r['file'] ?>" width="80">
<?php else: ?>
    Tidak ada file
<?php endif; ?>
</td>
<td>
<a href="../controllers/PatientController.php?delete=<?= $r['id'] ?>" class="btn btn-danger btn-sm">Hapus</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

</body>
</html>