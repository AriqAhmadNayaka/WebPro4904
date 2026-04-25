<?php
// VIEW: sekolah/auth/index.php
// Halaman autentikasi yang menampilkan dua section dalam satu file:
//   1. Form Login  — ditampilkan jika $active_section === 'login'
//   2. Form Register — ditampilkan jika $active_section === 'register'
//
// Variabel yang diterima dari Auth Controller (via array $data):
//   $notification      : Pesan notifikasi (error/sukses), kosong jika tidak ada
//   $notification_type : Tipe notifikasi untuk styling ('error' / 'success')
//   $active_section    : Menentukan section mana yang ditampilkan aktif ('login' / 'register')
//   $cookieEmail       : Email dari cookie "ingat saya" untuk pre-fill form (bisa kosong)
//   $cookieRole        : Role dari cookie "ingat saya" untuk pre-fill dropdown (bisa kosong)
//
// CATATAN PERBAIKAN CI3:
// Semua kode PHP native di bagian atas view (session_start(), require_once,
// new UserModel(), dll.) sudah DIHAPUS. Semua dihandle oleh Auth Controller.
// base_url() dari url helper digunakan untuk semua path aset dan action form.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSkill - Authentication</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- base_url() dari CI3 url helper menghasilkan URL lengkap ke folder assets -->
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
</head>
<body>

    <?php if (!empty($notification)): ?>
    <!-- Notifikasi PHP: ditampilkan jika controller mengirim pesan notifikasi -->
    <!-- Warna background berubah sesuai tipe: merah=error, hijau=sukses, biru=info -->
    <!-- Notifikasi ini muncul di pojok kanan atas layar (fixed position) -->
    <div id="phpNotification" style="
        position:fixed; top:20px; right:20px;
        background:<?= $notification_type === 'error' ? '#ef4444' : ($notification_type === 'success' ? '#10b981' : '#3b82f6') ?>;
        color:white; padding:14px 18px; border-radius:10px;
        font-size:13px; z-index:9999; font-family:'Poppins',sans-serif;
    ">
        <?= htmlspecialchars($notification) ?>
    </div>
    <?php endif; ?>

    <!-- Background dekoratif dengan efek blob gradient -->
    <div class="auth-bg">
        <div class="gradient-blob blob-1"></div>
        <div class="gradient-blob blob-2"></div>
        <div class="gradient-blob blob-3"></div>
    </div>

    <div class="auth-wrapper">

        <!-- SECTION LOGIN                                          -->
        <!-- Ditampilkan aktif jika $active_section === 'login'    -->
        <!-- (saat halaman pertama kali dibuka atau login gagal)    -->
        <div id="loginSection" class="auth-container <?= $active_section === 'login' ? 'active' : '' ?>"
             style="<?= $active_section !== 'login' ? 'display:none;' : '' ?>">
            <div class="auth-header">
                <div class="logo-container"><div class="logo-icon">InS</div></div>
                <h2 class="auth-title">Masuk ke Akun</h2>
                <p class="auth-subtitle">Akses platform pembelajaran Anda</p>
            </div>

            <!-- Form login: POST ke /auth/login (method login() di Auth Controller) -->
            <form id="loginForm" class="auth-form" method="POST" action="<?= base_url('auth/login') ?>">

                <div class="form-group">
                    <label for="login-email">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <!-- Pre-fill email dari cookie "ingat saya" jika tersedia -->
                        <input type="email" id="login-email" name="email" required placeholder="nama@email.com"
                               value="<?= htmlspecialchars($cookieEmail) ?>">
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
                            <!-- Pre-select role dari cookie jika sesuai -->
                            <option value="sekolah" <?= $cookieRole === 'sekolah' ? 'selected' : '' ?>>Sekolah</option>
                        </select>
                        <svg class="select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>

                <!-- Checkbox "Ingat Saya": jika dicentang, controller akan menyimpan cookie 30 hari -->
                <!-- Otomatis tercentang jika sudah ada cookie sebelumnya ($cookieEmail tidak kosong) -->
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;font-size:13px;">
                    <input type="checkbox" name="ingat_saya" id="ingat_saya"
                           <?= !empty($cookieEmail) ? 'checked' : '' ?>>
                    <label for="ingat_saya" style="cursor:pointer;color:#6b7280;">Ingat Saya selama 30 hari</label>
                </div>

                <button class="auth-btn" type="submit"><span>Masuk</span></button>
            </form>

            <div class="divider"><span>Atau lanjutkan dengan</span></div>
            <!-- Tombol social login (Google, GitHub, Microsoft) — dekoratif, belum terhubung API -->
            <div class="social-login">
                <button class="social-btn google-btn" title="Login dengan Google" type="button">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
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
            <!-- Link untuk beralih ke section register via JavaScript (tanpa reload halaman) -->
            <p class="auth-switch">Belum punya akun? <a onclick="switchToRegister()">Daftar di sini</a></p>
        </div>

        <!-- SECTION REGISTER                                       -->
        <!-- Ditampilkan aktif jika $active_section === 'register' -->
        <!-- (saat register gagal validasi, halaman ini yang aktif) -->
        <div id="registerSection" class="auth-container <?= $active_section === 'register' ? 'active' : '' ?>"
             style="<?= $active_section !== 'register' ? 'display:none;' : '' ?>">
            <div class="auth-header">
                <div class="logo-container"><div class="logo-icon">InS</div></div>
                <h2 class="auth-title">Buat Akun Baru</h2>
                <p class="auth-subtitle">Bergabunglah dengan InkluSkill hari ini</p>
            </div>

            <!-- Form register: POST ke /auth/register (method register() di Auth Controller) -->
            <form id="registerForm" class="auth-form" method="POST" action="<?= base_url('auth/register') ?>">

                <div class="form-group">
                    <label for="register-name">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input type="text" id="register-name" name="name" required placeholder="Masukkan nama lengkap Anda">
                    </div>
                </div>
                <div class="form-group">
                    <label for="register-email">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="register-email" name="email" required placeholder="nama@email.com">
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
                            <option value="sekolah">Sekolah</option>
                        </select>
                        <svg class="select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
                <button class="auth-btn" type="submit"><span>Daftar</span></button>
            </form>

            <div class="divider"><span>Atau lanjutkan dengan</span></div>
            <!-- Tombol social register (dekoratif) -->
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
            <!-- Link untuk beralih ke section login via JavaScript -->
            <p class="auth-switch">Sudah punya akun? <a onclick="switchToLogin()">Masuk di sini</a></p>
        </div>

    </div>

    <script>
        // Ambil referensi elemen section login dan register
        const loginSection    = document.getElementById('loginSection');
        const registerSection = document.getElementById('registerSection');

        // switchToLogin(): Animasi transisi dari section register ke section login
        // Menggunakan CSS class 'active' dan setTimeout untuk efek fade
        function switchToLogin() {
            registerSection.classList.remove('active');
            setTimeout(() => {
                registerSection.style.display = 'none';
                loginSection.style.display = 'block';
                setTimeout(() => loginSection.classList.add('active'), 10);
            }, 300);
        }

        // switchToRegister(): Animasi transisi dari section login ke section register
        function switchToRegister() {
            loginSection.classList.remove('active');
            setTimeout(() => {
                loginSection.style.display = 'none';
                registerSection.style.display = 'block';
                setTimeout(() => registerSection.classList.add('active'), 10);
            }, 300);
        }

        // Auto-dismiss: notifikasi PHP otomatis menghilang setelah 3.5 detik
        const notif = document.getElementById('phpNotification');
        if (notif) setTimeout(() => notif.remove(), 3500);
    </script>

</body>
</html>
