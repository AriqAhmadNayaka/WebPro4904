<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Nuids</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('register.css') ?>">
</head>

<body>
    <!-- Halaman register: tampilan form, proses simpan ada di Auth::register -->
    <div class="container-register">
        <h2 class="greeting">Hi! Selamat Datang di Nuids.</h2>

        <div class="acrylic-card">
            <div class="logo-container">
                <img src="<?= base_url('logo.png') ?>" alt="" width="50px">
            </div>

            <!-- Feedback validasi dari controller -->
            <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 10px; text-align: center;"><?= $error ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div style="color: green; margin-bottom: 10px; text-align: center;"><?= $success ?></div>
            <?php endif; ?>

            <!-- enctype multipart dipakai karena role dokter bisa upload file -->
            <form action="<?= base_url('index.php?c=auth&m=register') ?>" method="POST" enctype="multipart/form-data">
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
                    <input id="regCertFile" name="cert_file" type="file" class="custom-input" accept=".pdf,.jpg,.jpeg,.png,.docx">
                </div>

                <button type="submit" class="btn-register">
                    <span class="btn-text">Register</span>
                </button>
            </form>
            <a href="<?= base_url('index.php?c=auth&m=login') ?>">Sudah punya akun? Login</a>
        </div>
    </div>

    <script src="<?= base_url('main.js') ?>"></script>
    <script>
        // UI helper: tampilkan input sertifikat hanya saat role = doctor.
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
