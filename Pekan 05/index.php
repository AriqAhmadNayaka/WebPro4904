<?php
session_start();
// gunanya supaya orang yang belum login ngga bisa langsung masuk ke halaman index.php ini
if (!isset($_SESSION['login'])) header("Location: login.php");
include 'config.php';

// Logika Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM proyek WHERE id = $id");
    header("Location: index.php");
}

// Logika Simpan (Satu Tombol untuk Create & Update + Upload)
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_proyek'];
    $desc = $_POST['deskripsi'];
    
    // Proses Upload File
    $file_name = $_FILES['berkas']['name'];
    $tmp_name = $_FILES['berkas']['tmp_name'];
    if ($file_name) {
        // file yang di pilih dipindahin ke folder sememtara di server
        move_uploaded_file($tmp_name, "uploads/" . $file_name);
    } else {
        $file_name = $_POST['file_lama']; // Gunakan file lama jika tidak upload baru
    }

    if ($id) { // Update
        mysqli_query($conn, "UPDATE proyek SET nama_proyek='$nama', deskripsi='$desc', nama_file='$file_name' WHERE id=$id");
    } else { // Create
        mysqli_query($conn, "INSERT INTO proyek VALUES ('', '$nama', '$desc', '$file_name')");
    }
    header("Location: index.php");
}

// Logika Edit (Ambil data ke form)
$edit_data = ['id'=>'', 'nama_proyek'=>'', 'deskripsi'=>'', 'nama_file'=>''];
if (isset($_GET['edit'])) {
    $res = mysqli_query($conn, "SELECT * FROM proyek WHERE id = " . $_GET['edit']);
    $edit_data = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Proyek</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Manajemen Proyek</h2>
    <a href="login.php">Logout</a><hr>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <input type="hidden" name="file_lama" value="<?= $edit_data['nama_file'] ?>">
        
        <input type="text" name="nama_proyek" placeholder="Nama Proyek" value="<?= $edit_data['nama_proyek'] ?>" required><br>
        <textarea name="deskripsi" placeholder="Deskripsi"><?= $edit_data['deskripsi'] ?></textarea><br>
        <input type="file" name="berkas"><br>
        <button type="submit" name="simpan">SIMPAN DATA & UPLOAD</button>
    </form>

    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>File</th>
            <th>Aksi</th>
        </tr>
        <?php
        $data = mysqli_query($conn, "SELECT * FROM proyek");
        while($row = mysqli_fetch_assoc($data)):
        ?>
        <tr>
            <td><?= $row['nama_proyek'] ?></td>
            <td><?= $row['deskripsi'] ?></td>
            <td><a href="uploads/<?= $row['nama_file'] ?>" target="_blank"><?= $row['nama_file'] ?></a></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>">Edit</a> | 
                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>