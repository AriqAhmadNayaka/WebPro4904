<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location:login.php");
}
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Data Barang</h3>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- FORM -->
    <div class="card p-3 mb-4 shadow">
        <form action="proses.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Barang" required>
                </div>
                <div class="col">
                    <input type="number" name="qty" class="form-control" placeholder="Qty" required>
                </div>
                <div class="col">
                    <input type="number" name="harga" class="form-control" placeholder="Harga" required>
                </div>
                <div class="col">
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="col">
                    <button type="submit" name="simpan" class="btn btn-primary w-100">Simpan</button>
                </div>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card p-3 shadow">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $data = mysqli_query($conn, "SELECT * FROM barang");
            while ($d = mysqli_fetch_array($data)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $d['nama_barang'] ?></td>
                    <td><?= $d['qty'] ?></td>
                    <td><?= $d['harga'] ?></td>
                    <td>
                        <img src="upload/<?= $d['file'] ?>" width="80">
                    </td>
                    <td>
                        <a href="proses.php?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>