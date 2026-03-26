<?php
// 1. Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. PROSES TAMBAH DATA (Jika form di bawah diklik)
if (isset($_POST['tambah'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Query simpan (ID tidak perlu dimasukkan karena sudah Auto Increment)
    $sql = "INSERT INTO datauser (nama, email, password) VALUES ('$nama', '$email', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data Berhasil Ditambahkan!'); window.location.href='datauser.php';</script>";
        exit;
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}

// 3. AMBIL DATA UNTUK TABEL
$query  = "SELECT id, nama, email, password FROM datauser ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User • CyberVault</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background: #0a0a0f; color: #fff; padding: 40px; }
        h2 { color: #00d4ff; text-align: center; }
        
        /* Style Form Tambah */
        .form-tambah {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(0, 212, 255, 0.3);
            max-width: 800px;
            margin: 0 auto 30px auto;
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        .input-group { display: flex; flex-direction: column; flex: 1; }
        .input-group label { font-size: 12px; margin-bottom: 5px; color: #00d4ff; }
        input { 
            padding: 10px; 
            background: #1a1a2e; 
            border: 1px solid #333; 
            color: #fff; 
            border-radius: 5px; 
        }
        .btn-tambah { 
            background: #00d4ff; 
            color: #000; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            font-weight: bold; 
            cursor: pointer; 
            height: 40px;
        }
        .btn-tambah:hover { background: #0099cc; }

        /* Style Tabel */
        table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.02); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(0,212,255,0.1); }
        th { background: rgba(0, 212, 255, 0.1); color: #00d4ff; }
        .pass-cell { color: #8892b0; font-size: 11px; font-family: monospace; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        
        .btn-aksi { padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .btn-edit { background: #00ffaa; color: #000; }
        .btn-hapus { background: #ff4d4d; color: #fff; }
    </style>
</head>
<body>

    <h2>Manajemen Pengguna CyberVault</h2>

    <form action="" method="POST" class="form-tambah">
        <div class="input-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan Nama" required>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="email@vault.com" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="******" required>
        </div>
        <button type="submit" name="tambah" class="btn-tambah">Tambah User</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Password (Hash)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td class="pass-cell" title="<?= $row['password']; ?>"><?= $row['password']; ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-aksi btn-edit">Edit</a>
                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Hapus user ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
            
            <?php if (mysqli_num_rows($result) == 0) : ?>
                <tr><td colspan="5" style="text-align:center; padding: 20px;">Belum ada data pengguna.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>