<?php
session_start();
include "koneksi.php";

$output = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = trim($_POST["role"] ?? "");

    if ($email === "" || $password === "" || $role === "") {
        $output = '<p style="color:red;">Email, password, dan role wajib diisi.</p>';
    } else {
        $passwordHash = md5($password);

        $cekRole = mysqli_query($koneksi, "SHOW COLUMNS FROM user LIKE 'role'");
        $adaKolomRole = $cekRole && mysqli_num_rows($cekRole) > 0;

        if ($adaKolomRole) {
            $sql = "SELECT * FROM user WHERE email = ? AND password = ? AND role = ? LIMIT 1";
            $stmt = mysqli_prepare($koneksi, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $email, $passwordHash, $role);
            }
        } else {
            $sql = "SELECT * FROM user WHERE email = ? AND password = ? LIMIT 1";
            $stmt = mysqli_prepare($koneksi, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $email, $passwordHash);
            }
        }

        if (empty($stmt)) {
            $output = '<p style="color:red;">Gagal menyiapkan query login: ' . htmlspecialchars(mysqli_error($koneksi)) . '</p>';
        } else {
            mysqli_stmt_execute($stmt);
            $hasil = mysqli_stmt_get_result($stmt);
            $user = $hasil ? mysqli_fetch_assoc($hasil) : null;

            if ($user) {
                $_SESSION["user"] = $user["email"];
                $_SESSION["role"] = $user["role"] ?? $role;
                $_SESSION["username"] = $user["username"] ?? "";

                header("Location: kelolapeserta.php");
                exit();
            }

            $output = '<p style="color:red;">Email, password, atau role tidak cocok.</p>';
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | InkluSkill</title>
<link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body">
<div class="auth-container">
<h2 class="auth-title">Masuk ke InkluSkill</h2>

<form method="POST" action="" class="auth-form">
<div class="form-group">
<label>Email</label>
<input type="email" name="email" placeholder="Masukkan email" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" placeholder="Masukkan password" required>
</div>

<div class="form-group">
<label>Pilih Role</label>
<select name="role" required>
<option value="">-- Pilih Role --</option>
<option value="sekolah">Sekolah</option>
</select>
</div>

<button class="btn auth-btn" type="submit">Masuk</button>
</form>

<?php echo $output; ?>

<p class="auth-switch">
Belum punya akun?
<a href="register.php">Daftar</a>
</p>
</div>
</body>
</html>
