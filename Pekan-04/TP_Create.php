<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>

<body>
<div class="container">

<?php
// Include file koneksi
include "TP.php";

// Fungsi untuk membersihkan input
function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ID     = input($_POST["ID"]);
    $Nama_Barang  = input($_POST["Nama_Barang"]);
    $QTY  = input($_POST["QTY"]);
    $Harga    = input($_POST["Harga"]);

    // Query insert data
    $sql = "INSERT INTO stok barang (ID, Nama_Barang, QTY, Harga) 
            VALUES ('$ID','$Nama_Barang','$QTY','$Harga')";

    $hasil = mysqli_query($kon, $sql);

    if ($hasil) {
        header("Location:TP_index.php");
    } else {
        echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";
    }
}
?>

<h2>Input Data</h2>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

    <div class="form-group">
        <label>ID:</label>
        <input type="text" name="ID" class="form-control" placeholder="Masukan ID" required />
    </div>

    <div class="form-group">
        <label>Nama Barang:</label>
        <input type="text" name="Nama_Barang" class="form-control" placeholder="Masukan Nama Barang" required />
    </div>

    <div class="form-group">
        <label>QTY:</label>
        <input type="text" name="QTY" class="form-control" placeholder="Masukan QTY" required />
    </div>

    <div class="form-group">
        <label>Harga:</label>
        <input type="text" name="Harga" class="form-control" placeholder="Masukan Harga" required />
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>

</div>
</body>
</html>