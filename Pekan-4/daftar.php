<?php
$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $cek = mysqli_query($conn, "SELECT email FROM datauser WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah digunakan!');</script>";
    } else {
        $sql = "INSERT INTO datauser (nama, email, password) VALUES ('$nama', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Daftar Berhasil!'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up • CyberVault</title>
    <style>
        body { font-family: sans-serif; background: #0a0a0f; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid #00d4ff; width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; background: #1a1a2e; border: 1px solid #333; color: #fff; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #00d4ff; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; color: #000; }
    </style>
</head>
<body>
    <div class="box">
        <h2 style="text-align:center; color:#00d4ff;">Sign Up</h2>
        <form action="" method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="daftar">Daftar Akun</button>
            <p style="text-align:center; font-size:12px;">Sudah punya akun? <a href="login.php" style="color:#00d4ff;">Login</a></p>
        </form>
    </div>
</body>
</html>