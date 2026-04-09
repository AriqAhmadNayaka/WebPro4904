<?php
// Memuat file inisialisasi (autoloader & session)
require_once 'config/init.php';

// Proteksi halaman: jika sudah login, lempar ke dashboard
Auth::redirectIfLoggedIn();

$error = '';
$success = '';

// Flow Registrasi: Menangani pengiriman form POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    $userModel = new User();
    
    // 1. Validasi Email: Cek apakah email sudah terdaftar
    $check_email = $userModel->findByEmail($email);

    if ($check_email) {
        $error = "Email sudah terdaftar!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan Konfirmasi Password tidak cocok!";
    } else {
        // 2. Flow Khusus Dokter: Menangani upload sertifikat
        $doctorData = null;
        if ($role === 'doctor') {
            if (isset($_FILES['cert_file']) && $_FILES['cert_file']['error'] === 0) {
                $upload = FileHelper::upload($_FILES["cert_file"]);
                if ($upload['status'] === 'success') {
                    $doctorData = [
                        "nama" => $name,
                        "sertifikat" => $upload["filename"],
                        "email" => $email
                    ];
                } else {
                    $error = $upload['msg'];
                }
            } else {
                $error = "File sertifikasi (STR/SIP) wajib diupload untuk dokter!";
            }
        }

        // 3. Simpan Data: Jika validasi lolos, masukkan ke database
        if (empty($error)) {
            if ($userModel->register($name, $email, $password, $role)) {
                // Jika role adalah dokter, simpan juga ke tabel dokter
                if ($role === 'doctor' && $doctorData) {
                    $doctorModel = new Doctor();
                    $doctorModel->create($doctorData);
                }
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat registrasi.";
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
    <title>Register Nuids</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <div class="container-register">
        <h2 class="greeting">Hi! Selamat Datang di Nuids.</h2>

        <div class="acrylic-card">
            <div class="logo-container">
                <img src="logo.png" alt="" width="50px">
            </div>

            <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 10px; text-align: center;"><?= $error ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div style="color: green; margin-bottom: 10px; text-align: center;"><?= $success ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="name" class="custom-input input-teal" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="custom-input input-pink-blue" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="custom-input input-pink" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" class="custom-input input-pink" placeholder="Konfirmasi Password" required>
                </div>

                <div class="form-group">
                    <div class="role-group">
                        <label>Pilih tipe akun:</label>
                        <div class="role-options">
                            <label><input type="radio" name="role" value="user" checked> Pengguna</label>
                            <label><input type="radio" name="role" value="doctor"> Dokter</label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="certGroup" style="display: none; flex-direction: column; align-items: center">
                    <div style="width: 85%; text-align: left">
                        <label for="regCertFile">Upload sertifikasi (STR/SIP) *</label>
                    </div>
                    <input id="regCertFile" name="cert_file" type="file" class="custom-input" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <button type="submit" class="btn-register">
                    <span class="btn-text">Register</span>
                </button>
            </form>
            <a href="login.php">Sudah punya akun? Login</a>
        </div>
    </div>

    <script src="main.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const certGroup = document.getElementById("certGroup");

            roleRadios.forEach(radio => {
                radio.addEventListener("change", function() {
                    if (this.value === "doctor") {
                        certGroup.style.display = "flex";
                        document.getElementById("regCertFile").setAttribute("required", "required");
                    } else {
                        certGroup.style.display = "none";
                        document.getElementById("regCertFile").removeAttribute("required");
                        document.getElementById("regCertFile").value = "";
                    }
                });
            });
        });
    </script>
</body>

</html>