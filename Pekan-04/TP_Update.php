<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>

<body>
<div class="container">

<?php
include "TP.php";

// Fungsi filter input
function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Ambil data berdasarkan id
if (isset($_GET['ID'])) {
    $ID = input($_GET["ID"]);

    $sql = "SELECT * FROM stok barang WHERE ID=$ID";
    $hasil = mysqli_query($kon, $sql);
    $data = mysqli_fetch_assoc($hasil);
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ID = htmlspecialchars($_POST["ID"]);
    $Nama_Barang     = input($_POST["Nama_Barang"]);
    $QTY  = input($_POST["QTY"]);
    $Harga  = input($_POST["Harga"]);

    $sql = "UPDATE stok barang SET
            ID='$ID',
            Nama_Barang='$Nama_Barang',
            QTY='$QTY',
            Harga='$Harga',
            WHERE ID=$ID";

    $hasil = mysqli_query($kon, $sql);

    if ($hasil) {
        header("Location:TP_index.php");
    } else {
        echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";
    }
}
?>

<h2>Update Data</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <div class="form-group">
        <label>ID:</label>
        <input type="text" name="ID" class="form-control"
        value="<?php echo $data['ID']; ?>" required />
    </div>

    <div class="form-group">
        <label>Nama Barang:</label>
        <input type="text" name="Nama_Barang" class="form-control"
        value="<?php echo $data['Nama_Barang']; ?>" required />
    </div>

    <div class="form-group">
        <label>QTY:</label>
        <input type="text" name="QTY" class="form-control"
        value="<?php echo $data['QTY']; ?>" required />
    </div>

    <div class="form-group">
        <label>Harga:</label>
        <input type="text" name="Harga" class="form-control"
        value="<?php echo $data['Harga']; ?>" required />
    </div>

    <input type="hidden" name="ID" value="<?php echo $data['ID']; ?>" />

    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>

</div>
</body>
</html>