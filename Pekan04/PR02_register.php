<?php
include "koneksi2.php"; // menghubungkan ke file koneksi database (pastikan nama file benar)
session_start(); // memulai session

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
<html>
<head>
    <title>Registrasi</title>
   <style>
        /* CSS untuk tampilan halaman registrasi */
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
        /* input dan tombol */
        input, button { width:100%; padding:12px; margin:8px 0; border-radius:30px; border:none; }
        input { background:#e2e2e2; }
        button { background:#fff; color:#333; font-weight:bold; cursor:pointer; transition:0.3s; }
        button:hover { background:#f2e6d7; transform:scale(1.05); }
        /* animasi fade in */
        @keyframes fadeIn { from{opacity:0; transform:translateY(25px);} to{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>
<div class="container">
    <!-- Judul utama halaman registrasi -->
    <h2>Registrasi Akun</h2>

    <!-- Menampilkan pesan error jika ada -->
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <!-- Menampilkan pesan sukses jika ada -->
    <?php if(isset($success)) echo "<p style='color:lightgreen;'>$success</p>"; ?>

    <!-- Form registrasi -->
    <form method="POST">
        <!-- Input username -->
        <input type="text" name="username" placeholder="Username" required>

        <!-- Input email -->
        <input type="email" name="email" placeholder="Email" required>

        <!-- Input password -->
        <input type="password" name="password" placeholder="Password" required>

        <!-- Input konfirmasi password -->
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>

        <!-- Tombol submit untuk registrasi -->
        <button type="submit" name="register">Daftar</button>
    </form>

    <!-- Link menuju halaman login -->
    <p>Sudah punya akun? <a href="PR_02login.php">Login</a></p>
</div>
</body>
</html>