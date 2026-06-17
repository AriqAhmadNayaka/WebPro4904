<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
body {
    font-family: Arial;
    background: #f4f6f9;
}
.navbar {
    background: #667eea;
    padding: 15px;
    color: white;
}
.container {
    width: 80%;
    margin: 30px auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}
th, td {
    padding: 10px;
    border: 1px solid #ddd;
}
th {
    background: #667eea;
    color: white;
}
a {
    text-decoration: none;
}
.btn {
    padding: 5px 10px;
    color: white;
    border-radius: 5px;
}
.edit { background: orange; }
.hapus { background: red; }
.tambah {
    background: green;
    padding: 10px;
    display: inline-block;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="navbar">
    Halo, <?php echo $_SESSION['username']; ?> |
    <a href="logout.php" style="color:white;">Logout</a>
</div>

<div class="container">
    <h2>Data Destinasi</h2>

    <a href="tambah.php" class="tambah">+ Tambah</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Lokasi</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $data = mysqli_query($conn, "SELECT * FROM destinasi");
        while ($d = mysqli_fetch_array($data)) {
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $d['nama']; ?></td>
            <td><?php echo $d['lokasi']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $d['id']; ?>" class="btn edit">Edit</a>
                <a href="hapus.php?id=<?php echo $d['id']; ?>" class="btn hapus" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>