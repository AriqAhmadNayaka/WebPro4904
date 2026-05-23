<?php
session_start(); //memulai session
include("Koneksi.php"); //mengambil file koneksi database

//jika belum login, akan kembali ke login.php
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

 //mengambil data dari form HTML
if(isset($_POST['tambah'])){    
    $name = $_POST["name"];
    $address = $_POST["addres"]; 
    $number = $_POST["number"];
    $gender = $_POST["gender"];
    $date = $_POST["date"];
    $weight = $_POST["weight"];
    $height = $_POST["height"];
    $jeniskanker = $_POST["text"];

    //perinta untuk menambahkan data ke tabel user1
    $sql = "INSERT INTO user1 (nama, alamat, notlp, jeniskelamin, tanggallahir, beratbadan, tinggibadan, jeniskanker) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); 
    //mencek kesalahan pada tabel
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    } 
    //proses memsukan data
    $stmt->bind_param("ssssssss", $name, $address, $number, $gender, $date, $weight, $height, $jeniskanker);
    //perintah menyimpan data secara permanen 
    if ($stmt->execute()) { 
        echo "<script>alert('Data berhasil disimpan!');</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $stmt->error . "');</script>";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - LifeTrack</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px; text-align: left; }
        form { margin-bottom: 30px; }
        input { margin-bottom: 5px; padding: 5px; width: 250px; }
    </style>
</head>

<body>
    <!-- umtuk menyapa pengguna yang mengambil data email dari session yang sedang login-->
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user']['email']); ?>!</h2>
    <hr>
    <!-- form input data -->
    <h3>Input Data Pasien</h3>
    <form action="" method="POST">
        <input type="text" name="name" placeholder="Nama" required><br>
        <input type="text" name="addres" placeholder="Alamat" required><br>
        <input type="text" name="number" placeholder="No HP" required><br>
        <input type="text" name="gender" placeholder="Gender" required><br>
        <input type="date" name="date" required><br>
        <input type="number" name="weight" placeholder="Berat" required><br>
        <input type="number" name="height" placeholder="Tinggi" required><br>
        <input type="text" name="text" placeholder="Jenis Kanker" required><br>

        <button type="submit" name="tambah">Tambah</button>
        <button type="button" onclick="location.href='update.php'">Update Data</button>
        <button type="button" onclick="location.href='delete.php'">Hapus Data</button>
        <button type="button" onclick="location.href='logout.php'" style="background-color: #ff4d4d; color: white;">Logout</button>
    </form>

    <h3>Data Pasien</h3>

    <table border="1">
        <tr style="background-color: #0f766e; color: white;">
            <th>Nama</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Gender</th>
            <th>Tanggal Lahir</th>
            <th>BB</th>
            <th>TB</th>
            <th>Kanker</th>
        </tr>

        <?php 
        //mengambil data langsung dari database tabel user1
        $query = "SELECT * FROM user1";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while($p = $result->fetch_assoc()){ 
        ?>
        <!-- data yang di isi masuk ke html -->
        <tr>
            <td><?php echo htmlspecialchars($p['nama']); ?></td>
            <td><?php echo htmlspecialchars($p['alamat']); ?></td>
            <td><?php echo htmlspecialchars($p['notlp']); ?></td>
            <td><?php echo htmlspecialchars($p['jeniskelamin']); ?></td>
            <td><?php echo htmlspecialchars($p['tanggallahir']); ?></td>
            <td><?php echo htmlspecialchars($p['beratbadan']); ?> kg</td>
            <td><?php echo htmlspecialchars($p['tinggibadan']); ?> cm</td>
            <td><?php echo htmlspecialchars($p['jeniskanker']); ?></td>
            <!-- tombol delete dan edit -->
            <td>
                <a href="dashboard.php?edit=<?php echo $p['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" 
                onclick="return confirm('Yakin ingin menghapus data ini?')"> Hapus </a>
            </td>
        </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='8' style='text-align:center;'>Belum ada data pasien.</td></tr>";
        }
        $conn->close();
        ?>
    </table>

</body>
</html>
