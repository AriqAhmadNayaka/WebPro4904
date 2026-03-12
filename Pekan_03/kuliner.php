<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko dengan Keranjang - Desktop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ✅ SEMUA CSS AWAL ANDA TETAP SAMA - TIDAK BERUBAH */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.1);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #ebfbfa;
            background-image: radial-gradient(circle at 10% 100%, rgba(5, 12, 87, 0.607), rgba(70, 79, 184, 0.281), transparent 40%),
                             radial-gradient(circle at 100% 10%, rgba(60, 252, 242, 0.42), rgba(207, 244, 248, 0.756), transparent 60%);
            background-repeat: no-repeat;
            background-attachment: fixed;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }

        .header-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 20px;
            box-shadow: 0 8px 32px rgba(141, 109, 253, 0.4);
            position: sticky;
            top: 0;
            z-index: 1000;
            margin: 0 auto 20px;
            border-radius: 20px;
            max-width: 1200px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .back-to-main {
            background: linear-gradient(90deg, #8D6DFD, #3a71ff);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-to-main:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 196, 180, 0.4);
        }

        /* KERANJANG - SELALU VISIBLE */
        #cartContainer {
            position: fixed !important;
            top: 25px !important;
            right: 25px !important;
            z-index: 99999 !important;
        }

        .cart-button {
            width: 65px !important;
            height: 65px !important;
            background: linear-gradient(135deg, #8D6DFD, #3a71ff, #00C4B4) !important;
            border: none !important;
            border-radius: 50% !important;
            color: white !important;
            font-size: 26px !important;
            cursor: pointer !important;
            box-shadow: 0 10px 30px rgba(141, 109, 253, 0.5) !important;
            transition: all 0.4s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: relative !important;
            border: 3px solid rgba(255, 255, 255, 0.3) !important;
        }

        .cart-button:hover {
            transform: scale(1.15) !important;
            box-shadow: 0 20px 50px rgba(141, 109, 253, 0.7) !important;
        }

        .cart-badge {
            position: absolute !important;
            top: -8px !important;
            right: -8px !important;
            background: #FF4444 !important;
            color: white !important;
            border-radius: 50% !important;
            min-width: 28px !important;
            height: 28px !important;
            font-size: 13px !important;
            font-weight: bold !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(255, 68, 68, 0.4) !important;
            border: 2px solid white !important;
        }

        .cart-dropdown {
            position: absolute;
            top: 80px;
            right: 0;
            width: 380px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            max-height: 80vh;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .cart-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .cart-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            background: rgba(248, 249, 250, 0.8);
        }

        .cart-header h3 {
            color: #333;
            margin-bottom: 5px;
        }

        .cart-total-items {
            color: #666;
            font-size: 14px;
        }

        .cart-items {
            max-height: 300px;
            overflow-y: auto;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* ✅ GAMBAR CART - 100% JELAS */
        .cart-item img {
            width: 70px !important;
            height: 70px !important;
            border-radius: 12px !important;
            object-fit: cover !important;
            object-position: center !important;
            border: 3px solid rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .cart-item-details {
            flex: 1;
            margin-left: 12px;
        }

        .cart-item-details h4 {
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
            line-height: 1.3;
        }

        .cart-item-price {
            font-size: 14px;
            color: #8D6DFD;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-item {
            background: #ff4444;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }

        .cart-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            background: rgba(248, 249, 250, 0.8);
        }

        .cart-total {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .cart-total strong {
            color: #333;
            font-size: 20px;
        }

        .btn-checkout {
            width: 100%;
            background: linear-gradient(90deg, #8D6DFD, #3a71ff);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(141, 109, 253, 0.4);
        }

        .empty-cart {
            text-align: center;
            color: #666;
            padding: 40px 20px;
            font-size: 16px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 120px auto 40px;
        }

        .product-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(141, 109, 253, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .product-price {
            font-size: 20px;
            background: linear-gradient(90deg, #8D6DFD, #3a71ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .add-to-cart-btn {
            width: 100%;
            background: linear-gradient(90deg, #8D6DFD, #3a71ff);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .side-floating-nav {
            position: fixed;
            top: 100px;
            left: 25px;
            z-index: 99;
        }

        .side-rail {
            width: 50px;
            height: 460px;
            padding: 9px;
            border-radius: 40px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .side-top { margin-bottom: 20px; gap: 5px; }
        .side-bottom { margin-top: 20px; gap: 5px; }
        .icon-pill {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .icon-pill:hover { background: rgba(141, 109, 253, 0.2); transform: translateY(-2px); }
        .icon-image { width: 20px; height: 20px; object-fit: contain; }
    </style>
</head>

<body>
    <header class="header-nav">
        <div class="nav-container">
            <a href="halaman-kuliner.html" class="back-to-main">
                Kembali ke Halaman Utama
            </a>
        </div>
    </header>

    <div class="cart-container" id="cartContainer">
        <button class="cart-button" id="cartButton">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-badge" id="cartBadge">0</span>
        </button>
        <div class="cart-dropdown" id="cartDropdown">
            <div class="cart-header">
                <h3>Keranjang Belanja</h3>
                <span class="cart-total-items" id="cartTotalItems">0 item</span>
            </div>
            <div class="cart-items" id="cartItems"></div>
            <div class="cart-footer">
                <div class="cart-total">
                    <strong>Total: Rp <span id="cartTotalPrice">0</span></strong>
                </div>
                <button class="btn-checkout" id="checkoutBtn" onclick="checkout()">Lanjut Checkout</button>
            </div>
        </div>
    </div>

    <div class="product-grid">
        <div class="product-card">
            <img src="mieayam-kuliner.jpg" alt="Mie Ayam Aja" class="product-image">
            <div class="product-name">Mie Ayam Aja</div>
            <div class="product-price">Rp 15.000</div>
            <button class="add-to-cart-btn" data-product='{"id":"1","name":"Mie Ayam Aja","price":15000,"image":"mieayam-kuliner.jpg"}'>
                <i class="fas fa-plus"></i> Tambah ke Keranjang
            </button>
        </div>

        <div class="product-card">
            <img src="miebaso-kuliner.jpg" alt="Mie Ayam Baso" class="product-image">
            <div class="product-name">Mie Ayam Baso</div>
            <div class="product-price">Rp 25.000</div>
            <button class="add-to-cart-btn" data-product='{"id":"2","name":"Mie Ayam Baso","price":25000,"image":"miebaso-kuliner.jpg"}'>
                <i class="fas fa-plus"></i> Tambah ke Keranjang
            </button>
        </div>

        <div class="product-card">
            <img src="miebasourat-kuliner.jpg" alt="Mie Ayam Baso Urat" class="product-image">
            <div class="product-name">Mie Ayam Baso Urat</div>
            <div class="product-price">Rp 30.000</div>
            <button class="add-to-cart-btn" data-product='{"id":"3","name":"Mie Ayam Baso Urat","price":30000,"image":"miebasourat-kuliner.jpg"}'>
                <i class="fas fa-plus"></i> Tambah ke Keranjang
            </button>
        </div>

        <div class="product-card">
            <img src="es teh - kuliner.jpg" alt="Es Teh" class="product-image">
            <div class="product-name">Es Teh Manis</div>
            <div class="product-price">Rp 5.000</div>
            <button class="add-to-cart-btn" data-product='{"id":"4","name":"Es Teh Manis","price":5000,"image":"es teh - kuliner.jpg"}'>
                <i class="fas fa-plus"></i> Tambah ke Keranjang
            </button>
        </div>
    </div>

   <nav class="side-floating-nav" aria-label="Navigasi Utama">
        <div class="side-rail">
            <div class="side-top">
                <a href="konsultasiai.html">
                <button class="icon-pill" aria-label="AI Assistant">
                    <img class="icon-image" src="chat-bot-removebg-preview (1).png" alt="">
                </button>
                </a>
                <a href="komunitas.html">
                <button class="icon-pill" aria-label="Komunitas">
                    <img class="icon-image" src="komunitas_icon-removebg-preview.png" alt="">
                </button>
                </a>
            </div>
            
            <div class="side-icons">
                <a href="petaumkm.html">
                    <button class="icon-pill" aria-label="Peta UMKM">
                        <img class="icon-image" src="lokasi_icon-removebg-preview.png" alt="">
                    </button>
                </a>
                <a href="kategori.html">
                    <button class="icon-pill" aria-label="Jenis UMKM">
                        <img class="icon-image" src="shop_icon-removebg-preview.png" alt="">
                    </button>
                </a>
                <a href="delivery.html">
                <button class="icon-pill" aria-label="Delivery">
                  <img class="icon-image" src="fast-delivery_icon-removebg-preview.png" alt="">
                </button>
                </a>
                <a href="pesan.html">
                <button class="icon-pill" aria-label="Pesan">
                  <img class="icon-image" src="chat_icon-removebg-preview.png" alt="">
                </button>
                </a>
                <a href="investasi.html">
                <button class="icon-pill" aria-label="Investasi">
                  <img class="icon-image" src="invest_icon-removebg-preview.png" alt="">
                </button>
                </a>
            </div>

            <div class="side-bottom">
                <a href="pengaturan.html">
                <button class="icon-pill" aria-label="Pengaturan">
                  <img class="icon-image" src="pengaturan_icon-removebg-preview.png" alt="">
                </button>
                </a>
                <a href="logout.html">
                <button class="icon-pill" aria-label="Logout">
                  <img class="icon-image" src="keluar_icon-removebg-preview.png" alt="">
                </button>
                </a>
            </div>
        </div>
    </nav>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartButton = document.getElementById('cartButton');
        const cartDropdown = document.getElementById('cartDropdown');
        const cartBadge = document.getElementById('cartBadge');
        const cartItems = document.getElementById('cartItems');
        const cartTotalItems = document.getElementById('cartTotalItems');
        const cartTotalPrice = document.getElementById('cartTotalPrice');
        const cartContainer = document.getElementById('cartContainer');

        cartButton.addEventListener('click', (e) => {
            e.stopPropagation();
            cartDropdown.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!cartContainer.contains(e.target)) {
                cartDropdown.classList.remove('active');
            }
        });

        // 🔥 FIX UTAMA - EVENT LISTENER BARU UNTUK SEMUA TOMBOL
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const product = JSON.parse(this.dataset.product);
                    
                    // ✅ CEK GAMBAR ADA/TIDAK
                    const testImg = new Image();
                    testImg.onload = function() {
                        addProductToCart(product, this.src);
                    };
                    testImg.onerror = function() {
                        // Fallback cantik jika gambar hilang
                        const fallback = `https://via.placeholder.com/70x70/4ECDC4/FFFFFF?text=${product.name.replace(/\s/g, '+').substring(0,8)}`;
                        addProductToCart(product, fallback);
                    };
                    testImg.src = product.image;
                });
            });
        });

        function addProductToCart(product, imageUrl) {
            const existing = cart.find(item => item.id === product.id);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({ 
                    id: product.id, 
                    name: product.name, 
                    price: product.price, 
                    image: imageUrl, 
                    quantity: 1 
                });
            }
            updateCartUI();
            showAddSuccess(event.target);
        }

        function showAddSuccess(btn) {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Ditambahkan!';
            btn.style.background = '#28a745';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = 'linear-gradient(90deg, #8D6DFD, #3a71ff)';
            }, 1500);
        }

        // ✅ FUNGSI AWAL ANDA - TIDAK BERUBAH
        function updateCartUI() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            cartBadge.textContent = totalItems || 0;
            cartTotalItems.textContent = `${totalItems || 0} item${totalItems === 1 ? '' : 's'}`;
            cartTotalPrice.textContent = totalPrice.toLocaleString('id-ID');

            if (totalItems === 0) {
                cartItems.innerHTML = '<div class="empty-cart"><i class="fas fa-shopping-cart" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i><p>Keranjang kosong</p><p>Tambahkan produk untuk memulai belanja</p></div>';
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.name}" onerror="this.src='https://via.placeholder.com/70x70/ddd/666?text=?';">
                        <div class="cart-item-details">
                            <h4>${item.name}</h4>
                            <div class="cart-item-price">Rp ${item.price.toLocaleString('id-ID')} x ${item.quantity}</div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity('${item.id}', -1)">-</button>
                                <span>${item.quantity}</span>
                                <button class="quantity-btn" onclick="updateQuantity('${item.id}', 1)">+</button>
                            </div>
                            <button class="remove-item" onclick="removeFromCart('${item.id}')">Hapus</button>
                        </div>
                    </div>
                `).join('');
            }
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function updateQuantity(id, change) {
            const item = cart.find(item => item.id === id);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(id);
                } else {
                    updateCartUI();
                }
            }
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            updateCartUI();
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Keranjang kosong!');
                return;
            }
            const orderData = {
                items: cart.map(item => ({ id: item.id, name: item.name, price: item.price, quantity: item.quantity })),
                subtotal: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
                timestamp: new Date().toISOString()
            };
            localStorage.setItem('orderData', JSON.stringify(orderData));
            window.location.href = 'pembayaran-kuliner-zaki.html';
        }

        updateCartUI();
    </script>
</body>
</html>
