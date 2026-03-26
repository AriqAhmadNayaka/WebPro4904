<?php
include "koneksi2.php"; // menghubungkan ke file koneksi database
session_start(); // memulai session

// jika tombol login ditekan
if(isset($_POST['login'])){
    $username = $_POST['username']; // ambil input username
    $password = $_POST['password']; // ambil input password

    // query cari user berdasarkan username
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // cek apakah user ditemukan
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        // verifikasi password dengan hash di database
        if(password_verify($password, $row['password'])){
            $_SESSION['login'] = true;              // simpan status login
            $_SESSION['user'] = $row['username'];   // simpan username ke session
            header("Location: PR_02dashboard.php"); // arahkan ke dashboard
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        /* CSS untuk tampilan halaman login */
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
<!-- container utama card login -->
<div class="container">
    <!-- judul halaman -->
    <h2>Login</h2>

    <!-- tampilkan pesan error jika ada -->
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <!-- form login -->
    <form method="POST">
        <!-- input username -->
        <input type="text" name="username" placeholder="Username" required>

        <!-- input password -->
        <input type="password" name="password" placeholder="Password" required>

        <!-- tombol login -->
        <button type="submit" name="login">Masuk</button>
    </form>

    <!-- link ke halaman registrasi -->
    <p>Belum punya akun? <a href="PR_02register.php">Register</a></p>
</div>
</body>
</html>