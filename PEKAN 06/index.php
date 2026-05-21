<?php
/**
 * HALAMAN LOGIN - GALERI NUSANTARA
 * 
 * Fungsi:
 * 1. Proses login user dengan User::login()
 * 2. Validasi session (redirect ke dashboard jika sudah login)
 * 3. Tampilan form login glassmorphism UI
 * 4. Error handling untuk login gagal
 */

require_once 'config/database.php';
require_once 'classes/User.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // PROSES LOGIN FORM
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($user->login($username, $password)) {
        // SUKSES: Set session dan redirect ke dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // GAGAL: Tampilkan error message
        $error = 'Username atau password salah!';
    }
}


if (User::isLoggedIn()) {
    // CEK SESSION: Sudah login? Langsung ke dashboard
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<!-- UI LOGIN: Glassmorphism form dengan TailwindCSS + custom CSS -->
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Galeri Nusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #fdfbf6 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Glassmorphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(133, 77, 14, 0.1);
        }

        h1 { font-family: 'Crimson Pro', serif; }

        .input-glass {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(133, 77, 14, 0.1);
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            border-color: rgba(133, 77, 14, 0.4);
            background: rgba(255, 255, 255, 0.8);
            outline: none;
            box-shadow: 0 0 0 4px rgba(133, 77, 14, 0.05);
        }

        .btn-heritage {
            background: linear-gradient(135deg, #7c2d12 0%, #b45309 100%);
            transition: all 0.3s ease;
        }
        .btn-heritage:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(124, 45, 18, 0.2);
            opacity: 0.95;
        }

        /* Background Decorations */
        .bg-deco {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.4;
        }
        .deco-1 { width: 400px; height: 400px; background: #fef3c7; top: -10%; right: -5%; }
        .deco-2 { width: 500px; height: 500px; background: #e2e8f0; bottom: -15%; left: -10%; }
    </style>
</head>
<body class="p-6">

    <div class="bg-deco deco-1"></div>
    <div class="bg-deco deco-2"></div>

    <div class="w-full max-w-[440px]">
        <div class="text-center mb-8">
            <div class="inline-flex p-4 bg-white/40 rounded-3xl backdrop-blur-md border border-white/60 mb-4 shadow-sm">
                <i class="fas fa-landmark text-3xl text-amber-900"></i>
            </div>
            <h1 class="text-4xl font-bold text-amber-950">Galeri <span class="italic text-amber-800">Nusantara</span></h1>
            <p class="text-slate-500 text-sm mt-2 font-medium tracking-wide italic">Gerbang Pustaka Warisan Budaya</p>
        </div>

        <div class="glass-card p-10 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/batik-bg.png');"></div>

            <header class="text-center mb-8">
                <h2 class="text-xl font-bold text-amber-900 tracking-tight">Masuk ke Sistem</h2>
                <div class="h-1 w-12 bg-amber-800/20 mx-auto mt-2 rounded-full"></div>
            </header>

            <?php if (isset($error)): ?>
                <div class="bg-red-500/10 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3 animate-pulse">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div class="space-y-2">
                    <label class="text-xs uppercase font-bold text-slate-500 tracking-widest ml-1">Username</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        <input type="text" name="username" required 
                               class="w-full pl-11 pr-5 py-3.5 rounded-2xl input-glass text-slate-700 placeholder:text-slate-400" 
                               placeholder="Masukkan username">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs uppercase font-bold text-slate-500 tracking-widest ml-1">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" name="password" required 
                               class="w-full pl-11 pr-5 py-3.5 rounded-2xl input-glass text-slate-700 placeholder:text-slate-400" 
                               placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 btn-heritage text-white rounded-2xl font-bold shadow-lg mt-4 flex items-center justify-center gap-2">
                    <span>Masuk Pustaka</span>
                    <i class="fas fa-arrow-right text-xs opacity-70"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-amber-900/5 text-center">
                <p class="text-sm text-slate-500 mb-4">Belum memiliki akses?</p>
                <a href="register.php" class="inline-flex items-center gap-2 text-amber-800 font-semibold hover:text-amber-950 transition-colors">
                    <i class="fas fa-user-plus text-xs"></i>
                    <span>Daftar Akun Baru</span>
                </a>
            </div>
        </div>

        <footer class="mt-8 text-center">
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-bold">© 2026 Arsip Warisan Nusantara</p>
        </footer>
    </div>

</body>
</html>