<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo html_escape($title ?? 'EcoTaste'); ?> - EcoTaste</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
	<style>
		:root {
			--hijau-primer: #4CAF50;
			--hijau-sekunder: #8BC34A;
			--hijau-latar: #F4F9F4;
			--hijau-gelap: #1B5E20;
			--putih: #FFFFFF;
			--abu-teks: #555;
			--merah: #E53935;
			--shadow-card: 0 15px 35px rgba(0, 0, 0, 0.08);
		}
		body {
			min-height: 100vh;
			margin: 0;
			background: var(--hijau-latar);
			background-image: radial-gradient(#E8F5E9 2px, transparent 2px);
			background-size: 30px 30px;
			color: #222;
			font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}
		.app-shell { min-height: 100vh; padding: 24px; }
		.app-header {
			background: var(--hijau-primer);
			color: var(--putih);
			border-radius: 8px;
			padding: 28px 36px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 20px;
			box-shadow: var(--shadow-card);
		}
		.brand-title {
			display: flex;
			align-items: center;
			gap: 10px;
			font-weight: 800;
			font-size: 26px;
		}
		.nav-actions {
			display: flex;
			align-items: center;
			gap: 12px;
			flex-wrap: wrap;
		}
		.nav-actions a {
			color: var(--putih);
			text-decoration: none;
			font-weight: 700;
			padding: 10px 14px;
			border-radius: 7px;
		}
		.nav-actions a.active,
		.nav-actions a:hover { background: rgba(255, 255, 255, 0.16); }
		.nav-actions .logout {
			background: var(--merah);
			color: var(--putih);
		}
		.content { padding: 26px 0 0; }
		.panel {
			background: var(--putih);
			border-radius: 8px;
			box-shadow: var(--shadow-card);
		}
		.panel-pad { padding: 36px; }
		.form-control,
		.form-select {
			border-radius: 6px;
			border: 1px solid #cfcfcf;
			padding: 11px 13px;
		}
		.btn-eco {
			background: var(--hijau-primer);
			border-color: var(--hijau-primer);
			color: #fff;
			font-weight: 700;
			border-radius: 7px;
			padding: 10px 22px;
		}
		.btn-eco:hover { background: #43A047; border-color: #43A047; color: #fff; }
		.table { font-size: 16px; }
		.table thead th {
			background: var(--hijau-primer);
			color: #fff;
			border-color: var(--hijau-primer);
			padding: 15px;
		}
		.table tbody td { padding: 14px 15px; vertical-align: middle; }
		.action-link {
			border: none;
			background: transparent;
			font-weight: 500;
			padding: 0 4px;
		}
		.action-edit { color: #0d6efd; }
		.action-delete { color: #dc3545; }
		@media (max-width: 800px) {
			.app-shell { padding: 14px; }
			.app-header { align-items: flex-start; flex-direction: column; padding: 22px; }
			.panel-pad { padding: 22px; }
		}
	</style>
</head>
<body>
	<!-- Pembungkus utama halaman dashboard/CRUD. -->
	<div class="app-shell">
