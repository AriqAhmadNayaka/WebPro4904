<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>

<title>
    Stok Barang
</title>

<body>

<nav class="navbar navbar-dark bg-dark">
    <span class="navbar-brand mb-0 h1">Stok Barang</span>
</nav>

<div class="container">
    <br>
    <h4><center>Stok Barang</center></h4>

    <?php
    include "TP.php";

    // Proses hapus data
    if (isset($_GET['ID'])) {
        $ID = htmlspecialchars($_GET["ID"]);

        $sql = "DELETE FROM stok barang WHERE ID='$ID'";
        $hasil = mysqli_query($kon, $sql);

        if ($hasil) {
            header("Location:TP_index.php");
        } else {
            echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";
        }
    }
    ?>

    <table class="my-3 table table-bordered">
        <thead>
            <tr class="table-primary">
                <th>ID</th>
                <th>Nama Barang</th>
                <th>QTY</th>
                <th>Harga</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>

        <?php
        include "TP.php";
        $sql = "SELECT * FROM stok barang ORDER BY ID DESC";
        $hasil = mysqli_query($kon, $sql);
        $no = 0;

        while ($data = mysqli_fetch_array($hasil)) {
            $no++;
        ?>
        <tbody>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data["ID"]; ?></td>
                <td><?php echo $data["Nama_Barang"]; ?></td>
                <td><?php echo $data["QTY"]; ?></td>
                <td><?php echo $data["Harga"]; ?></td>
                <td>
                    <a href="update.php?ID=<?php echo htmlspecialchars($data['ID']); ?>" class="btn btn-warning">Edit</a>
                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?ID=<?php echo $data['ID']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        </tbody>
        <?php
        }
        ?>
    </table>

    <a href="TP_Create.php" class="btn btn-primary" role="button">Tambah Data</a>
</div>

</body>
</html>