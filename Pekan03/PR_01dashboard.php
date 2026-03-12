<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: PR_01login.php"); // arahkan balik ke login kalau belum login
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Web Desa</title>
    <style>
        /* RESET */
        * { margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", Arial, sans-serif; }

        /* BACKGROUND */
        body {
          background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0)),
                      url("/asetgambar/bg-1.jpg");
          background-size: cover;
          height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
          position: relative;
        }
        body::before {
          content:""; position:absolute; width:100%; height:100%;
          background:rgba(84,114,131,0.625); backdrop-filter:blur(3px); z-index:-1;
        }

        /* CARD */
        .container {
          background:rgba(91,118,124,0.85); width:600px; padding:40px;
          border-radius:20px; text-align:center; color:white;
          box-shadow:0 10px 25px rgba(0,0,0,0.35);
          animation:fadeIn 0.8s ease-in-out;
        }
        .container h2 { font-size:30px; font-weight:bold; margin-bottom:20px; }
        .container p { font-size:16px; margin-bottom:20px; }
        .container a {
          display:inline-block; margin-top:15px; padding:10px 20px;
          border-radius:30px; background:#fff; color:#333;
          font-weight:bold; text-decoration:none; transition:0.3s;
        }
        .container a:hover { background:#f2e6d7; transform:scale(1.05); }
        @keyframes fadeIn { from{opacity:0; transform:translateY(25px);} to{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat datang di Web Desa</h2>
        <p>Halo, <?= $_SESSION['user']; ?>! Anda berhasil login.</p>
        <p>Halaman ini berisikan tentang informasi desa.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>