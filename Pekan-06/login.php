<?php
include "koneksi.php"; // Menghubungkan ke file koneksi untuk akses database
session_start(); // Memulai sesi untuk menyimpan informasi login

class LoginSystem { // Kelas untuk mengelola proses login
    private $db; // Variabel untuk menyimpan koneksi database
    public function __construct($connection) { $this->db = $connection; } //

    public function login($role, $email, $password) { // Fungsi untuk melakukan login, menerima peran, email, dan password dari form
        $email = mysqli_real_escape_string($this->db, $email); // Melindungi dari SQL Injection dengan membersihkan input email
        $query = mysqli_query($this->db, "SELECT * FROM users WHERE email='$email' AND role='$role'"); // Query untuk mencari pengguna dengan email dan peran yang sesuai
        $user = mysqli_fetch_assoc($query); // Mengambil data pengguna dari hasil query

        if ($user && password_verify($password, $user['password'])) { // Cek jika pengguna ditemukan dan password cocok dengan hash di database
            $_SESSION['login'] = true; // Menandai bahwa pengguna sudah login
            $_SESSION['name'] = $user['name']; //   Menyimpan nama pengguna di sesi untuk ditampilkan di dashboard
            $_SESSION['role'] = $user['role'];// Menyimpan peran pengguna di sesi untuk kontrol akses di halaman lain
            return true; // Login berhasil
        }
        return false; // Login gagal jika pengguna tidak ditemukan atau password salah
    }
}

$auth = new LoginSystem($conn); // Membuat objek LoginSystem dengan koneksi database yang sudah dibuat di koneksi.php
$message = ""; $type = ""; $email = $_COOKIE['email'] ?? ""; $role = ""; // Inisialisasi variabel untuk menyimpan pesan, tipe pesan, email yang diisi sebelumnya (dari cookie), dan peran yang dipilih sebelumnya agar tidak error saat form pertama dimuat

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Cek jika form disubmit dengan metode POST
    $role = $_POST['role']; // Mengambil nilai role dari form
    if ($auth->login($role, $_POST['email'], $_POST['password'])) { // Memanggil fungsi login dari objek LoginSystem dengan data yang dikirimkan dari form, dan cek jika login berhasil
        $message = "Login berhasil! Mengalihkan..."; $type = "success"; // Jika login berhasil, simpan email di cookie selama 30 hari untuk mengisi otomatis saat login berikutnya
        header("refresh:1;url=dashboard.php"); // Redirect ke dashboard setelah 1 detik
    } else { // Jika login gagal, tampilkan pesan error
        $message = "Email atau Password salah!"; $type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LifeTrack - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; }
        .login-wrapper { flex: 1; display: flex; justify-content: center; align-items: center; }
        .login-card { border-radius: 18px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); background: white; width:360px; }
        .btn-lifetrack { background: #0f766e; border: none; padding: 12px; color: white; }
        footer { background: #0f766e; color: white; text-align: center; padding: 24px; }
        .text-lifetrack { color: #0f766e; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="card login-card p-4">
            <h4 class="fw-bold text-lifetrack mb-1">Selamat Datang</h4>
            <p class="text-muted mb-4">Masuk untuk melanjutkan ke LifeTrack</p>
            <?php if ($message != "") : ?>
                <div class="alert alert-<?= $type; ?> py-2 small"><?= $message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Peran</label>
                    <select name="role" class="form-select">
                        <option value="">-- Pilih Peran --</option>
                        <option value="pasien" <?= $role == 'pasien' ? 'selected' : ''; ?>>Pasien</option>
                        <option value="tenaga_medis" <?= $role == 'tenaga_medis' ? 'selected' : ''; ?>>Tenaga Medis</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-lifetrack w-100 fw-bold">Login</button>
            </form>
            <div class="text-center mt-4"><small>Belum punya akun? <a href="registrasi.php" class="text-lifetrack fw-bold text-decoration-none">Buat Akun</a></small></div>
        </div>
    </div>
    <footer>LifeTrack © 2025 — Membantu Anda hidup lebih sehat</footer>
</body>
</html>