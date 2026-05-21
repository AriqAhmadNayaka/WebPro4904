<?php
require_once 'config/database.php'; // KONEKSI DATABASE SINGLETON PDO
require_once 'classes/User.php'; // CLASS USER UNTUK AUTENTIKASI
require_once 'classes/Produk.php'; // CLASS PRODUK UNTUK CRUD

if (!User::isLoggedIn()) { // CEK SESSION LOGIN - KEAMANAN UTAMA
    header('Location: index.php');
    exit;
}

$produkObj = new Produk();
$produk = null;
$error = $success = '';

$id = $_GET['id'] ?? null; // ID UNTUK EDIT MODE
if ($id) { // LOAD DATA PRODUK JIKA MODE EDIT (GET ?id=)
    $produk = $produkObj->getById($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // HANDLER POST REQUEST
    if (isset($_POST['delete'])) { // MODE DELETE (hidden form dari tabel)
        $delete_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        if ($delete_id) {
            $produkObj->delete($delete_id); // HAPUS PRODUK + FILE GAMBAR
        }
        header('Location: dashboard.php');
        exit;
    } else { // MODE CREATE/UPDATE
        $nama = $_POST['nama'] ?? '';
        $harga = $_POST['harga'] ?? 0;
        $deskripsi = $_POST['deskripsi'] ?? '';
        $saveId = $id ?? filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        
        try {
            // VALIDASI FORM INPUT
            if (empty($nama) || $harga <= 0) {
                throw new Exception('Form tidak lengkap!');
            }
            // SIMPAN DATA (auto handle create/update + upload gambar baru)
            $produkObj->save(['nama' => $nama, 'harga' => $harga, 'deskripsi' => $deskripsi], $_FILES['gambar'] ?? null, $saveId);
            $success = 'Data berhasil disimpan!';
            header('Location: dashboard.php');
            exit;
        } catch (Exception $e) { // TANGANI ERROR UPLOAD/VALIDASI
            $error = $e->getMessage();
        }
    }
}
$listProduk = $produkObj->getAll(); // QUERY LIST SEMUA PRODUK UNTUK TABEL
?>
<!DOCTYPE html>
<!-- TAMBOHAN UTAMA: GRID LAYOUT (FORM SIDEBAR + TABEL + STATS + FOOTER) -->
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusantara Heritage Digital Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-primary: linear-gradient(135deg, #fdfbf6 0%, #f1f5f9 100%);
            --bg-glass: rgba(255, 255, 255, 0.45);
            --bg-glass-dark: rgba(30, 41, 59, 0.8);
            --text-primary: #444;
            --text-secondary: #666;
            --text-muted: #94a3b8;
            --border-light: rgba(255, 255, 255, 0.3);
            --border-dark: rgba(71, 85, 105, 0.5);
            --color-primary: #854d0e;
            --color-primary-dark: #60a5fa;
            --color-primary-light: #fef3c7;
            --shadow-light: rgba(0, 0, 0, 0.03);
            --shadow-dark: rgba(0, 0, 0, 0.5);
            --deco-1: #fef3c7;
            --deco-2: #e2e8f0;
        }

        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-glass: rgba(30, 41, 59, 0.8);
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-light: rgba(71, 85, 105, 0.5);
            --border-dark: rgba(71, 85, 105, 0.5);
            --color-primary: #60a5fa;
            --color-primary-dark: #3b82f6;
            --color-primary-light: rgba(96, 165, 250, 0.2);
            --shadow-light: rgba(0, 0, 0, 0.5);
            --shadow-dark: rgba(0, 0, 0, 0.5);
            --deco-1: rgba(96, 165, 250, 0.3);
            --deco-2: rgba(51, 65, 85, 0.5);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-primary);
            min-height: 100vh;
            color: var(--text-primary);
            position: relative;
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }
        h1, h2, h3, h4 { font-family: 'Crimson Pro', serif; }

        .glass-card {
            background: var(--bg-glass);
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            box-shadow: 0 10px 30px var(--shadow-light);
            transition: all 0.3s ease;
        }

        [data-theme="dark"] .glass-card {
            background: var(--bg-glass-dark);
            border-color: var(--border-dark);
        }

        .btn-heritage {
            background: linear-gradient(135deg, rgba(133, 77, 14, 0.9) 0%, rgba(180, 83, 9, 0.9) 100%);
            color: #fff;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }
        [data-theme="dark"] .btn-heritage {
            background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(133, 77, 14, 0.1);
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
            color: var(--text-primary);
        }
        [data-theme="dark"] .input-glass {
            background: rgba(51, 65, 85, 0.8);
        }

        .bg-deco {
            position: fixed; border-radius: 50%; filter: blur(80px); z-index: -1; opacity: 0.5;
            transition: background 0.3s ease;
        }
        .deco-1 { width: 300px; height: 300px; background: var(--deco-1); top: -100px; right: -100px; }
        .deco-2 { width: 400px; height: 400px; background: var(--deco-2); bottom: -150px; left: -150px; }

        @keyframes fadeOut { 0% { opacity: 1; transform: scale(1); } 100% { opacity: 0; transform: scale(1.05); } }
        @keyframes scaleIn { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        @keyframes scaleOut { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(0.9); opacity: 0; } }

        /* Social Icon Hover Effect */
        .social-icon {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .social-icon:hover {
            transform: translateY(-5px) scale(1.1);
            background: var(--color-primary);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="p-4 md:p-8">

    <div class="bg-deco deco-1"></div>
    <div class="bg-deco deco-2"></div>

    <div class="max-w-6xl mx-auto">
        <header class="flex flex-col md:flex-row justify-between items-center mb-10 glass-card p-6 border border-white/50">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="p-3 bg-amber-900/10 rounded-2xl text-amber-900">
                    <i class="fas fa-landmark text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-amber-950 tracking-tight">Galeri <span class="italic text-amber-800">Nusantara</span></h1>
                    <p class="text-slate-600 text-sm font-medium tracking-wide">Pusat Digital Warisan Budaya</p>
                </div>
            </div>
            <div class="flex items-center gap-4 mt-6 md:mt-0 text-slate-700 bg-white/20 px-5 py-2.5 rounded-full border border-white/30 text-sm font-semibold">
                <span class="italic">Rahayu, <?= User::getUsername() ?> ✨</span>
                <span class="text-slate-300">|</span>
                <button onclick="logoutEffect()" class="text-amber-900/60 hover:text-red-700 transition-colors p-1 rounded-full hover:bg-red-500/20">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
                <button id="theme-toggle" class="ml-2 p-2 rounded-full bg-white/20 hover:bg-white/40 border border-white/30 transition-all text-lg">
                    <i class="fas fa-sun text-yellow-400" id="theme-icon"></i>
                </button>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start"> <!-- LAYOUT RESPONSIVE: MOBILE STACK, DESKTOP 4+8 COL -->
            <div class="lg:col-span-4 lg:sticky lg:top-8"> <!-- SIDEBAR FORM CRUD (STICKY DI DESKTOP) -->
                <div class="glass-card p-8 shadow-inner border border-white/60">
<h3 class="text-2xl font-bold mb-6 text-amber-950 border-b border-amber-900/10 pb-2">
                        <!-- DYNAMIC TITLE: EDIT MODE ATAU CREATE BARU -->
                        <?= $produk ? 'Sunting Artefak' : 'Daftarkan Barang' ?>
                    </h3>
                    <form method="POST" enctype="multipart/form-data" class="space-y-5">
                        <input type="hidden" name="id" value="<?= $produk ? $produk['id'] : '' ?>">
                        <div>
                            <label class="text-xs uppercase font-bold text-slate-500 tracking-widest block mb-2 ml-1">Nama Artefak</label>
                            <input type="text" name="nama" value="<?= $produk ? htmlspecialchars($produk['nama']) : '' ?>" required
                                   class="w-full px-5 py-3 rounded-xl input-glass" placeholder="E.g. Keris Pusaka">
                        </div>
                        <div>
                            <label class="text-xs uppercase font-bold text-slate-500 tracking-widest block mb-2 ml-1">Nilai Taksiran (Rp)</label>
                            <input type="number" name="harga" value="<?= $produk ? htmlspecialchars((string) $produk['harga']) : '' ?>" required
                                   class="w-full px-5 py-3 rounded-xl input-glass" placeholder="0">
                        </div>
                        <div>
                            <label class="text-xs uppercase font-bold text-slate-500 tracking-widest block mb-2 ml-1">Keterangan</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-5 py-3 rounded-xl input-glass" placeholder="Riwayat barang..."><?= $produk ? htmlspecialchars($produk['deskripsi']) : '' ?></textarea>
                        </div>
                        <div>
                            <label class="text-xs uppercase font-bold text-slate-500 tracking-widest block mb-2 ml-1">Citra Visual</label>
                            <div class="rounded-xl relative h-32 group overflow-hidden flex items-center justify-center bg-white/20 border border-amber-900/10">
                                <?php if ($produk && $produk['gambar']): ?>
                                    <img src="uploads/<?= $produk['gambar'] ?>" id="image-preview" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img src="" id="image-preview" class="w-full h-full object-cover hidden">
                                    <div id="placeholder-content" class="text-center">
                                        <i class="fas fa-camera text-slate-400 text-2xl mb-1"></i>
                                        <p class="text-[10px] text-slate-500 italic">Pilih Foto</p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="gambar" id="gambar-input" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-4 btn-heritage rounded-xl font-bold">
                            <i class="fas fa-feather mr-2"></i> Simpan ke Galeri
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-8 p-2">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="glass-card p-5 border-white/40 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-900"><i class="fas fa-boxes-stacked"></i></div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Koleksi</p>
                            <h3 class="text-2xl font-bold text-amber-950"><?= count($listProduk) ?></h3>
                        </div>
                    </div>
                    <?php $totalNilai = array_sum(array_column($listProduk, 'harga')); ?>
                    <div class="glass-card p-5 border-white/40 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-700"><i class="fas fa-vault"></i></div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Asset</p>
                            <h3 class="text-lg font-bold text-amber-950">Rp <?= number_format($totalNilai/1000000, 1) ?>jt</h3>
                        </div>
                    </div>
                    <div class="glass-card p-5 border-white/40 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-700"><i class="fas fa-shield-halved"></i></div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Status</p>
                            <h3 class="text-lg font-bold text-blue-900 italic">Terverifikasi</h3>
                        </div>
                    </div>
                </div>

                <div class="glass-card overflow-hidden border-white/50 shadow-xl"> <!-- MAIN CONTENT TABLE -->
                    <table class="w-full text-left border-collapse"> <!-- TABEL PRODUK DENGAN EDIT/DELETE ACTION -->
                        <thead>
                            <tr class="bg-amber-900/5 text-amber-950 text-xs uppercase tracking-widest">
                                <th class="px-6 py-4 font-bold">Artefak</th>
                                <th class="px-6 py-4 font-bold">Nilai</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-900/5">
                            <?php foreach ($listProduk as $p): ?>
                            <tr class="group hover:bg-white/40 transition-colors" id="item-row-<?= $p['id'] ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-200 border border-white">
                                            <?php if ($p['gambar']): ?>
                                                <img src="uploads/<?= $p['gambar'] ?>" class="w-full h-full object-cover">
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-amber-950 text-sm"><?= htmlspecialchars($p['nama']) ?></div>
                                            <div class="text-[10px] text-slate-500 italic"><?= date('d/m/Y', strtotime($p['created_at'])) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-amber-900">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="?id=<?= $p['id'] ?>" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center hover:bg-amber-200"><i class="fas fa-pen-nib text-xs"></i></a>
                                        <form method="POST" class="delete-form inline">
                                            <input type="hidden" name="delete" value="1">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <button type="button" onclick="shatterDelete(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nama']) ?>')"
                                                    class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center hover:bg-red-200">
                                                <i class="fas fa-broom text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="mt-20 mb-10">
            <div class="glass-card p-10 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 opacity-5 pointer-events-none">
                    <i class="fas fa-dharmachakra text-[200px] text-amber-900"></i>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-900/10 flex items-center justify-center text-amber-900">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-amber-950 italic">Galeri Nusantara</h4>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Mendokumentasikan warisan budaya Indonesia dalam format digital. <span class="italic font-semibold text-amber-800">Lestari Budayaku.</span>
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="https://www.instagram.com/hzkiiiaa_?igsh=MTd3dnU5OWJhaW5qMQ%3D%3D&utm_source=qr" target="_blank" class="social-icon w-10 h-10 rounded-xl bg-white/40 flex items-center justify-center text-amber-900 border border-white/60">
                                <i class="fab fa-instagram text-lg"></i>
                            </a>
                            <a href="https://www.tiktok.com/@kiaakks?_r=1&_t=ZS-95NWkZkcbqP" target="_blank" class="social-icon w-10 h-10 rounded-xl bg-white/40 flex items-center justify-center text-amber-900 border border-white/60">
                                <i class="fab fa-tiktok text-lg"></i>
                            </a>
                            <a href="https://github.com/hizkiak" target="_blank" class="social-icon w-10 h-10 rounded-xl bg-white/40 flex items-center justify-center text-amber-900 border border-white/60">
                                <i class="fab fa-github text-lg"></i>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h5 class="text-xs uppercase tracking-widest font-bold text-amber-900/50 mb-4">Navigasi</h5>
                            <ul class="space-y-2 text-sm text-slate-600">
                                <li><a href="#" class="hover:text-amber-800 transition-colors">Beranda</a></li>
                                <li><a href="#" class="hover:text-amber-800 transition-colors">Statistik</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-xs uppercase tracking-widest font-bold text-amber-900/50 mb-4">Bantuan</h5>
                            <ul class="space-y-2 text-sm text-slate-600">
                                <li><a href="#" class="hover:text-amber-800 transition-colors">Panduan</a></li>
                                <li><a href="#" class="hover:text-amber-800 transition-colors">Kontak</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="glass-card bg-amber-900/5 p-6 border-white/20">
                        <h5 class="text-xs uppercase tracking-widest font-bold text-amber-900/50 mb-3">Warta Budaya</h5>
                        <div class="relative">
                            <input type="email" placeholder="Email Anda..." class="w-full pl-4 pr-10 py-2.5 rounded-xl input-glass text-xs outline-none">
                            <button class="absolute right-2 top-1.5 text-amber-900"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-6 border-t border-amber-900/10 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] text-slate-500 font-semibold tracking-wider uppercase">
                    <p>&copy; <?= date('Y') ?> Nusantara Heritage. Rahayu.</p>
                    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="hover:text-amber-900 flex items-center gap-2 transition-colors">
                        Kembali Ke Atas <i class="fas fa-arrow-up"></i>
                    </button>
                </div>
            </div>
        </footer>
    </div>

    <script>
        /* JAVASCRIPT FEATURES:
         * 1. Dark/Light theme toggle dengan localStorage
         * 2. Real-time image preview untuk upload
         * 3. Logout konfirmasi modal glassmorphism
         * 4. Shatter animation delete effect unik
         * 5. Smooth scroll footer
         */
        // 1. INIT THEME SYSTEM - Load dari localStorage
        function initTheme() {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = saved;
            document.getElementById('theme-icon').className = saved === 'dark' ? 'fas fa-moon text-yellow-400' : 'fas fa-sun text-yellow-400';
        }
        // 2. TOGGLE THEME - Switch CSS variables real-time
        function toggleTheme() {
            const current = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = current;
            localStorage.setItem('theme', current);
            document.getElementById('theme-icon').className = current === 'dark' ? 'fas fa-moon text-yellow-400' : 'fas fa-sun text-yellow-400';
        }
        const themeToggleButton = document.getElementById('theme-toggle');
        if (themeToggleButton) {
            themeToggleButton.addEventListener('click', toggleTheme);
        }
        initTheme();

        // === PREVIEW IMAGE ===
        const gambarInput = document.getElementById('gambar-input');
        if (gambarInput) {
            gambarInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.getElementById('image-preview');
                        if (!img) {
                            return;
                        }
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        const placeholder = document.getElementById('placeholder-content');
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // === PREMIUM LOGOUT ===
        function logoutEffect() {
            const modalHTML = `
                <div id="logoutModal" style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:10001; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(10px);">
                    <div class="glass-card p-10 max-w-sm mx-4 text-center" style="animation: scaleIn 0.3s forwards;">
                        <i class="fas fa-door-open text-6xl text-red-400 mb-4 opacity-80"></i>
                        <h3 class="text-2xl font-bold text-amber-950 mb-2">Keluar Pustaka?</h3>
                        <p class="text-slate-600 mb-6 italic">Rahayu! Yakin ingin meninggalkan galeri?</p>
                        <div class="flex gap-4 justify-center">
                            <button onclick="document.getElementById('logoutModal').remove()" class="px-6 py-2 rounded-xl font-bold bg-amber-100 text-amber-900 border border-white/50">Batal</button>
                            <button onclick="confirmLogout()" class="px-6 py-2 rounded-xl font-bold bg-red-500 text-white shadow-lg">Ya, Keluar</button>
                        </div>
                    </div>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
        function confirmLogout() {
            document.querySelector('header').style.animation = 'fadeOut 0.6s forwards';
            setTimeout(() => window.location.href = 'logout.php', 500);
        }

        // === SHATTER EFFECT ===
        function shatterDelete(id, name) {
            if (confirm(`Lepaskan artefak "${name}"?`)) {
                const row = document.getElementById(`item-row-${id}`);
                const rect = row.getBoundingClientRect();
                for (let i = 0; i < 15; i++) {
                    const piece = document.createElement('div');
                    piece.style.cssText = `position:fixed; left:${rect.left + Math.random()*rect.width}px; top:${rect.top + Math.random()*rect.height}px; width:10px; height:10px; background:#854d0e; z-index:10000; pointer-events:none; border-radius:2px;`;
                    document.body.appendChild(piece);
                    piece.animate([{transform:'scale(1)', opacity:1}, {transform:`translate(${(Math.random()-0.5)*500}px, ${(Math.random()-0.5)*500}px) rotate(${Math.random()*720}deg) scale(0)`, opacity:0}], {duration:800}).onfinish = () => piece.remove();
                }
                row.animate([{opacity:1, transform:'scale(1)'}, {opacity:0, transform:'translateX(-100px) scale(0.9)'}], {duration:600}).onfinish = () => row.querySelector('.delete-form').submit();
            }
        }
    </script>
</body>
</html>
