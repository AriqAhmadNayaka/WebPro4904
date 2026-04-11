<?php
// 1. Memulai session agar kita bisa mengecek status login
session_start();

// 2. Proteksi Halaman: Jika user belum login, tendang kembali ke halaman login
if (!isset($_SESSION['login'])) { 
    header("Location: login.php"); 
    exit; 
}

// 3. Membuka koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

// 4. Validasi ID: Cek apakah ada parameter 'id' di URL (contoh: edit.php?id=5)
if (!isset($_GET['id'])) { 
    header("Location: datauser.php"); 
    exit; 
}

$id = $_GET['id'];

// 5. Ambil data user lama dari database berdasarkan ID untuk ditampilkan di form
$query = "SELECT * FROM datauser WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Jika ID tidak ditemukan di database, kembali ke halaman utama
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='datauser.php';</script>";
    exit;
}

// 6. PROSES UPDATE: Berjalan jika tombol 'update' diklik
if (isset($_POST['update'])) {
    // Amankan input teks dari karakter berbahaya
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Ambil informasi file foto dari input type="file"
    $namaFile = $_FILES['foto']['name'];
    $tmpName  = $_FILES['foto']['tmp_name'];
    $errorFile = $_FILES['foto']['error'];

    // LOGIKA PENGGANTIAN FOTO:
    // Cek apakah user memilih file baru (Error 4 berarti user tidak upload file baru)
    if ($errorFile === 4) {
        // Jika tidak upload baru, tetap gunakan nama file foto yang lama
        $fotoFinal = $data['foto'];
    } else {
        // Jika ada file baru yang diupload, lakukan validasi
        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        // pathinfo: mengambil ekstensi file dengan lebih aman
        $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        // Cek apakah ekstensi file sesuai (hanya gambar)
        if (!in_array($ekstensiFile, $ekstensiValid)) {
            echo "<script>alert('Format file tidak didukung!');</script>";
            $fotoFinal = $data['foto']; // Balikkan ke foto lama jika format salah
        } else {
            /* MANAJEMEN PENYIMPANAN:
               Hapus foto fisik lama dari folder 'img' agar server tidak penuh sampah.
               Jangan hapus jika fotonya masih 'default.png'. 
            */
            if ($data['foto'] != 'default.png' && file_exists('img/' . $data['foto'])) {
                unlink('img/' . $data['foto']); // unlink: perintah PHP untuk menghapus file
            }
            
            // Buat nama unik baru menggunakan uniqid() agar tidak bentrok
            $fotoFinal = uniqid() . '.' . $ekstensiFile;
            // Pindahkan file dari memori sementara ke folder 'img' di server
            move_uploaded_file($tmpName, 'img/' . $fotoFinal);
        }
    }

    // 7. Update data terbaru ke dalam database
    $updateQuery = "UPDATE datauser SET nama='$nama', email='$email', foto='$fotoFinal' WHERE id=$id";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='datauser.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User • CyberVault</title>
    <style>
        /* Menggunakan tema yang konsisten dengan halaman login & datauser */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #0a0a0f; 
            color: #fff; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
        }

        .edit-container { 
            background: #1a1a2e; 
            padding: 40px; 
            border-radius: 15px; 
            border: 1px solid #00d4ff; 
            width: 100%; 
            max-width: 400px; 
            box-shadow: 0px 0px 20px rgba(0, 212, 255, 0.2);
            text-align: center;
        }

        h2 { color: #00d4ff; margin-bottom: 30px; }

        .current-foto { margin-bottom: 25px; }
        .img-edit { 
            width: 110px; 
            height: 110px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 3px solid #00d4ff;
            padding: 3px;
        }

        form { text-align: left; }
        label { 
            display: block; 
            font-size: 13px; 
            color: #00d4ff; 
            margin-bottom: 8px; 
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #333;
            background: #0a0a0f;
            color: white;
            box-sizing: border-box;
        }

        input:focus { border-color: #00d4ff; outline: none; }

        .btn-save {
            width: 100%;
            padding: 12px;
            background-color: #00d4ff;
            color: #0a0a0f;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-bottom: 10px;
        }

        .btn-save:hover { background-color: #00b8e6; transform: scale(1.02); }

        .btn-back {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #888;
            font-size: 13px;
            transition: 0.3s;
        }

        .btn-back:hover { color: #ff4d4d; }
    </style>
</head>
<body>

    <div class="edit-container">
        <h2>Update User Profile</h2>
        
        <div class="current-foto">
            <img src="img/<?= $data['foto']; ?>" class="img-edit">
            <p style="font-size: 12px; color: #8892b0; margin-top: 10px;">ID Database: #<?= $id; ?></p>
        </div>
    
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Full Name</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required placeholder="Edit name...">

            <label>Email Access</label>
            <input type="email" name="email" value="<?= htmlspecialchars($data['email']); ?>" required placeholder="Edit email...">

            <label>Change Security Photo</label>
            <input type="file" name="foto">

            <button type="submit" name="update" class="btn-save">SIMPAN PERUBAHAN</button>
            <a href="datauser.php" class="btn-back">Batal dan Kembali</a>
        </form>
    </div>

</body>
</html>