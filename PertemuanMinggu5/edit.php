<?php
// 1. Inisialisasi Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Memuat Session dan Koneksi
require_once "session_init.php";
require_once "koneksi.php";

// Pastikan variabel koneksi tersedia
global $conn;

// 3. Proteksi Halaman: Hanya Admin yang bisa masuk
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

// 4. Validasi Parameter ID
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: dashboard.php");
    exit;
}

$id = (int) $_GET["id"];

// 5. Ambil Data User Lama
$queryUser = mysqli_query($conn, "SELECT * FROM datauser WHERE id = $id");
$user = mysqli_fetch_assoc($queryUser);

// Jika user tidak ditemukan
if (!$user) {
    header("Location: dashboard.php");
    exit;
}

// 6. Logika Update Data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $nama = mysqli_real_escape_string($conn, trim($_POST["nama"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $passwordInput = trim($_POST["password"]);

    if ($nama === "" || $email === "") {
        $error = "Nama dan email wajib diisi!";
    } else {
        // Cek apakah email sudah digunakan oleh orang lain
        $cekEmail = mysqli_query($conn, "SELECT id FROM datauser WHERE email = '$email' AND id != $id");

        if (mysqli_num_rows($cekEmail) > 0) {
            $error = "Email sudah digunakan oleh pengguna lain!";
        } else {
            // Susun query update dasar
            $updateSql = "UPDATE datauser SET name = '$nama', email = '$email'";

            // Update password hanya jika kolom password diisi
            if ($passwordInput !== "") {
                if (strlen($passwordInput) < 6) {
                    $error = "Password minimal 6 karakter!";
                } else {
                    $passwordHash = password_hash($passwordInput, PASSWORD_DEFAULT);
                    $updateSql .= ", password = '$passwordHash'";
                }
            }

            $updateSql .= " WHERE id = $id";

            if ($error === "") {
                if (mysqli_query($conn, $updateSql)) {
                    // Redirect kembali ke dashboard setelah berhasil
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Gagal memperbarui data: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - CyberVault</title>
    <link rel="stylesheet" href="HomePage.css">
    <style>
        /* Desain Khusus Halaman Edit (Sesuai Minggu 3/4) */
        body {
            background-color: #0a0a0f;
            color: #e0e6ed;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-header {
            background: rgba(10, 10, 15, 0.95);
            padding: 15px 50px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo { font-size: 24px; font-weight: bold; color: #00d4ff; text-decoration: none; }
        
        .edit-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .edit-card {
            background: #11111d;
            border: 1px solid rgba(0, 212, 255, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .edit-card h2 { color: #00d4ff; margin-top: 0; text-align: center; }
        .edit-card p { color: #8892b0; font-size: 14px; text-align: center; margin-bottom: 30px; }

        .form-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
        .form-group label { color: #00d4ff; font-size: 13px; font-weight: bold; }
        .form-group input {
            background: #0a0a0f;
            border: 1px solid #2a2a40;
            padding: 12px;
            border-radius: 8px;
            color: #fff;
            outline: none;
        }
        .form-group input:focus { border-color: #00d4ff; }

        .btn-update {
            background: #00d4ff;
            color: #000;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: 0.3s;
        }
        .btn-update:hover { background: #00b8e6; transform: translateY(-2px); }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #8892b0;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-back:hover { color: #fff; }

        .error-msg {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ff4757;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>
<body>

    <header class="main-header">
        <a href="dashboard.php" class="logo">CyberVault</a>
        <div style="color: #8892b0; font-size: 12px;">Mode Administrator</div>
    </header>

    <div class="edit-wrapper">
        <div class="edit-card">
            <h2>Edit Pengguna</h2>
            <p>Perbarui informasi akun anggota CyberVault.</p>

            <?php if ($error !== ""): ?>
                <div class="error-msg"><?= $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">
                    <small style="color: #555; font-size: 11px;">*Minimal 6 karakter jika ingin mengganti.</small>
                </div>

                <button type="submit" name="update" class="btn-update">Simpan Perubahan</button>
            </form>

            <a href="dashboard.php" class="btn-back">← Batal dan Kembali</a>
        </div>
    </div>

    <footer style="text-align: center; padding: 20px; color: #444; font-size: 12px; border-top: 1px solid #1a1a2e;">
        &copy; 2026 CyberVault Project - Kelompok 10 SIKC.
    </footer>

</body>
</html>