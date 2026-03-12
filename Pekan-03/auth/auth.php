<?php
// Memulai session agar data login bisa diakses di seluruh halaman
session_start();

// Jika ada ?logout=1 di URL, hapus session dan arahkan kembali ke halaman login
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: auth.php');
    exit;
}

// Daftar halaman tujuan redirect setelah login, disesuaikan dengan role user
// Pada praktik pekan 3 ini hanya bisa mengakses sekolah
$ROLE_REDIRECT = [
    'sekolah'  => '../sekolah/dashboard/dashboardSekolah.php',
    // 'orangtua'  => '../orangtua/dashboard/dashboardOrtu.php',
    // 'dinas'  => '../instansi/dinas.php',
    // 'admin'  => '../admin/dashboard/dashboardAdmin.php',
];

// Path file JSON sebagai pengganti database, __DIR__ agar path selalu relatif ke file ini
$USERS_FILE = __DIR__ . '/users.json';

// Membaca data user dari file JSON, kembalikan array kosong jika file belum ada
function getUsers($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?: [];
}

// Menyimpan array user ke file JSON
function saveUsers($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

// Membersihkan input dari spasi berlebih, backslash, dan karakter berbahaya (XSS)
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Variabel notifikasi dan penanda section aktif (login/register)
$notification      = '';
$notification_type = '';
$active_section    = 'login';

// Blok ini hanya berjalan saat form dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // REGISTRASI
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $active_section = 'register';

        // Ambil dan bersihkan semua input dari form registrasi
        $name     = test_input($_POST['name']     ?? '');
        $email    = test_input($_POST['email']    ?? '');
        $password = $_POST['password']            ?? '';
        $role     = test_input($_POST['role']     ?? '');

        // Validasi field wajib diisi
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            $notification      = 'Semua field wajib diisi';
            $notification_type = 'error';

        // preg_match: nama hanya boleh huruf dan spasi
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $notification      = 'Nama hanya boleh berisi huruf dan spasi';
            $notification_type = 'error';

        // filter_var: validasi format email
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $notification      = 'Format email tidak valid';
            $notification_type = 'error';

        // strlen: password minimal 6 karakter
        } elseif (strlen($password) < 6) {
            $notification      = 'Password harus minimal 6 karakter';
            $notification_type = 'error';

        // preg_match: password harus mengandung minimal 1 karakter spesial
        } elseif (!preg_match("/[\W]/", $password)) {
            $notification      = 'Password harus mengandung minimal 1 karakter spesial (!@#$%^&*)';
            $notification_type = 'error';

        } else {
            $users = getUsers($USERS_FILE);

            // Cek apakah email sudah terdaftar
            $emailExists = false;
            foreach ($users as $u) {
                if ($u['email'] === $email) { $emailExists = true; break; }
            }

            if ($emailExists) {
                $notification      = 'Email sudah terdaftar';
                $notification_type = 'error';
            } else {
                // Buat data user baru, password di-hash agar tidak tersimpan sebagai teks biasa
                $newUser = [
                    'id'       => time(),
                    'name'     => $name,
                    'email'    => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role'     => $role,
                ];
                $users[] = $newUser;
                saveUsers($USERS_FILE, $users);

                // Simpan data ke session lalu redirect ke dashboard sesuai role
                $_SESSION['current_user'] = [
                    'id'    => $newUser['id'],
                    'name'  => $newUser['name'],
                    'email' => $newUser['email'],
                    'role'  => $newUser['role'],
                ];
                header("Location: " . ($ROLE_REDIRECT[$role] ?? '#'));
                exit;
            }
        }

    // === LOGIN ===
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        $active_section = 'login';

        // Ambil dan bersihkan input dari form login
        $email    = test_input($_POST['email']    ?? '');
        $password = $_POST['password']            ?? '';
        $role     = test_input($_POST['role']     ?? '');

        if (empty($email) || empty($password) || empty($role)) {
            $notification      = 'Semua field wajib diisi';
            $notification_type = 'error';
        } else {
            $users     = getUsers($USERS_FILE);
            $foundUser = null;

            // Cari user yang cocok berdasarkan email, role, dan verifikasi password dengan hash
            foreach ($users as $u) {
                if ($u['email'] === $email && password_verify($password, $u['password']) && $u['role'] === $role) {
                    $foundUser = $u;
                    break;
                }
            }

            if (!$foundUser) {
                $notification      = 'Email, password, atau role salah';
                $notification_type = 'error';
            } else {
                // Login berhasil — simpan ke session dan redirect ke dashboard
                $_SESSION['current_user'] = [
                    'id'    => $foundUser['id'],
                    'name'  => $foundUser['name'],
                    'email' => $foundUser['email'],
                    'role'  => $foundUser['role'],
                ];
                header("Location: " . ($ROLE_REDIRECT[$foundUser['role']] ?? '#'));
                exit;
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
    <title>InkluSkill - Authentication</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="auth.css">
</head>
<body>

    <?php if (!empty($notification)): ?>
    <!-- Notifikasi error/sukses dari proses login atau registrasi, hilang otomatis via JS -->
    <div id="phpNotification" style="
        position:fixed; top:20px; right:20px;
        background:<?= $notification_type === 'error' ? '#ef4444' : ($notification_type === 'success' ? '#10b981' : '#3b82f6') ?>;
        color:white; padding:14px 18px; border-radius:10px;
        font-size:13px; z-index:9999; font-family:'Poppins',sans-serif;
    ">
        <?= htmlspecialchars($notification) ?>
    </div>
    <?php endif; ?>

    <div class="auth-bg">
        <div class="gradient-blob blob-1"></div>
        <div class="gradient-blob blob-2"></div>
        <div class="gradient-blob blob-3"></div>
    </div>

    <div class="auth-wrapper">

        <!-- Section login, ditampilkan jika $active_section = 'login' -->
        <div id="loginSection" class="auth-container <?= $active_section === 'login' ? 'active' : '' ?>"
             style="<?= $active_section !== 'login' ? 'display:none;' : '' ?>">
            <div class="auth-header">
                <div class="logo-container"><div class="logo-icon">InS</div></div>
                <h2 class="auth-title">Masuk ke Akun</h2>
                <p class="auth-subtitle">Akses platform pembelajaran Anda</p>
            </div>

            <!-- Form login dikirim via POST, action=login untuk dibedakan dari register di PHP -->
            <form id="loginForm" class="auth-form" method="POST" action="auth.php">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label for="login-email">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <input type="email" id="login-email" name="email" required placeholder="nama@email.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="login-password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" id="login-password" name="password" required placeholder="Masukkan password Anda">
                    </div>
                </div>

                <div class="form-group">
                    <label for="login-role">Tipe Pengguna</label>
                    <div class="select-wrapper">
                        <select id="login-role" name="role" required>
                            <option value="">Hanya Tipe Sekolah yang Boleh Dipilih</option>
                            <option value="sekolah">Sekolah</option>
                            <option value="orangtua">Orang Tua</option>
                            <option value="dinas">Dinas/BLK</option>
                            <option value="admin">Admin Sistem</option>
                        </select>
                        <svg class="select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <button class="auth-btn" type="submit"><span>Masuk</span></button>
            </form>

            <div class="divider"><span>Atau lanjutkan dengan</span></div>

            <div class="social-login">
                <button class="social-btn google-btn" title="Login dengan Google" type="button">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </button>
                <button class="social-btn github-btn" title="Login dengan GitHub" type="button">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    GitHub
                </button>
                <button class="social-btn microsoft-btn" title="Login dengan Microsoft" type="button">
                    <svg viewBox="0 0 24 24" width="20" height="20"><rect x="2" y="2" width="8" height="8" fill="currentColor"/><rect x="14" y="2" width="8" height="8" fill="currentColor"/><rect x="2" y="14" width="8" height="8" fill="currentColor"/><rect x="14" y="14" width="8" height="8" fill="currentColor"/></svg>
                    Microsoft
                </button>
            </div>

            <p class="auth-switch">Belum punya akun? <a onclick="switchToRegister()">Daftar di sini</a></p>
        </div>

        <!-- Section register, ditampilkan jika $active_section = 'register' (misal setelah error register) -->
        <div id="registerSection" class="auth-container <?= $active_section === 'register' ? 'active' : '' ?>"
             style="<?= $active_section !== 'register' ? 'display:none;' : '' ?>">
            <div class="auth-header">
                <div class="logo-container"><div class="logo-icon">InS</div></div>
                <h2 class="auth-title">Buat Akun Baru</h2>
                <p class="auth-subtitle">Bergabunglah dengan InkluSkill hari ini</p>
            </div>

            <!-- Form register dikirim via POST, action=register untuk dibedakan dari login di PHP -->
            <form id="registerForm" class="auth-form" method="POST" action="auth.php">
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label for="register-name">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <!-- value diisi ulang dari $_POST agar tidak kosong saat ada error validasi -->
                        <input type="text" id="register-name" name="name" required placeholder="Masukkan nama lengkap Anda"
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="register-email">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="register-email" name="email" required placeholder="nama@email.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="register-password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="register-password" name="password" required placeholder="Buat password yang kuat">
                    </div>
                </div>

                <div class="form-group">
                    <label for="register-role">Tipe Pengguna</label>
                    <div class="select-wrapper">
                        <!-- selected dipilih ulang dari $_POST agar tidak reset saat ada error validasi -->
                        <select id="register-role" name="role" required>
                            <option value="">Hanya Tipe Sekolah yang Boleh Dipilih</option>
                            <option value="sekolah"  <?= ($_POST['role'] ?? '') === 'sekolah'  ? 'selected' : '' ?>>Sekolah</option>
                            <option value="orangtua" <?= ($_POST['role'] ?? '') === 'orangtua' ? 'selected' : '' ?>>Orang Tua</option>
                            <option value="dinas"    <?= ($_POST['role'] ?? '') === 'dinas'    ? 'selected' : '' ?>>Dinas/BLK</option>
                            <option value="admin"    <?= ($_POST['role'] ?? '') === 'admin'    ? 'selected' : '' ?>>Admin Sistem</option>
                        </select>
                        <svg class="select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>

                <button class="auth-btn" type="submit"><span>Daftar</span></button>
            </form>

            <div class="divider"><span>Atau lanjutkan dengan</span></div>

            <div class="social-login">
                <button class="social-btn google-btn" title="Daftar dengan Google" type="button">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </button>
                <button class="social-btn github-btn" title="Daftar dengan GitHub" type="button">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    GitHub
                </button>
                <button class="social-btn microsoft-btn" title="Daftar dengan Microsoft" type="button">
                    <svg viewBox="0 0 24 24" width="20" height="20"><rect x="2" y="2" width="8" height="8" fill="currentColor"/><rect x="14" y="2" width="8" height="8" fill="currentColor"/><rect x="2" y="14" width="8" height="8" fill="currentColor"/><rect x="14" y="14" width="8" height="8" fill="currentColor"/></svg>
                    Microsoft
                </button>
            </div>

            <p class="auth-switch">Sudah punya akun? <a onclick="switchToLogin()">Masuk di sini</a></p>
        </div>

    </div>

    <script>
        const loginSection    = document.getElementById('loginSection');
        const registerSection = document.getElementById('registerSection');

        // Beralih ke form login dengan animasi transisi
        function switchToLogin() {
            registerSection.classList.remove('active');
            setTimeout(() => {
                registerSection.style.display = 'none';
                loginSection.style.display = 'block';
                setTimeout(() => loginSection.classList.add('active'), 10);
            }, 300);
        }

        // Beralih ke form register dengan animasi transisi
        function switchToRegister() {
            loginSection.classList.remove('active');
            setTimeout(() => {
                loginSection.style.display = 'none';
                registerSection.style.display = 'block';
                setTimeout(() => registerSection.classList.add('active'), 10);
            }, 300);
        }

        // Notifikasi dari PHP hilang otomatis setelah 3.5 detik
        const notif = document.getElementById('phpNotification');
        if (notif) setTimeout(() => notif.remove(), 3500);
    </script>

</body>
</html>