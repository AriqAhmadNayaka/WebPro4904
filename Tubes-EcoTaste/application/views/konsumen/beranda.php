<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Beranda - EcoTaste</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<style>
		:root {
			--hijau-primer: #4CAF50;
			--hijau-sekunder: #8BC34A;
			--hijau-latar: #F4F9F4;
			--hijau-gelap: #1B5E20;
			--putih: #FFFFFF;
			--abu-teks: #555;
			--kuning-emas: #FFC107;
			--merah: #E53935;
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
			align-items: center;
			gap: 28px;
			margin: 0;
			padding: 0;
		}
		.nav-links a {
			text-decoration: none;
			color: var(--abu-teks);
			font-weight: 700;
			font-size: 15px;
			position: relative;
		}
		.nav-links a:hover,
		.nav-links a.active { color: var(--hijau-primer); }
		.nav-links a.active::after,
		.nav-links a:hover::after {
			content: '';
			position: absolute;
			width: 25px;
			height: 3px;
			bottom: -7px;
			left: 50%;
			transform: translateX(-50%);
			background-color: var(--hijau-primer);
			border-radius: 2px;
		}
		.logout-link {
			background: var(--merah);
			color: #fff !important;
			padding: 10px 14px;
			border-radius: 7px;
		}
		.logout-link::after { display: none; }
		.main-content {
			padding: 30px 5% 60px;
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
			inset: 0;
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
		}
		.promo-section h1 {
			font-size: 3.5em;
			font-weight: 800;
			margin: 0 0 15px;
			text-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
			position: relative;
			z-index: 2;
		}
		.promo-section p {
			font-size: 1.2em;
			opacity: 0.95;
			max-width: 620px;
			position: relative;
			z-index: 2;
			margin: 0 0 38px;
		}
		.search-container-wrapper {
			position: absolute;
			top: 100%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 90%;
			max-width: 620px;
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
			font-weight: 800;
			margin-bottom: 12px;
			text-transform: uppercase;
			letter-spacing: 1px;
			display: flex;
			align-items: center;
			gap: 8px;
		}
		.search-bar-promo { display: flex; gap: 15px; }
		.search-input-field {
			flex: 1;
			padding: 14px 20px;
			border: 2px solid #E0E0E0;
			border-radius: 12px;
			outline: none;
			font-size: 16px;
			background-color: #FAFAFA;
		}
		.search-button,
		.rating-button {
			padding: 14px 24px;
			border: none;
			border-radius: 12px;
			background: linear-gradient(to right, #66BB6A, var(--hijau-primer));
			color: var(--putih);
			font-size: 16px;
			font-weight: 800;
			cursor: pointer;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
		}
		.section-header-container {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 18px;
			margin-bottom: 30px;
		}
		.product-list-section h2 {
			color: var(--hijau-gelap);
			font-size: 1.8em;
			font-weight: 800;
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
		.filter-container {
			display: flex;
			align-items: center;
			gap: 10px;
		}
		.filter-container label {
			font-weight: 700;
			color: var(--hijau-gelap);
			font-size: 14px;
		}
		.filter-container select {
			padding: 8px 12px;
			border: 2px solid #E0E0E0;
			border-radius: 8px;
			background-color: var(--putih);
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
			cursor: pointer;
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
		}
		.card-image-placeholder i {
			font-size: 4em;
			color: #fff;
			filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
		}
		.card-content {
			padding: 25px;
			flex: 1;
			display: flex;
			flex-direction: column;
		}
		.card-content h3 {
			color: var(--hijau-gelap);
			font-weight: 800;
			font-size: 1.25em;
			margin: 0 0 10px;
		}
		.card-content p {
			font-size: 14px;
			color: var(--abu-teks);
			margin: 0;
		}
		.card-rating-badge {
			margin-top: auto;
			padding-top: 15px;
			display: flex;
			align-items: center;
			gap: 5px;
			font-size: 0.9em;
			font-weight: 700;
			color: var(--kuning-emas);
		}
		.main-footer {
			background-color: var(--hijau-gelap);
			color: var(--putih);
			text-align: center;
			padding: 40px 5%;
			margin-top: 70px;
			font-size: 14px;
		}
		@media (max-width: 768px) {
			.navbar { align-items: flex-start; flex-direction: column; gap: 14px; }
			.nav-links { gap: 14px; flex-wrap: wrap; }
			.promo-section h1 { font-size: 2.6em; }
			.search-bar-promo { flex-direction: column; }
			.section-header-container { align-items: flex-start; flex-direction: column; }
		}
	</style>
</head>
<body>
	<!-- Header putih berisi logo, menu beranda, menu rating, dan logout. -->
	<header class="main-header">
		<nav class="navbar">
			<div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
			<ul class="nav-links">
				<li><a href="<?php echo site_url('konsumen/beranda'); ?>" class="active">Beranda</a></li>
				<li><a href="<?php echo site_url('konsumen/rating'); ?>">Rating</a></li>
				<li><a href="<?php echo site_url('auth/logout'); ?>" class="logout-link">Logout</a></li>
			</ul>
		</nav>
	</header>

	<main class="main-content">
		<!-- Banner utama setelah konsumen berhasil login. -->
		<section class="promo-section" aria-labelledby="promo-heading">
			<i class="fas fa-utensils promo-bg-icon"></i>
			<h1 id="promo-heading">Selamat Datang, <?php echo html_escape($this->session->userdata('name')); ?>!</h1>
			<p>Temukan rekomendasi kuliner dan berikan rating untuk restoran yang menurutmu paling ramah lingkungan.</p>
			<div class="search-container-wrapper">
				<!-- Kotak aksi cepat menuju halaman rating. -->
				<div class="search-box-promo">
					<p><i class="fas fa-star"></i> Mulai kontribusi</p>
					<div class="search-bar-promo">
						<input type="text" class="search-input-field" placeholder="Cari restoran favoritmu">
						<a class="rating-button" href="<?php echo site_url('konsumen/rating'); ?>"><i class="fas fa-pen-to-square"></i> Rating</a>
					</div>
				</div>
			</div>
		</section>

		<!-- Daftar rekomendasi makanan/restoran sebagai konten beranda. -->
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
				<article class="product-card" data-rating="4.5">
					<div class="card-image-placeholder"><i class="fas fa-utensils"></i></div>
					<div class="card-content">
						<h3>Nasi Ngawur</h3>
						<p>Kombinasi rasa yang unik, lezat, dan cocok untuk dinilai.</p>
						<div class="card-rating-badge"><i class="fas fa-star"></i> 4.5</div>
					</div>
				</article>
				<article class="product-card" data-rating="4.2">
					<div class="card-image-placeholder"><i class="fas fa-bowl-food"></i></div>
					<div class="card-content">
						<h3>Mie Hijau Sehat</h3>
						<p>Menu sehat berbahan sayuran dengan rasa ringan.</p>
						<div class="card-rating-badge"><i class="fas fa-star"></i> 4.2</div>
					</div>
				</article>
				<article class="product-card" data-rating="4.8">
					<div class="card-image-placeholder"><i class="fas fa-apple-whole"></i></div>
					<div class="card-content">
						<h3>Salad Buah Organik</h3>
						<p>Segar, ringan, dan memakai bahan lokal pilihan.</p>
						<div class="card-rating-badge"><i class="fas fa-star"></i> 4.8</div>
					</div>
				</article>
			</div>
		</section>
	</main>

	<footer class="main-footer">
		<p>&copy; 2026 EcoTaste. Semua Hak Cipta Dilindungi.</p>
	</footer>

	<script>
		// Filter kartu rekomendasi berdasarkan rating minimum yang dipilih.
		document.getElementById('rating-filter').addEventListener('change', function () {
			const selectedValue = this.value;
			document.querySelectorAll('.product-card').forEach(card => {
				const rating = parseFloat(card.getAttribute('data-rating'));
				card.style.display = selectedValue === 'all' || rating >= parseFloat(selectedValue) ? 'flex' : 'none';
			});
		});
	</script>
</body>
</html>
