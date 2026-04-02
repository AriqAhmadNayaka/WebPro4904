<?php
include 'koneksi.php'; // Koneksi ke database

session_start();

//jadi jika session username tidak ada maka akan diarahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

//untuk mengambil data anak dari database
$data = mysqli_query($conn, "SELECT * FROM anak");
?>

<!--untuk menampilkan data anak dalam bentuk tabel dengan data yang diambil dari database-->
<!DOCTYPE html>
<html>
<head>
    <title>Data Anak</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Data Anak</h2>
<p>Selamat datang, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>

<a href="tambah_anak.php">+ Tambah Data Anak</a>

<table>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Umur</th>
    <th>Jenis Kelamin</th>
    <th>Pelatihan</th>
    <th>Alergi</th>
    <th>Aksi</th>
</tr>

<?php 
$no = 1;
while($row = mysqli_fetch_assoc($data)) { //ketika data masih ada maka akan terus menampilkan data anak dalam bentuk tabel dengan data yang diambil dari database   
?>
<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $row['nama']; ?></td>
    <td><?php echo $row['umur']; ?></td>
    <td><?php echo $row['jenis_kelamin']; ?></td>
    <td><?php echo $row['jenis_pelatihan']; ?></td>
    <td><?php echo $row['alergi']; ?></td>
    <td>
        <a href="edit_anak.php?id=<?php echo $row['id']; ?>">Edit</a> //untuk mengarahkan ke halaman edit_anak.php dengan membawa id dari data anak yang ingin diedit
         |
        <a href="hapus_anak.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>