<?php
include "koneksi.php"; // menghubungkan ke file koneksi database
session_start(); // memulai session

// cek apakah user sudah login
if(!isset($_SESSION['login'])){
    header("Location: PR_02login.php"); // jika belum login, arahkan ke halaman login
    exit;
}
// CREATE: tambah data warga baru
if(isset($_POST['tambah'])){
    $nama   = $_POST['nama'];   // ambil input nama
    $alamat = $_POST['alamat']; // ambil input alamat
    $usia   = $_POST['usia'];   // ambil input usia
    $conn->query("INSERT INTO warga (nama, alamat, usia) VALUES ('$nama','$alamat','$usia')");
}
// DELETE: hapus data warga berdasarkan id
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM warga WHERE id=$id");
}
// UPDATE: ubah data warga berdasarkan id
if(isset($_POST['update'])){
    $id     = $_POST['id'];
    $nama   = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $usia   = $_POST['usia'];
    $sql = "UPDATE warga SET nama='$nama', alamat='$alamat', usia='$usia' WHERE id=$id";
    if(!$conn->query($sql)){
        echo "Error update: " . $conn->error;
    }
}
// READ: ambil semua data warga dari database
$result = $conn->query("SELECT * FROM warga");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Warga Desa</title>
    <style>
        /* CSS untuk tampilan halaman data warga */
        body {
            font-family: "Poppins", Arial, sans-serif;
            background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0)),
                        url("asetgambar/bg-1.jpg"); /* background gambar */
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            padding-top: 40px;
        }
        /* overlay blur di atas background */
        body::before {
            content:""; position:absolute; width:100%; height:100%;
            background:rgba(84,114,131,0.625); backdrop-filter:blur(3px); z-index:-1;
        }
        /* container card */
        .container {
            background:rgba(91,118,124,0.85); width:900px; padding:30px;
            border-radius:20px; text-align:center; color:white;
            box-shadow:0 10px 25px rgba(0,0,0,0.35);
            animation:fadeIn 0.8s ease-in-out;
        }
        .container h2 { font-size:28px; margin-bottom:20px; }
        form input, form button {
            padding:10px; margin:5px; border-radius:8px; border:none;
        }
        form button {
            background:#fff; color:#333; font-weight:bold; cursor:pointer;
            transition:0.3s;
        }
        form button:hover { background:#f2e6d7; transform:scale(1.05); }
        table {
            width:100%; border-collapse:collapse; margin-top:20px;
            background:rgba(255,255,255,0.1);
        }
        th, td {
            border:1px solid #ddd; padding:10px; text-align:center;
        }
        th { background:rgba(255,255,255,0.2); }
        a { color:#fff; text-decoration:none; margin-right:10px; }
        a:hover { text-decoration:underline; }
        @keyframes fadeIn { from{opacity:0; transform:translateY(25px);} to{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>
<div class="container">
    <h2>Data Warga Desa</h2>

    <!-- Form Tambah Warga -->
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="alamat" placeholder="Alamat">
        <input type="number" name="usia" placeholder="Usia">
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <!-- Tabel Data Warga -->
    <table>
        <tr><th>ID</th><th>Nama</th><th>Alamat</th><th>Usia</th><th>Aksi</th></tr>
        <?php while($row = $result->fetch_assoc()){ ?>
        <tr> 
            <td><?= $row['id'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['alamat'] ?></td>
            <td><?= $row['usia'] ?></td>
            <td>
                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data?')">Hapus</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="nama" value="<?= $row['nama'] ?>">
                    <input type="text" name="alamat" value="<?= $row['alamat'] ?>">
                    <input type="number" name="usia" value="<?= $row['usia'] ?>">
                    <button type="submit" name="update">Update</button>
                </form>
            </td>
        </tr>
        <?php } ?> <!-- penutup while -->
    </table>

    <br>
    <a href="PR_02dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html>