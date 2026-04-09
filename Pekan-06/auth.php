<?php
// Session saya aktifkan dari awal supaya status login bisa dipakai di seluruh halaman
session_start();

require_once 'app/bootstrap.php';

const LOGIN_SECTION = 'login';
const REGISTER_SECTION = 'register';

// Redirect per role saya kumpulkan di sini biar alurnya gampang diatur
$redirectByRole = [
    'Orang Tua' => './dasboard/dashboardOrtu.php',
    'orang tua' => './dasboard/dashboardOrtu.php',
    'orangtua'  => './dasboard/dashboardOrtu.php',
];

// Input dari form saya rapikan dulu supaya lebih aman dan hasilnya tetap konsisten
function cleanInput(string $value): string
{
    return htmlspecialchars(trim(stripslashes($value)), ENT_QUOTES, 'UTF-8');
}

function postValue(string $key): string
{
    return cleanInput($_POST[$key] ?? '');
}

// Saya pisahkan notifikasi ke fungsi kecil supaya pengaturan pesan tidak berulang-ulang
// Notifikasi saya pisahkan ke fungsi kecil biar tidak berulang-ulang saat set pesan
function showNotification(string &$message, string &$type, string $nextMessage, string $nextType = 'error'): void
{
    $message = $nextMessage;
    $type = $nextType;
}

// Redirect saya bungkus ke fungsi sendiri biar pemanggilannya singkat dan enak dibaca
// Redirect saya bungkus ke fungsi supaya bagian proses login dan register lebih ringkas
function goToRolePage(string $role, array $redirectByRole): void
{
    header('Location: ' . ($redirectByRole[$role] ?? '#'));
    exit;
}

// Cookie ini saya pakai untuk fitur "ingat saya" agar email dan role bisa terisi otomatis
function updateRememberMe(array $user, bool $remember): void
{
    if ($remember) {
        setcookie('inklu_email', $user['email'], time() + (86400 * 30), '/');
        setcookie('inklu_role', $user['role'], time() + (86400 * 30), '/');
        return;
    }

    setcookie('inklu_email', '', time() - 3600, '/');
    setcookie('inklu_role', '', time() - 3600, '/');
}

if (isset($_GET['logout'])) {
    // Saat logout, session saya hapus supaya user keluar sepenuhnya dari sistem
    session_unset();
    session_destroy();
    header('Location: auth.php');
    exit;
}

