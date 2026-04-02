<?php
// File: register.php
require 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $cert_path = NULL;

    // Cek apakah email sudah terdaftar
    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
    
    if ($check_email->num_rows > 0) {
        $error = "Email sudah terdaftar!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan Konfirmasi Password tidak cocok!";
    } else {
        // Hash password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Proses upload file jika role adalah doctor
        if ($role === 'doctor' && isset($_FILES['cert_file']) && $_FILES['cert_file']['error'] === 0) {
            $target_dir = "uploads/";
            // Buat folder uploads jika belum ada
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES["cert_file"]["name"], PATHINFO_EXTENSION);
            // Rename file agar unik
            $new_filename = "CERT_" . time() . "_" . uniqid() . "." . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
            
            if (in_array(strtolower($file_extension), $allowed_types)) {
                if (move_uploaded_file($_FILES["cert_file"]["tmp_name"], $target_file)) {
                    $cert_path = $target_file;
                } else {
                    $error = "Gagal mengupload file sertifikasi.";
                }
            } else {
                $error = "Format file tidak diizinkan. Hanya PDF, JPG, JPEG, PNG.";
            }
        } elseif ($role === 'doctor') {
            $error = "File sertifikasi (STR/SIP) wajib diupload untuk dokter!";
        }

        // Jika tidak ada error, masukkan ke database
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, cert_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $cert_path);
            
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan: " . $conn->error;
            }
            $stmt->close();
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

            <?php if(!empty($error)): ?>
                <div style="color: red; margin-bottom: 10px; text-align: center;"><?= $error ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
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
        document.addEventListener("DOMContentLoaded", function () {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const certGroup = document.getElementById("certGroup");

            roleRadios.forEach(radio => {
                radio.addEventListener("change", function () {
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