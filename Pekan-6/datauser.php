<?php
// Mengambil file class User agar bisa digunakan di sini
require_once 'User.php';

session_start();

// Proteksi Halaman: Jika belum login, tendang ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit; 
}

// Inisialisasi/Membuat Objek dari Class User
$userRepo = new User();

// Cek apakah tombol "tambah" di form diklik
if (isset($_POST['tambah'])) {
    // Panggil fungsi tambahUser dan kirim data $_POST & $_FILES
    if ($userRepo->tambahUser($_POST, $_FILES['foto'])) {
        echo "<script>alert('Data Berhasil Ditambahkan!'); window.location.href='datauser.php';</script>";
    }
}

// Ambil semua data user untuk ditampilkan di tabel HTML nanti
$result = $userRepo->getAllUsers();
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberVault • Management</title>
    <style>
        :root {
            --primary: #00d4ff;
            --accent: #00ffaa;
            --danger: #ff4d4d;
            --bg: #0d0d14;
            --card-bg: rgba(255, 255, 255, 0.03);
        }

        body { 
            font-family: 'Inter', 'Segoe UI', sans-serif; 
            background: var(--bg); 
            background-image: radial-gradient(circle at 50% 0%, #1a1a2e 0%, #0d0d14 100%);
            color: #e0e0e0; 
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container { width: 100%; max-width: 1000px; }

        /* --- Header Section --- */
        .header-area { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px;
        }

        h2 { 
            color: var(--primary); 
            text-transform: uppercase; 
            letter-spacing: 2px;
            font-size: 24px;
            margin: 0;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
        }

        .btn-logout { 
            background: transparent; 
            color: var(--danger); 
            padding: 8px 18px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: bold; 
            border: 1px solid var(--danger);
            transition: 0.3s;
        }

        .btn-logout:hover { background: var(--danger); color: white; box-shadow: 0 0 15px rgba(255, 77, 77, 0.4); }

        /* --- Glassmorphism Form --- */
        .form-tambah { 
            background: var(--card-bg); 
            padding: 25px; 
            border-radius: 15px; 
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .input-group { display: flex; flex-direction: column; }
        .input-group label { font-size: 11px; color: var(--primary); margin-bottom: 8px; text-transform: uppercase; font-weight: bold; }
        
        input { 
            padding: 12px; 
            background: rgba(0, 0, 0, 0.2); 
            border: 1px solid #333; 
            color: #fff; 
            border-radius: 8px;
            transition: 0.3s;
        }

        input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 8px rgba(0, 212, 255, 0.2); }

        .btn-simpan { 
            background: var(--primary); 
            color: #000; 
            border: none; 
            padding: 12px; 
            border-radius: 8px; 
            font-weight: 800; 
            cursor: pointer; 
            align-self: flex-end;
            transition: 0.3s;
        }

        .btn-simpan:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 212, 255, 0.4); }

        /* --- Modern Table --- */
        .table-container { 
            background: var(--card-bg); 
            border-radius: 15px; 
            overflow: hidden; 
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(255, 255, 255, 0.03); color: var(--primary); font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        
        tr:hover { background: rgba(255, 255, 255, 0.02); }

        .img-preview { 
            width: 40px; height: 40px; border-radius: 10px; 
            object-fit: cover; border: 1px solid rgba(0, 212, 255, 0.5); 
        }

        .btn-aksi { 
            padding: 6px 12px; text-decoration: none; border-radius: 6px; 
            font-size: 11px; font-weight: bold; margin-right: 5px; transition: 0.2s;
        }

        .btn-edit { background: rgba(0, 255, 170, 0.1); color: var(--accent); border: 1px solid var(--accent); }
        .btn-edit:hover { background: var(--accent); color: #000; }

        .btn-hapus { background: rgba(255, 77, 77, 0.1); color: var(--danger); border: 1px solid var(--danger); }
        .btn-hapus:hover { background: var(--danger); color: #fff; }

        /* Custom File Input */
        input[type="file"] { font-size: 12px; padding: 8px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-area">
        <h2>CyberVault<span style="font-weight: 200; color: #fff;">Admin</span></h2>
        <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">Log Out</a>
    </div>

    <form action="" method="POST" enctype="multipart/form-data" class="form-tambah">
        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="nama" required placeholder="User name">
        </div>
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="name@cyber.com">
        </div>
        <div class="input-group">
            <label>Security Key</label>
            <input type="password" name="password" required placeholder="••••••">
        </div>
        <div class="input-group">
            <label>Profile Image</label>
            <input type="file" name="foto">
        </div>
        <button type="submit" name="tambah" class="btn-simpan">ADD USER</button>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Identity</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="img/<?= $row['foto']; ?>" class="img-preview"></td>
                    <td style="font-weight: 600; color: #fff;"><?= htmlspecialchars($row['nama']); ?></td>
                    <td style="color: #aaa;"><?= htmlspecialchars($row['email']); ?></td>
                    <td style="text-align: center;">
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn-aksi btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Hapus data ini?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>