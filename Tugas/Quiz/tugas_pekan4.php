<?php
include "koneksi.php";

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
    echo "<script>alert('Data dihapus'); window.location='tugas_pekan4.php';</script>";
}

$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM barang WHERE id=$id");
    $editData = mysqli_fetch_assoc($result);
}

if(isset($_GET['nama_barang'])){

    $nama = $_GET['nama_barang'];
    $qty = $_GET['qty'];
    $harga = $_GET['harga'];

    if(isset($_GET['id']) && $_GET['id'] != ""){
        // UPDATE
        $id = $_GET['id'];
        mysqli_query($conn, "UPDATE barang SET 
            nama_barang='$nama',
            qty='$qty',
            harga='$harga'
            WHERE id=$id");

        echo "<script>alert('Data diupdate'); window.location='tugas_pekan4.php';</script>";
    } else {
        // INSERT
        mysqli_query($conn, "INSERT INTO barang (nama_barang, qty, harga)
            VALUES ('$nama','$qty','$harga')");

        echo "<script>alert('Data ditambahkan'); window.location='tugas_pekan4.php';</script>";
    }
}

$dataLaporan = mysqli_query($conn, "SELECT * FROM barang ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="row">

  <!-- FORM INPUT -->
  <div class="form-card">
    <h3>Input Data Barang</h3> 

    <form method="GET" action="">
      
      <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

      <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" 
        value="<?php echo $editData['nama_barang'] ?? ''; ?>" required>
      </div>

      <div class="form-group">
        <label>Kuantiti</label>
        <input type="number" name="qty" 
        value="<?php echo $editData['qty'] ?? ''; ?>" required>
      </div>

      <div class="form-group">
        <label>Harga Barang</label>
        <input type="number" name="harga" 
        value="<?php echo $editData['harga'] ?? ''; ?>" required>
      </div>

      <div class="btn-group">
        <button type="submit">Simpan</button>
      </div>

    </form>
  </div>

  <!-- TABEL DATA -->
  <div class="table-card" style="flex:1;">
    <h3 style="margin-bottom: 10px;">Data Barang</h3>

    <table id="tabelManual" border="1" cellpadding="8" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Barang</th>
          <th>Kuantiti</th>
          <th>Harga Barang</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody>
        <?php while($row = mysqli_fetch_assoc($dataLaporan)): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['nama_barang']; ?></td>
          <td><?php echo $row['qty']; ?></td>
          <td><?php echo $row['harga']; ?></td>
          <td>
            <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
            <a href="?hapus=<?php echo $row['id']; ?>" 
               onclick="return confirm('Yakin hapus?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>

    </table>
  </div>

</div>
</body>
</html>