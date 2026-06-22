<?php
session_start();

// 1. PROTEKSI HALAMAN
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// 2. LOGIKA INISIALISASI DATA (READ)
// Ini dipindah ke atas agar selalu siap menerima data baru
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['title' => 'Nasi Ngawur', 'rating' => '4.5', 'desc' => 'Kombinasi rasa unik.', 'icon' => 'fa-utensils'],
        ['title' => 'Mie Hijau Sehat', 'rating' => '4.2', 'desc' => 'Mie organik sawi.', 'icon' => 'fa-bowl-food']
    ];
}

// 3. LOGIKA "CREATE" (TAMBAH DATA)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $new_item = [
        'title' => htmlspecialchars($_POST['title']),
        'rating' => htmlspecialchars($_POST['rating']),
        'desc' => htmlspecialchars($_POST['desc']),
        'icon' => 'fa-utensils' // Ikon default
    ];
    
    // Menambahkan data baru ke urutan paling atas
    array_unshift($_SESSION['products'], $new_item);
    
    // Mencegah form tersubmit berulang kali saat halaman di-refresh
    header("Location: beranda.php");
    exit();
}

$user_display = $_SESSION['current_user'] ?? 'Pengguna';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTaste | Create & Read</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --hijau-primer: #4CAF50;
            --hijau-gelap: #1B5E20;
            --putih: #FFFFFF;
            --abu-latar: #F4F9F4;
        }

        body { font-family: 'Poppins', sans-serif; background: var(--abu-latar); margin: 0; }
        
        /* Navbar */
        .navbar { display: flex; justify-content: space-between; padding: 15px 5%; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logo { color: var(--hijau-primer); font-weight: bold; font-size: 24px; display: flex; align-items: center; gap: 8px; }
        .nav-links { list-style: none; display: flex; gap: 20px; margin: 0; }
        .nav-links a { text-decoration: none; color: #333; font-weight: 600; }
        .nav-links a:hover { color: var(--hijau-primer); }

        .main-container { padding: 40px 5%; }

        /* Form Styling (CREATE) */
        .form-card {
            background: white; padding: 25px; border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 40px;
        }
        .form-card h2 { color: var(--hijau-gelap); margin-top: 0; }
        .input-box { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-add { background: var(--hijau-primer); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-add:hover { background: var(--hijau-gelap); }

        /* Grid Styling (READ) */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .product-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; }
        .icon-box { font-size: 40px; color: var(--hijau-primer); margin-bottom: 15px; }
        .rating { color: #FFC107; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
    <ul class="nav-links">
        <li><a href="beranda.php">Beranda</a></li>
        <li><a href="logout.php" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>

<div class="main-container">
    <h1 style="color: var(--hijau-gelap);">Selamat Datang, <?php echo htmlspecialchars($user_display); ?>!</h1>

    <section class="form-card">
        <h2><i class="fas fa-plus-circle"></i> Tambah Menu Baru</h2>
        <form action="beranda.php" method="POST">
            <input type="text" name="title" class="input-box" placeholder="Nama Makanan" required>
            <input type="number" step="0.1" max="5" name="rating" class="input-box" placeholder="Rating (0-5)" required>
            <textarea name="desc" class="input-box" placeholder="Deskripsi Singkat" required></textarea>
            <button type="submit" name="add_product" class="btn-add">Simpan Menu</button>
        </form>
    </section>

    <section>
        <h2 style="color: var(--hijau-gelap);"><i class="fas fa-list"></i> Daftar Menu EcoTaste</h2>
        <div class="product-grid">
            <?php foreach ($_SESSION['products'] as $product): ?>
                <div class="product-card">
                    <div class="icon-box"><i class="fas <?php echo htmlspecialchars($product['icon']); ?>"></i></div>
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p><?php echo htmlspecialchars($product['desc']); ?></p>
                    <div class="rating"><i class="fas fa-star"></i> <?php echo htmlspecialchars($product['rating']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

</body>
</html>