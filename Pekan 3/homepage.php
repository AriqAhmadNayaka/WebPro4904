<?php
session_start();


if (!isset($_SESSION['login'])) { // Cek apakah user sudah login, jika belum, redirect ke login.php
    header("Location: login.php"); // Pindah ke halaman login
    exit;
}


if (!isset($_SESSION['menu_list'])) { // Cek apakah data menu sudah ada di session, jika belum, buat data awal
    $_SESSION['menu_list'] = [
        ['nama' => 'Nasi Ngawur', 'desk' => 'Kombinasi rasa unik dan lezat.', 'rating' => 4.5, 'icon' => 'fas fa-utensils'],
        ['nama' => 'Mie Hijau Sehat', 'desk' => 'Sehat, alami, dan bergizi.', 'rating' => 4.2, 'icon' => 'fas fa-bowl-food']
    ];
}


if (isset($_POST['tambah_menu'])) {// Cek apakah form tambah menu disubmit
    
    $nama = htmlspecialchars($_POST['nama']);// Bersihkan input nama
    $desk = htmlspecialchars($_POST['desk']);// Bersihkan input deskripsi
    $rating = (float)$_POST['rating'];// Konversi rating ke float
    
    $_SESSION['menu_list'][] = [// Tambahkan menu baru ke session
        'nama' => $nama,
        'desk' => $desk,
        'rating' => $rating,
        'icon' => 'fas fa-utensils' // Default icon
    ];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Makanan | Beranda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       
        :root {
            --hijau-primer: #4CAF50;
            --hijau-sekunder: #8BC34A;
            --hijau-latar: #F4F9F4;
            --hijau-gelap: #1B5E20;
            --putih: #FFFFFF;
            --abu-teks: #555;
            --abu-kartu: #F8F9FA;
            --kuning-emas: #FFC107;

            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.05);
            --shadow-card: 0 15px 35px rgba(0, 0, 0, 0.08);
            --shadow-float: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: var(--hijau-latar);
        
            background-image: radial-gradient(#E8F5E9 2px, transparent 2px);
            background-size: 30px 30px;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .main-header {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: var(--putih);
           
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            color: var(--hijau-primer);
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: -0.5px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 35px;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--abu-teks);
            font-weight: 600;
            font-size: 15px;
            position: relative;
            transition: color 0.3s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--hijau-primer);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--hijau-primer);
            transition: width 0.3s ease-out;
            border-radius: 2px;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 25px;
        }

       
        .main-content {
            padding: 30px 5%;
            padding-bottom: 60px;
        }

        .promo-section {
            background: linear-gradient(135deg, var(--hijau-primer), var(--hijau-gelap));
            color: var(--putih);
            height: 400px;
            border-radius: 24px;
            margin-bottom: 90px;
            box-shadow: var(--shadow-card);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            overflow: visible;
        }

     
        .promo-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            border-radius: 24px;
            pointer-events: none;
        }

      
        .promo-bg-icon {
            position: absolute;
            font-size: 15rem;
            color: rgba(255, 255, 255, 0.08);
            right: 5%;
            bottom: 10%;
            transform: rotate(-15deg);
            pointer-events: none;
        }

        .promo-section h1 {
            font-size: 3.5em;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
        }

        .promo-section p {
            font-size: 1.2em;
            opacity: 0.95;
            max-width: 600px;
            line-height: 1.6;
            position: relative;
            z-index: 2;
            margin-bottom: 40px;
           
        }

    
        .search-container-wrapper {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 600px;
            z-index: 10;
        }

        .search-box-promo {
            background-color: var(--putih);
            padding: 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-float);
            text-align: left;
        }

        .search-box-promo p {
            font-size: 14px;
            color: var(--hijau-gelap);
            font-weight: 700;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-bar-promo {
            display: flex;
            gap: 15px;
        }

        .search-input-field {
            flex-grow: 1;
            padding: 14px 20px;
            border: 2px solid #E0E0E0;
            border-radius: 12px;
            outline: none;
            font-size: 16px;
            transition: all 0.3s;
            background-color: #FAFAFA;
        }

        .search-input-field:focus {
            border-color: var(--hijau-primer);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
        }

        .search-button {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(to right, #66BB6A, var(--hijau-primer));
            color: var(--putih);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

     
        .section-header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .filter-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-container label {
            font-weight: 600;
            color: var(--hijau-gelap);
            font-size: 14px;
        }

        .filter-container select {
            padding: 8px 12px;
            border: 2px solid #E0E0E0;
            border-radius: 8px;
            background-color: var(--putih);
            font-size: 14px;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .filter-container select:focus {
            border-color: var(--hijau-primer);
            outline: none;
        }

        .product-list-section h2 {
            color: var(--hijau-gelap);
            font-size: 1.8em;
            font-weight: 800;
            position: relative;
            margin: 0;
        }

        .product-list-section h2::after {
            content: '';
            display: block;
            width: 40%;
            height: 4px;
            background-color: var(--hijau-primer);
            border-radius: 2px;
            margin-top: 5px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background-color: var(--putih);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(0, 0, 0, 0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
            cursor: pointer;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-card);
        }

        .card-image-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .card-image-placeholder i {
            font-size: 4em;
            color: #fff;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .product-card:hover .card-image-placeholder i {
            transform: scale(1.2) rotate(5deg);
        }

        .card-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-content h3 {
            color: var(--hijau-gelap);
            font-weight: 700;
            font-size: 1.25em;
            margin: 0 0 10px;
        }

        .card-content p {
            font-size: 14px;
            color: var(--abu-teks);
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.6;
        }

       
        .card-rating-badge {
            margin-top: auto;
            padding-top: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9em;
            font-weight: 600;
            color: var(--kuning-emas);
        }

       
        .main-footer {
            background-color: var(--hijau-gelap);
            color: var(--putih);
            text-align: center;
            padding: 50px 5%;
            margin-top: 80px;
            font-size: 14px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 25px 0 0;
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s;
            font-weight: 500;
        }

        .footer-links a:hover {
            color: var(--putih);
            text-decoration: underline;
        }

      
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-y: auto;
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background-color: var(--putih);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 550px;
            position: relative;
            animation: fadeIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-image-placeholder {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--hijau-gelap);
        }

        .modal-image-placeholder i {
            font-size: 5em;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
        }

        .modal-body {
            padding: 35px;
            text-align: center;
        }

        .modal-body h2 {
            font-size: 28px;
            color: var(--hijau-gelap);
            margin-bottom: 8px;
            font-weight: 800;
        }

        .modal-rating {
            color: var(--kuning-emas);
            font-size: 1.2em;
            margin-bottom: 20px;
            display: block;
        }

        .modal-description {
            color: var(--abu-teks);
            margin-bottom: 30px;
            line-height: 1.7;
            font-size: 15px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .action-btn {
            flex: 1;
            padding: 16px;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(to right, #66BB6A, var(--hijau-primer));
            color: white;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-secondary {
            background-color: var(--putih);
            color: var(--hijau-primer);
            border: 2px solid var(--hijau-primer);
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 2001;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .close-btn:hover {
            color: var(--hijau-primer);
            transform: rotate(90deg);
            background: #fff;
        }

   
        @media (max-width: 768px) {
            .navbar {
                justify-content: center;
            }

            .nav-links {
                display: none;
            }

            .promo-section h1 {
                font-size: 2.8em;
            }

            .search-bar-promo {
                flex-direction: column;
            }

            .search-button {
                width: 100%;
            }

            .product-grid {
                grid-template-columns: 1fr;
                /* 1 kolom di mobile */
            }

            .promo-bg-icon {
                font-size: 10rem;
                opacity: 0.05;
            }
        }
    </style>
</head>

<body>

    <header class="main-header">
        <nav class="navbar">
            <div>
                <div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
            </div>
            <ul class="nav-links">
                <li><a href="beranda.html" class="active">Beranda</a></li>
                <li><a href="navigasi.html">Navigasi</a></li>
                <li><a href="pasport.html">Passport</a></li>
                <li><a href="redeem.html">Redeem</a></li>
                <li><a href="login.html">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">

        <section class="promo-section" aria-labelledby="promo-heading">
            
            <i class="fas fa-utensils promo-bg-icon"></i>

            <h1 id="promo-heading">Promo Hari Ini! 🍽️</h1>
            <p>Nikmati diskon fantastis untuk semua menu spesial di lokasi terdekat Anda.</p>

            <div class="search-container-wrapper">
                <div class="search-box-promo">
                    <p><i class="fas fa-map-marker-alt"></i> Cari Kuliner</p>
                    <div class="search-bar-promo">
                        <input type="text" class="search-input-field" placeholder="Mau makan apa hari ini?" value="">
                        <button class="search-button" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="product-list-section" aria-labelledby="product-heading">
            <div class="section-header-container">
                <h2 id="product-heading">Rekomendasi Pilihan</h2>
                <div class="filter-container">
                    <label for="rating-filter">Filter Rating:</label>
                    <select id="rating-filter">
                        <option value="all">Semua</option>
                        <option value="4.0">4.0+</option>
                        <option value="4.5">4.5+</option>
                        <option value="5.0">5.0+</option>
                    </select>
                </div>
            </div>

            <div class="product-grid">

                
                <article class="product-card" data-title="Nasi Ngawur Spesial" data-rating="4.5"
                    data-description="Nasi campur khas dengan berbagai macam lauk pauk yang dipilih secara acak namun menghasilkan rasa yang luar biasa harmonis.">
                    <div class="card-image-placeholder">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="card-content">
                        <h3>Nasi Ngawur</h3>
                        <p>Kombinasi rasa yang unik dan lezat.</p>
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> 4.5
                        </div>
                    </div>
                </article>

                
                <article class="product-card" data-title="Mie Hijau Sehat" data-rating="4.2"
                    data-description="Mi berbahan dasar sawi dan sayuran organik, disajikan dengan topping ayam jamur yang lezat.">
                    <div class="card-image-placeholder">
                        <i class="fas fa-bowl-food"></i>
                    </div>
                    <div class="card-content">
                        <h3>Mie Hijau Sehat</h3>
                        <p>Sehat, alami, dan bergizi.</p>
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> 4.2
                        </div>
                    </div>
                </article>

               
                <article class="product-card" data-title="Salad Buah Organik" data-rating="4.8"
                    data-description="Salad buah segar dengan dressing yogurt madu, menggunakan buah-buahan lokal yang baru dipetik.">
                    <div class="card-image-placeholder">
                        <i class="fas fa-apple-whole"></i>
                    </div>
                    <div class="card-content">
                        <h3>Salad Buah Organik</h3>
                        <p>Segar langsung dari kebun.</p>
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> 4.8
                        </div>
                    </div>
                </article>

                
                <article class="product-card" data-title="Sate Jamur Tiram" data-rating="4.3"
                    data-description="Sate vegetarian yang terbuat dari jamur tiram pilihan, dibakar dengan bumbu kacang spesial.">
                    <div class="card-image-placeholder">
                        <i class="fas fa-fire-burner"></i>
                    </div>
                    <div class="card-content">
                        <h3>Sate Jamur</h3>
                        <p>Alternatif sate yang lezat.</p>
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> 4.3
                        </div>
                    </div>
                </article>

                
                <article class="product-card" data-title="Es Kelapa Muda" data-rating="4.7"
                    data-description="Minuman penyegar dahaga klasik. Air kelapa murni dengan daging kelapa muda yang lembut.">
                    <div class="card-image-placeholder">
                        <i class="fas fa-glass-water"></i>
                    </div>
                    <div class="card-content">
                        <h3>Es Kelapa Muda</h3>
                        <p>Kesegaran alami tropis.</p>
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> 4.7
                        </div>
                    </div>
                </article>

                
                <article class="product-card" data-title="Tumis Kangkung" data-rating="4.1"
                    data-description="Sayur kangkung segar ditumis dengan bawang putih, cabai, dan saus tiram.">
                    <div class="card-image-placeholder">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="card-content">
                        <h3>Tumis Kangkung</h3>
                        <p>Masakan rumahan favorit.</p>
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> 4.1
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </main>

    <footer class="main-footer">
        <p>&copy;2025 EcoTaste. Semua Hak Cipta Dilindungi.</p>
        <ul class="footer-links">
            <li><a href="#">Kebijakan Privasi</a></li>
            <li><a href="#">Syarat Penggunaan</a></li>
            <li><a href="#">Hubungi Kami</a></li>
        </ul>
    </footer>

    
    <div id="productModal" class="modal-overlay">
        <div class="modal-content">
          
            <button class="close-btn" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>

            
            <div class="modal-image-placeholder">
                <i class="fas fa-utensils"></i>
            </div>

           
            <div class="modal-body">
                <h2 id="modalTitle">Judul Produk</h2>
               
                <span id="modalRating" class="modal-rating"></span>

                <p id="modalDescription" class="modal-description">
                    Deskripsi produk akan muncul di sini.
                </p>

                <div class="action-buttons">
                    <a href="navigasi.html" class="action-btn btn-primary">Lihat Lokasi</a>
                    <a href="rating.html" class="action-btn btn-secondary">Beri Rating</a>
                </div>
            </div>
        </div>
    </div>

   
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productCards = document.querySelectorAll('.product-card');
            const modal = document.getElementById('productModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalRating = document.getElementById('modalRating');
            const modalDescription = document.getElementById('modalDescription');
            const modalImageIcon = document.querySelector('.modal-image-placeholder i');

            
            function generateRatingStars(rating) {
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 !== 0;
                let starsHtml = '';

                for (let i = 0; i < fullStars; i++) {
                    starsHtml += '<i class="fas fa-star"></i>';
                }
                if (hasHalfStar) {
                    starsHtml += '<i class="fas fa-star-half-alt"></i>';
                }
                const emptyStars = 5 - Math.ceil(rating);
                for (let i = 0; i < emptyStars; i++) {
                    starsHtml += '<i class="far fa-star"></i>';
                }

                return starsHtml;
            }

            productCards.forEach(card => {
                card.addEventListener('click', () => {
                    const title = card.getAttribute('data-title');
                    const rating = parseFloat(card.getAttribute('data-rating'));
                    const description = card.getAttribute('data-description');

                   
                    const cardIconClass = card.querySelector('.card-image-placeholder i').className;

                    modalTitle.textContent = title;
                    modalDescription.textContent = description;
                    modalRating.innerHTML = generateRatingStars(rating);
                    modalImageIcon.className = cardIconClass; 

                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                });
            });

            window.closeModal = function () {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            modal.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal-overlay')) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === "Escape" && modal.style.display === "flex") {
                    closeModal();
                }
            });

            const ratingFilter = document.getElementById('rating-filter');
            ratingFilter.addEventListener('change', () => {
                const selectedValue = ratingFilter.value;
                const cards = document.querySelectorAll('.product-card');

                cards.forEach(card => {
                    const rating = parseFloat(card.getAttribute('data-rating'));
                    let showCard = true;

                    if (selectedValue === '4.0' && rating < 4.0) {
                        showCard = false;
                    } else if (selectedValue === '4.5' && rating < 4.5) {
                        showCard = false;
                    } else if (selectedValue === '5.0' && rating < 5.0) {
                        showCard = false;
                    }

                    card.style.display = showCard ? 'flex' : 'none';
                });
            });
        });
    </script>

</body>

</html>