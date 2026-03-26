<?php
include "koneksi.php"; // menghubungkan ke file koneksi database
session_start(); // memulai session untuk menyimpan data login

// jika tombol register ditekan
if (isset($_POST['register'])) {
    $username   = $_POST['username'];   // ambil input username
    $email      = $_POST['email'];      // ambil input email
    $password   = $_POST['password'];   // ambil input password
    $konfirmasi = $_POST['konfirmasi']; // ambil input konfirmasi password

    // cek apakah password sama dengan konfirmasi
    if ($password !== $konfirmasi) {
        $error = "Password dan konfirmasi tidak sama!";
    } else {
        // hash password sebelum disimpan ke database
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // query simpan data ke tabel users
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed);

        // eksekusi query
        if ($stmt->execute()) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Registrasi gagal: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Registrasi</title>
    <style>
        /* CSS untuk tampilan halaman registrasi */
        * { margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", Arial, sans-serif; }

        body {
          /* background dengan gambar + overlay */
          background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0)),
                      url("asetgambar/bg-1.jpg");
          background-size: cover; height:100vh;
          display:flex; justify-content:center; align-items:center; position:relative;
        }

        /* overlay blur di atas background */
        body::before { 
          content:""; position:absolute; width:100%; height:100%;
          background:rgba(84,114,131,0.625); backdrop-filter:blur(3px); z-index:-1; 
        }

        /* container card */
        .container {
          background:rgba(91,118,124,0.85); width:400px; padding:40px;
          border-radius:20px; text-align:center; color:white;
          box-shadow:0 10px 25px rgba(0,0,0,0.35);
          animation:fadeIn 0.8s ease-in-out;
        }

        .container h2 { font-size:30px; font-weight:bold; margin-bottom:25px; }

        /* input form */
        .container input {
          width:100%; padding:14px 16px; margin-bottom:18px;
          border:none; border-radius:30px; background:#e2e2e2;
          font-size:15px; transition:0.3s;
        }
        .container input:focus {
          background:#fff; box-shadow:0 0 10px rgba(255,255,255,0.6); outline:none;
        }

        /* tombol daftar */
        .container button {
          width:60%; padding:12px; border:none; border-radius:30px;
          background:#fff; font-size:17px; font-weight:bold;
          cursor:pointer; transition:0.3s;
        }
        .container button:hover { background:#f2e6d7; transform:scale(1.05); }

        /* link ke login */
        .register-link { margin-top:22px; font-size:15px; opacity:0.9; }
        .register-link a { color:#ffe7ad; font-weight:bold; text-decoration:none; transition:0.3s; }
        .register-link a:hover { color:#fff3ce; text-decoration:underline; }

        /* animasi fade in */
        @keyframes fadeIn { from{opacity:0; transform:translateY(25px);} to{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>
<div class="container">
    <h2>Daftar</h2>
    <!-- tampilkan pesan error atau sukses -->
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:lightgreen;'>$success</p>"; ?>

    <!-- form registrasi -->
    <form method="POST" action="PR_01register.php">
        <input type="text" name="username" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Buat Password" required />
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required />
        <button type="submit" name="register">Daftar</button>

        <!-- link ke halaman login -->
        <div class="register-link">
            <span>Sudah punya akun? </span>
            <a href="PR_01login.php">Login</a>
        </div>
    </form>
</div>
</body>
</html>