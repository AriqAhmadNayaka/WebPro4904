<?php
// Memanggil koneksi database.
include "koneksi.php";
// Memanggil helper session dan fitur remember me.
include "session_helper.php";

// Variabel untuk menampung pesan login.
$output = "";
// Penanda bahwa user datang dari halaman register.
$forceLoginPage = isset($_GET["registered"]);

// Jika baru selesai register, paksa tampil ke halaman login lagi.
if ($forceLoginPage) {
    // Hapus session dan cookie lama agar tidak auto-login.
    logoutUser();
    // Pastikan session aktif lagi untuk pemakaian halaman ini.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Tampilkan pesan sukses register.
    $output = '<p style="color:green;">Registrasi berhasil. Silakan login.</p>';
}

// Jika user sudah login dan tidak sedang dipaksa ke login, arahkan ke halaman peserta.
if (!$forceLoginPage && !empty($_SESSION["user"])) {
    header("Location: kelolapeserta.php");
    exit();
}

// Jika tidak ada force login, coba pulihkan user dari cookie remember me.
if (!$forceLoginPage) {
    restoreRememberedUser($koneksi);
}

// Jika session berhasil dipulihkan, langsung arahkan ke halaman peserta.
if (!$forceLoginPage && !empty($_SESSION["user"])) {
    header("Location: kelolapeserta.php");
    exit();
}

// Jalankan proses login jika form disubmit.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil email dari input.
    $email = trim($_POST["email"] ?? "");
    // Ambil password dari input.
    $password = $_POST["password"] ?? "";
    // Ambil role dari input.
    $role = trim($_POST["role"] ?? "");
    // Cek apakah checkbox remember me dicentang.
    $rememberMe = isset($_POST["remember_me"]);

    // Validasi jika ada input kosong.
    if ($email === "" || $password === "" || $role === "") {
        $output = '<p style="color:red;">Email, password, dan role wajib diisi.</p>';
    } else {
        // Hash password dengan md5 agar cocok dengan data yang tersimpan sekarang.
        $passwordHash = md5($password);

        // Cek apakah tabel user memiliki kolom role.
        $cekRole = mysqli_query($koneksi, "SHOW COLUMNS FROM user LIKE 'role'");
        // Simpan status ada atau tidaknya kolom role.
        $adaKolomRole = $cekRole && mysqli_num_rows($cekRole) > 0;

        // Jika kolom role ada, pakai role di query login.
        if ($adaKolomRole) {
            $sql = "SELECT * FROM user WHERE email = ? AND password = ? AND role = ? LIMIT 1";
            $stmt = mysqli_prepare($koneksi, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $email, $passwordHash, $role);
            }
        } else {
            // Jika tidak ada kolom role, login hanya pakai email dan password.
            $sql = "SELECT * FROM user WHERE email = ? AND password = ? LIMIT 1";
            $stmt = mysqli_prepare($koneksi, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $email, $passwordHash);
            }
        }

        // Jika statement gagal disiapkan.
        if (empty($stmt)) {
            $output = '<p style="color:red;">Gagal menyiapkan query login: ' . htmlspecialchars(mysqli_error($koneksi)) . '</p>';
        } else {
            // Jalankan query login.
            mysqli_stmt_execute($stmt);
            // Ambil hasil query.
            $hasil = mysqli_stmt_get_result($stmt);
            // Ambil data user jika cocok.
            $user = $hasil ? mysqli_fetch_assoc($hasil) : null;

            // Jika user ditemukan.
            if ($user) {
                // Simpan session dan cookie remember me bila dipilih.
                loginUser($user, $rememberMe);
                // Tutup statement.
                mysqli_stmt_close($stmt);
                // Arahkan ke halaman kelola peserta.
                header("Location: kelolapeserta.php");
                exit();
            }

            // Pesan jika login tidak cocok.
            $output = '<p style="color:red;">Email, password, atau role tidak cocok.</p>';
            // Tutup statement.
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Menentukan charset halaman. -->
<meta charset="UTF-8">
<!-- Membuat halaman responsif di berbagai ukuran layar. -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Judul tab browser. -->
<title>Login | InkluSkill</title>
<!-- Memanggil file CSS utama. -->
<link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body">
<div class="auth-container">
<!-- Judul halaman login. -->
<h2 class="auth-title">Masuk ke InkluSkill</h2>

<!-- Form login user. -->
<form method="POST" action="" class="auth-form">
<div class="form-group">
<label>Email</label>
<input type="email" name="email" placeholder="Masukkan email" required>
</div>

<div class="form-group">
<!-- Input password user. -->
<label>Password</label>
<input type="password" name="password" placeholder="Masukkan password" required>
</div>

<div class="form-group">
<!-- Pilihan role untuk login. -->
<label>Pilih Role</label>
<select name="role" required>
<option value="">-- Pilih Role --</option>
<option value="sekolah">Sekolah</option>
</select>
</div>

<div class="form-group">
<label>
<!-- Checkbox untuk remember me. -->
<input type="checkbox" name="remember_me" value="1">
Remember me
</label>
</div>

<!-- Tombol submit login. -->
<button class="btn auth-btn" type="submit">Masuk</button>
</form>

<!-- Menampilkan pesan login dari backend. -->
<?php echo $output; ?>

<p class="auth-switch">
<!-- Link menuju halaman daftar akun. -->
Belum punya akun?
<a href="register.php">Daftar</a>
</p>
</div>
</body>
</html>