$notification = '';
$notificationType = '';
$activeSection = LOGIN_SECTION;
$cookieEmail = $_COOKIE['inklu_email'] ?? '';
$cookieRole = $_COOKIE['inklu_role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === REGISTER_SECTION) {
        // Bagian ini khusus untuk proses registrasi, mulai dari validasi sampai simpan akun
        $activeSection = REGISTER_SECTION;

        $name = postValue('name');
        $email = postValue('email');
        $password = $_POST['password'] ?? '';
        $role = postValue('role');

        if ($name === '' || $email === '' || $password === '' || $role === '') {
            showNotification($notification, $notificationType, 'Semua field wajib diisi');
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $name)) {
            showNotification($notification, $notificationType, 'Nama hanya boleh berisi huruf dan spasi');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            showNotification($notification, $notificationType, 'Format email tidak valid');
        } elseif (strlen($password) < 6) {
            showNotification($notification, $notificationType, 'Password harus minimal 6 karakter');
        } elseif (!preg_match('/[\W]/', $password)) {
            showNotification($notification, $notificationType, 'Password harus mengandung minimal 1 karakter spesial (!@#$%^&*)');
        } elseif ($userRepository->emailExists($email)) {
            showNotification($notification, $notificationType, 'Email sudah terdaftar');
        } else {
            $newUserId = $userRepository->create(
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $role
            );

            if ($newUserId > 0) {
                $_SESSION['current_user'] = [
                    'id' => $newUserId,
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                ];

                goToRolePage($role, $redirectByRole);
            }

            showNotification($notification, $notificationType, 'Registrasi gagal, silakan coba lagi');
        }
    }

    if ($action === LOGIN_SECTION) {
        // Bagian login ini saya pakai untuk mencocokkan data user lalu menyimpan session
        $activeSection = LOGIN_SECTION;

        $email = postValue('email');
        $password = $_POST['password'] ?? '';
        $role = postValue('role');

        if ($email === '' || $password === '' || $role === '') {
            showNotification($notification, $notificationType, 'Semua field wajib diisi');
        } else {
            $foundUser = $userRepository->findByEmailAndRole($email, $role);
            $isValidUser = $foundUser && password_verify($password, $foundUser['password']);

            if (!$isValidUser) {
                showNotification($notification, $notificationType, 'Email, password, atau role salah');
            } else {
                $_SESSION['current_user'] = [
                    'id' => $foundUser['id'],
                    'name' => $foundUser['name'],
                    'email' => $foundUser['email'],
                    'role' => $foundUser['role'],
                ];

                updateRememberMe($foundUser, isset($_POST['ingat_saya']));
                goToRolePage($foundUser['role'], $redirectByRole);
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

    <?php if ($notification !== ''): ?>
    <div id="phpNotification" style="
        position:fixed; top:20px; right:20px;
        background:<?= $notificationType === 'error' ? '#ef4444' : ($notificationType === 'success' ? '#10b981' : '#3b82f6') ?>;
        color:white; padding:14px 18px; border-radius:10px;
        font-size:13px; z-index:9999; font-family:'Poppins',sans-serif;
    ">
        <?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php endif; ?>

    <div class="auth-bg">
        <div class="gradient-blob blob-1"></div>
        <div class="gradient-blob blob-2"></div>
        <div class="gradient-blob blob-3"></div>
    </div>

    <div class="auth-wrapper">
        <div id="loginSection" class="auth-container <?= $activeSection === LOGIN_SECTION ? 'active' : '' ?>"
             style="<?= $activeSection !== LOGIN_SECTION ? 'display:none;' : '' ?>">
            <div class="auth-header">
                <div class="logo-container"><div class="logo-icon">InS</div></div>
                <h2 class="auth-title">Masuk ke Akun</h2>
                <p class="auth-subtitle">Akses platform pembelajaran Anda</p>
            </div>

            <form id="loginForm" class="auth-form" method="POST" action="auth.php">
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label for="login-email">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <input type="email" id="login-email" name="email" required placeholder="nama@email.com"
                               value="<?= htmlspecialchars($cookieEmail, ENT_QUOTES, 'UTF-8') ?>">
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
                            <option value="">Pilih tipe pengguna</option>
                            <option value="Orang Tua" <?= $cookieRole === 'Orang Tua' ? 'selected' : '' ?>>Orang Tua</option>
                        </select>
                        <svg class="select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;font-size:13px;">
                    <input type="checkbox" name="ingat_saya" id="ingat_saya"
                           <?= $cookieEmail !== '' ? 'checked' : '' ?>>
                    <label for="ingat_saya" style="cursor:pointer;color:#6b7280;">Ingat Saya selama 30 hari</label>
                </div>

                <button class="auth-btn" type="submit"><span>Masuk</span></button>
            </form>

            <p class="auth-switch">Belum punya akun? <a onclick="switchToRegister()">Daftar di sini</a></p>
        </div>

        <div id="registerSection" class="auth-container <?= $activeSection === REGISTER_SECTION ? 'active' : '' ?>"
             style="<?= $activeSection !== REGISTER_SECTION ? 'display:none;' : '' ?>">
            <div class="auth-header">
                <div class="logo-container"><div class="logo-icon">InS</div></div>
                <h2 class="auth-title">Buat Akun Baru</h2>
                <p class="auth-subtitle">Bergabunglah dengan InkluSkill hari ini</p>
            </div>

            <form id="registerForm" class="auth-form" method="POST" action="auth.php">
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label for="register-name">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input type="text" id="register-name" name="name" required placeholder="Masukkan nama lengkap Anda"
                               value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="register-email">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="register-email" name="email" required placeholder="nama@email.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
                        <select id="register-role" name="role" required>
                            <option value="">Pilih tipe pengguna</option>
                            <option value="Orang Tua" <?= ($_POST['role'] ?? '') === 'Orang Tua' ? 'selected' : '' ?>>Orang Tua</option>
                        </select>
                        <svg class="select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>

                <button class="auth-btn" type="submit"><span>Daftar</span></button>
            </form>

            </div>

            <p class="auth-switch">Sudah punya akun? <a onclick="switchToLogin()">Masuk di sini</a></p>
        </div>
    </div>

    <script>
        const loginSection = document.getElementById('loginSection');
        const registerSection = document.getElementById('registerSection');

        function switchToLogin() {
            registerSection.classList.remove('active');
            setTimeout(() => {
                registerSection.style.display = 'none';
                loginSection.style.display = 'block';
                setTimeout(() => loginSection.classList.add('active'), 10);
            }, 300);
        }

        function switchToRegister() {
            loginSection.classList.remove('active');
            setTimeout(() => {
                loginSection.style.display = 'none';
                registerSection.style.display = 'block';
                setTimeout(() => registerSection.classList.add('active'), 10);
            }, 300);
        }

        const notif = document.getElementById('phpNotification');
        if (notif) {
            setTimeout(() => notif.remove(), 3500);
        }
    </script>

</body>
</html>
