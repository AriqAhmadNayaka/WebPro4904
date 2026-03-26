<?php
session_start(); // memulai session

// cek apakah user sudah login
if(!isset($_SESSION['login'])){
    // jika belum login, arahkan ke halaman login
    header("Location: PR_02login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Desa</title>
   <style>
        /* CSS untuk tampilan halaman dashboard */
        body {
            font-family: "Poppins", Arial, sans-serif;
            background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0)),
                        url("asetgambar/bg-1.jpg"); /* background gambar */
            background-size: cover; height: 100vh;
            display: flex; justify-content: center; align-items: center; position: relative;
        }
        /* overlay blur di atas background */
        body::before { content:""; position:absolute; width:100%; height:100%;
            background:rgba(84,114,131,0.625); backdrop-filter:blur(3px); z-index:-1; }
        /* container card */
        .container { background:rgba(91,118,124,0.85); width:400px; padding:40px;
            border-radius:20px; text-align:center; color:white;
            box-shadow:0 10px 25px rgba(0,0,0,0.35); animation:fadeIn 0.8s ease-in-out; }
        .container h2 { font-size:28px; margin-bottom:20px; }
        input, button { width:100%; padding:12px; margin:8px 0; border-radius:30px; border:none; }
        input { background:#e2e2e2; }
        button { background:#fff; color:#333; font-weight:bold; cursor:pointer; transition:0.3s; }
        button:hover { background:#f2e6d7; transform:scale(1.05); }
        /* animasi fade in */
        @keyframes fadeIn { from{opacity:0; transform:translateY(25px);} to{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>
    <!-- container utama card dashboard -->
    <div class="container">
        <!-- judul halaman dengan sapaan user dari session -->
        <h2>Selamat datang, <?php echo $_SESSION['user']; ?>!</h2>

        <!-- pesan sambutan -->
        <p>Anda berhasil login ke sistem desa.</p>

        <!-- link menuju halaman CRUD warga -->
        <a href="PR_02warga.php">Kelola Data Warga</a> |  

        <!-- link untuk logout -->
        <a href="PR_02logout.php">Logout</a>
    </div>
</body>
</html>