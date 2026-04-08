<?php
// Memulai session untuk menyimpan status login user
session_start();

// Cek apakah user sudah login atau belum
if (!isset($_SESSION['login'])) { 
    // Jika belum login, redirect ke halaman Login
    header("Location: Login.php"); 
    exit; // Menghentikan eksekusi script
}

// Menghubungkan ke database
include "koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>CyberVault Dashboard</title>

    <!-- Menghubungkan file CSS -->
    <link rel="stylesheet" href="login.css">

    <style>
        /* Container untuk menampilkan card secara grid/flex */
        .grid-container { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 20px; 
            justify-content: center; 
            padding: 20px; 
        }

        /* Desain card profil */
        .card { 
            background: var(--card); 
            width: 280px; 
            border-radius: 20px; 
            padding: 30px; 
            text-align: center; 
            border: 1px solid rgba(0,212,255,0.2); 
        }

        /* Styling foto profil */
        .profile-pic { 
            width: 120px; 
            height: 120px; 
            margin: 0 auto 20px; 
            border-radius: 50%; 
            overflow: hidden; 
            border: 3px solid var(--primary); 
            box-shadow: var(--glow); 
            background: #1a1a2e; 
        }

        /* Gambar di dalam profile */
        .profile-pic img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        }
    </style>
</head>

<body>

<!-- Header / Navbar -->
<header class="main-header">
    <nav class="nav-bar">
        <!-- Logo -->
        <a href="#" class="logo">CyberVault</a>

        <!-- Navigasi menu -->
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="tambah.php">Tambah Data</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<!-- Konten utama -->
<div style="padding-top:120px;">

    <!-- Judul halaman -->
    <h2 style="color:#00d4ff; text-align:center; margin-bottom:30px;">
        DATABASE PROFIL
    </h2>

    <!-- Container untuk card -->
    <div class="grid-container">

        <?php
        // Mengambil semua data dari tabel "catatan"
        $data = mysqli_query($conn, "SELECT * FROM catatan");

        // Looping untuk menampilkan setiap data
        while($d = mysqli_fetch_array($data)){

            // =========================
            // LOGIKA VALIDASI GAMBAR
            // =========================

            // Path file gambar
            $file_path = "uploads/" . $d['gambar'];

            // Jika ada nama file DAN file benar-benar ada di folder
            if (!empty($d['gambar']) && file_exists($file_path)) {
                $tampil_foto = $file_path; // gunakan gambar asli
            } else {
                // Jika file tidak ditemukan, gunakan avatar otomatis dari API
                $tampil_foto = "https://ui-avatars.com/api/?name="
                                . urlencode($d['judul'])
                                . "&background=00d4ff&color=fff";
            }
        ?>

        <!-- Card profil -->
        <div class="card">

            <!-- Foto profil -->
            <div class="profile-pic">
                <img src="<?= $tampil_foto; ?>">
            </div>

            <!-- Nama -->
            <h3 style="color:var(--primary);">
                <?= $d['judul']; ?>
            </h3>

            <!-- Bio -->
            <p style="color:var(--text-muted); margin:10px 0;">
                "<?= $d['isi']; ?>"
            </p>

            <!-- Tombol aksi -->
            <div style="margin-top:15px; display:flex; justify-content:center; gap:15px;">
                
                <!-- Link ke halaman edit -->
                <a href="edit.php?id=<?= $d['id']; ?>" 
                   style="color:#00d4ff; text-decoration:none; font-size:12px;">
                   EDIT
                </a>

                <!-- Link hapus dengan konfirmasi -->
                <a href="hapus.php?id=<?= $d['id']; ?>" 
                   style="color:#ff4664; text-decoration:none; font-size:12px;" 
                   onclick="return confirm('Hapus?')">
                   HAPUS
                </a>

            </div>
        </div>

        <?php } // akhir looping ?>
    </div>
</div>

</body>
</html>