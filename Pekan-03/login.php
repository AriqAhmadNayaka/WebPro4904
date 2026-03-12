<?php
// Inisialisasi variabel untuk menyimpan pesan dan tipe pesan
$message = ""; // Tempat menyimpan pesan (error atau sukses)
$type = ""; // Tipe pesan (danger untuk error, success untuk sukses)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah form disubmit dengan metode POST
    $role = $_POST["role"] ?? ""; // Ambil nilai role, gunakan null coalescing operator untuk menghindari error jika tidak ada
    $email = trim($_POST["email"]); // Ambil dan bersihkan input email
    $password = trim($_POST["password"]); // Ambil dan bersihkan input password

    if (empty($role) || empty($email) || empty($password)) {
        // Validasi semua field harus diisi
        $message = "Semua field wajib diisi!"; // Pesan error jika ada field yang kosong
        $type = "danger"; // Tipe pesan untuk error
    } elseif (strlen($password) < 6) {
        // Validasi panjang password
        $message = "Password minimal 6 karakter!"; // Pesan error jika password terlalu pendek
        $type = "danger"; // Tipe pesan untuk error
    } else {
        // Validasi berhasil
        $message = "Login berhasil! Mengalihkan..."; // Pesan sukses
        $type = "success"; // Pesan sukses
        
        $target = ($role === "medis") ? "dashboard.php" : "dashboard.php"; // Tentukan halaman tujuan berdasarkan role (saat ini sama untuk kedua role)
        
        header("refresh:1.2;url=$target"); // Redirect setelah 1.2 detik
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
                        <option value="medis" <?php echo (isset($role) && $role == 'medis') ? 'selected' : ''; ?>>Tenaga Medis</option>
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