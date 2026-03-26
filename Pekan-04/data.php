<?php
// Include koneksi ke database
include 'koneksi.php';

// Query untuk mengambil nama dan email semua user dari tabel 'users'
$sql = "SELECT nama, email FROM users";
$result = mysqli_query($conn, $sql);
?>

<h2>Semua User Terdaftar</h2>
<table border="1" cellpadding="10"><!-- Tabel untuk menampilkan data user -->
<tr>
    <th>Nama</th> <!-- Kolom Nama -->
    <th>Email</th> <!-- Kolom Email -->
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['nama']; ?></td> <!-- Tampilkan nama user -->
    <td><?= $row['email']; ?></td> <!-- Tampilkan email user -->
</tr>
<?php } ?>
</table>