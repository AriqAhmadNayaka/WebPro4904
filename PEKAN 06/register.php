<?php
/**
 * HALAMAN REGISTRASI - GALERI NUSANTARA
 * 
 * Fungsi:
 * 1. Proses pendaftaran baru dengan User::register()
 * 2. Validasi input (username unique, password length, format)
 * 3. Feedback success/error
 * 4. Redirect ke dashboard jika sudah login
 * 5. Link kembali ke login
 */

require_once 'config/database.php';
require_once 'classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = User::register($username, $password);
    
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['error'];
    }
}

if (User::isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Galeri Nusantara</title>
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
            position: relative;
            overflow-x: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(133, 77, 14, 0.1);
        }

        h1, h2 { font-family: 'Crimson Pro', serif; }

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
        }

        .btn-register {
            background: linear-gradient(135deg, #92400e 0%, #78350f 100%);
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(124, 45, 18, 0.2);
        }

        /* Dekorasi Latar */
        .bg-deco {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            opacity: 0.5;
        }
        .deco-1 { width: 450px; height: 450px; background: #fef3c7; top: -10%; left: -5%; }
        .deco-2 { width: 400px; height: 400px; background: #dcfce7; bottom: -10%; right: -5%; }
    </style>
</head>
<body class="p-6">

    <div class="bg-deco deco-1"></div>
    <div class="bg-deco deco-2"></div>

    <div class="w-full max-w-[460px]">
        <div class="text-center mb-10">
            <div class="inline-flex p-4 bg-white/40 rounded-full backdrop-blur-md border border-white/60 mb-4 shadow-sm">
                <i class="fas fa-feather-alt text-3xl text-amber-900"></i>
            </div>
            <h1 class="text-4xl font-bold text-amber-950 tracking-tight">Galeri <span class="italic text-amber-800">Nusantara</span></h1>
            <p class="text-slate-500 text-sm mt-2 font-medium italic tracking-wide">Mulai perjalanan kearsipan budaya Anda</p>
        </div>

        <div class="glass-card p-10 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/batik-bg.png');"></div>

            <header class="text-center mb-8">
                <h2 class="text-2xl font-bold text-amber-900 tracking-tight">Daftar Akun Baru</h2>
                <p class="text-[10px] uppercase tracking-[0.3em] text-amber-800/40 font-bold mt-1">Lengkapi Data Pustaka</p>
            </header>

            <?php if (isset($error)): ?>
                <div class="bg-red-500/10 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle opacity-70"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="bg-emerald-500/10 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-70"></i>
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-xs uppercase font-bold text-slate-500 tracking-widest ml-1">Nama Pengguna</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-id-badge text-sm"></i>
                        </span>
                        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required 
                               class="w-full pl-11 pr-5 py-3.5 rounded-2xl input-glass text-slate-700 placeholder:text-slate-400" 
                               placeholder="Min. 3 karakter">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs uppercase font-bold text-slate-500 tracking-widest ml-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fas fa-shield-alt text-sm"></i>
                        </span>
                        <input type="password" name="password" required 
                               class="w-full pl-11 pr-5 py-3.5 rounded-2xl input-glass text-slate-700 placeholder:text-slate-400" 
                               placeholder="Min. 6 karakter">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 btn-register text-white rounded-2xl font-bold shadow-lg mt-4 flex items-center justify-center gap-2 group">
                    <span>Daftarkan Sekarang</span>
                    <i class="fas fa-paper-plane text-[10px] group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </button>
            </form>

            <div class="mt-10 pt-6 border-t border-amber-900/5 text-center">
                <p class="text-sm text-slate-500 mb-4">Sudah terdaftar sebelumnya?</p>
                <a href="index.php" class="inline-flex items-center gap-2 text-amber-800 font-bold hover:text-amber-950 transition-colors bg-white/30 px-6 py-2 rounded-full border border-white/40 shadow-sm">
                    <i class="fas fa-sign-in-alt text-xs"></i>
                    <span>Kembali ke Login</span>
                </a>
            </div>
        </div>

        <footer class="mt-8 text-center">
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-bold italic">© 2026 Abhipraya Digital Pustaka</p>
        </footer>
    </div>

</body>
</html>