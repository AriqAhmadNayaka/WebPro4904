<?php
$message = ""; // Variabel untuk menyimpan pesan yang akan ditampilkan ke pengguna
$type = ""; // Variabel untuk menyimpan tipe pesan (success atau danger)

// Inisialisasi variabel agar tidak error saat pertama kali dimuat
$role = ""; // Variabel untuk menyimpan peran pengguna (pasien atau tenaga medis)
$name = ""; // Variabel untuk menyimpan nama pengguna
$email = ""; // Variabel untuk menyimpan email pengguna

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Cek jika form disubmit dengan metode POST

    $role     = $_POST['role'] ?? ""; // Ambil nilai role dari form, jika tidak ada set ke string kosong
    $name     = trim($_POST['name'] ?? ""); // Ambil nilai name dari form, bersihkan spasi, jika tidak ada set ke string kosong
    $email    = trim($_POST['email'] ?? ""); // Ambil nilai email dari form, bersihkan spasi, jika tidak ada set ke string kosong
    $password = trim($_POST['password'] ?? ""); // Ambil nilai password dari form, bersihkan spasi, jika tidak ada set ke string kosong

    if ($role == "" || $name == "" || $email == "" || $password == "") { // Validasi semua field harus diisi
        $message = "Semua field wajib diisi!"; // Pesan error jika ada field yang kosong
        $type = "danger"; // Tipe pesan untuk error
    }
    elseif (strlen($name) < 3) { // Validasi nama minimal 3 karakter
        $message = "Nama minimal 3 karakter!"; // Pesan error jika nama terlalu pendek
        $type = "danger"; // Tipe pesan untuk error
    }
    elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) { // Validasi nama hanya boleh mengandung huruf dan spasi
        $message = "Nama tidak boleh mengandung angka atau simbol!"; // Pesan error jika nama mengandung karakter yang tidak valid
        $type = "danger"; // Tipe pesan untuk error
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validasi format email
        $message = "Format email tidak valid!"; // Pesan error jika format email tidak benar
        $type = "danger"; // Tipe pesan untuk error
    }
    elseif (strlen($password) < 6) { // Validasi password minimal 6 karakter
        $message = "Password minimal 6 karakter!"; // Pesan error jika password terlalu pendek
        $type = "danger"; // Tipe pesan untuk error
    }
    else {
        // Validasi berhasil, di sini Anda bisa menambahkan kode untuk menyimpan data ke database atau file
        $message = "Akun berhasil dibuat! Silakan login.";
        $type = "success";
        
        // Redirect ke halaman login setelah 2 detik
        header("refresh:2;url=login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeTrack - Buat Akun</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .signin-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
        }

        .signin-card {
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            background: white;
        }

        .btn-lifetrack {
            background: #0f766e;
            border: none;
            padding: 10px;
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
<div class="signin-wrapper">
    <div class="card signin-card p-4" style="width:360px;">

        <h4 class="fw-bold text-lifetrack mb-1">Buat Akun</h4>
        <p class="text-muted mb-4">Daftar untuk mulai menggunakan LifeTrack</p>

        <?php if ($message != "") : ?>
            <!-- Cek jika ada pesan yang ingin ditampilkan -->
            <div class="alert alert-<?php echo $type; ?> py-2 small">
                <!-- Alert dengan tipe sesuai variabel $type dan padding kecil -->
                <?php echo $message; ?>
                <!-- Tampilkan isi pesan -->
            </div>
        <?php endif; ?>
        <!-- Akhir dari pengecekan pesan -->

        <form method="POST" action="">
            <!-- Form untuk registrasi dengan metode POST -->

            <div class="mb-3">
                <label class="form-label fw-semibold">Daftar sebagai</label>
                <select name="role" class="form-select">
                    <option value="">Pilih peran</option>
                    <option value="pasien" <?php if($role == "pasien") echo "selected"; ?>>Pasien</option>
                    <!-- Opsi untuk peran pasien -->
                    <option value="tenaga_medis" <?php if($role == "tenaga_medis") echo "selected"; ?>>Tenaga Medis</option>
                    <!-- Opsi untuk peran tenaga medis -->
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Masukkan nama lengkap"
                    value="<?php echo htmlspecialchars($name); ?>"
                >
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Masukkan email"
                    value="<?php echo htmlspecialchars($email); ?>"
                >
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Buat password"
                >
            </div>

            <button type="submit" class="btn btn-lifetrack text-white w-100 fw-bold mt-2">
                Buat Akun
            </button>

        </form>

        <div class="text-center mt-3">
            <small>
                Sudah punya akun?
                <a href="login.php" class="text-lifetrack fw-bold text-decoration-none">
                    Login
                </a>
            </small>
        </div>

    </div>
</div>

<footer>
    LifeTrack © 2025 — Membantu Anda hidup lebih sehat.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>