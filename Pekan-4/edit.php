<?php
// 1. Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: datauser.php");
    exit;
}

$id = $_GET['id'];

// 3. Ambil data user berdasarkan ID untuk ditampilkan di form
$query = "SELECT * FROM datauser WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='datauser.php';</script>";
    exit;
}

// 4. Proses Update Data jika tombol simpan diklik
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Query Update
    $updateQuery = "UPDATE datauser SET nama='$nama', email='$email' WHERE id=$id";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href = '.php';
              </script>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User • CyberVault</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #0a0a0f;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100 vh;
            margin: 0;
        }
        .edit-container {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #00d4ff;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h2 { color: #00d4ff; text-align: center; margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-size: 14px; color: #8892b0; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            background: #1a1a2e;
            border: 1px solid #333;
            border-radius: 8px;
            color: #fff;
            box-sizing: border-box;
        }
        input:focus { border-color: #00d4ff; outline: none; }
        .btn-save {
            width: 100%;
            padding: 12px;
            background: #00d4ff;
            border: none;
            border-radius: 8px;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-save:hover { background: #0099cc; transform: translateY(-2px); }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #8892b0;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-back:hover { color: #fff; }
    </style>
</head>
<body>

    <div class="edit-container">
        <h2>Edit Data User</h2>
        <form action="" method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($data['email']); ?>" required>

            <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
            <a href="datauser.php" class="btn-back">Batal dan Kembali</a>
        </form>
    </div>

</body>
</html>