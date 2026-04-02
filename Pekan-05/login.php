<?php
include "koneksi.php"; // Menghubungkan ke file koneksi untuk akses database

session_start(); // Memulai session untuk menyimpan data login
$email = ""; // Variabel untuk menyimpan email pengguna yang akan diisi jika cookie ada
$name = ""; // Variabel untuk menyimpan nama pengguna yang akan diisi jika session ada
if (isset($_COOKIE['email'])) { // Cek jika cookie email ada, ini untuk fitur "Remember Me"
    $email = $_COOKIE['email']; // Isi variabel email dengan nilai dari cookie
}

$message = ""; // Variabel untuk menyimpan pesan yang akan ditampilkan ke pengguna (misalnya error atau sukses)
$type = ""; // Variabel untuk menyimpan tipe pesan (misalnya "danger" untuk error, "success" untuk sukses)

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Cek jika form disubmit dengan metode POST

    $role = $_POST["role"] ?? ""; // Ambil nilai role dari form, jika tidak ada set ke string kosong
    $email = trim($_POST["email"]); // Ambil nilai email dari form, bersihkan spasi di awal dan akhir
    $password = trim($_POST["password"]); // Ambil nilai password dari form, bersihkan spasi di awal dan akhir

    if (empty($role) || empty($email) || empty($password)) { // Validasi semua field harus diisi
        $message = "Semua field wajib diisi!";// Pesan error jika ada field yang kosong
        $type = "danger"; // Tipe pesan untuk error
    } else {

        // Ambil data user dari database
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND role='$role'"); // Query untuk mencari pengguna dengan email dan role yang sesuai
        $user = mysqli_fetch_assoc($query); // Ambil hasil query sebagai array asosiatif, jika tidak ada hasil maka $user akan bernilai null

        if ($user) {
            // Cek password (pakai hash)
            if (password_verify($password, $user['password'])) {

                $_SESSION['login'] = true; // Set session
                $_SESSION['email'] = $user['email']; // Simpan email pengguna di session untuk referensi di halaman lain
                $_SESSION['name'] = $user['name']; // Simpan nama pengguna di session untuk referensi di halaman lain
                $_SESSION['role'] = $user['role']; // Simpan role pengguna di session untuk referensi di halaman lain

                if (isset($_POST['remember'])) { // Cek jika checkbox "Remember Me" dicentang
                    setcookie("email", $user['email'], time() + 3600, "/"); // Set cookie email dengan nilai email pengguna, berlaku selama 1 jam
                }

                $message = "Login berhasil! Mengalihkan..."; // Pesan sukses jika login berhasil
                $type = "success"; // Tipe pesan untuk sukses

                header("refresh:1;url=dashboard.php"); // Redirect ke dashboard setelah 1 detik
            } else {
                $message = "Password salah!"; // Pesan error jika password tidak cocok
                $type = "danger";
            }
        } else {
            $message = "Akun tidak ditemukan!"; // Pesan error jika tidak ada pengguna dengan email dan role yang sesuai
            $type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeTrack - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .login-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background: white;
        }

        .btn-lifetrack {
            background: #0f766e;
            border: none;
            padding: 12px;
        }

        .btn-lifetrack:hover {
            background: #0d5f57;
            color: white;
        }

        .text-lifetrack {
            color: #0f766e;
        }

        footer {
            background: #0f766e;
            color: white;
            text-align: center;
            padding: 24px;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="card login-card p-4" style="width:360px;">

            <h4 class="fw-bold text-lifetrack mb-1">Selamat Datang</h4>
            <p class="text-muted mb-4">Masuk untuk melanjutkan ke LifeTrack</p>

            <?php if ($message != "") : ?>
                <!-- Cek jika ada pesan yang ingin ditampilkan -->
                <div class="alert alert-<?php echo $type; ?> py-2">
                    <!-- Tampilkan pesan dengan kelas alert sesuai tipe (danger atau success) -->
                    <?php echo $message; ?>
                    <!-- Tampilkan isi pesan -->
                </div>
            <?php endif; ?>
            <!-- Akhir dari pengecekan pesan -->

            <form method="POST" action="">
                <!-- Form untuk login dengan metode POST, action kosong berarti submit ke halaman yang sama -->

                <div class="mb-3">
                    <label class="form-label fw-semibold">Peran</label>
                    <select name="role" class="form-select">
                        <option value="">-- Pilih Peran --</option>
                        <option value="pasien" <?php echo (isset($role) && $role == 'pasien') ? 'selected' : ''; ?>>Pasien</option>
                        <!-- Opsi untuk pasien, akan terpilih jika sebelumnya sudah dipilih -->
                        <option value="tenaga_medis" <?php echo (isset($role) && $role == 'medis') ? 'selected' : ''; ?>>Tenaga Medis</option>
                        <!-- Opsi untuk tenaga medis, akan terpilih jika sebelumnya sudah dipilih -->
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    <!-- Input untuk email dengan nilai yang tetap setelah submit (jika ada) -->
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember">
                    <label class="form-check-label">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-lifetrack text-white w-100 fw-bold mt-2">
                    Login
                </button>

            </form>
            <div class="text-center mt-4">
                <small>
                    Belum punya akun?
                    <a href="registrasi.php" class="text-lifetrack fw-bold text-decoration-none">
                        Buat Akun
                    </a>
                </small>
            </div>
        </div>
    </div>

    <footer>
        LifeTrack © 2025 — Membantu Anda hidup lebih sehat.
    </footer>
</body>
</html>