<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up Page - EcoTaste</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<style>
		:root {
			--hijau-primer: #4CAF50;
			--hijau-sekunder: #8BC34A;
			--putih: #FFFFFF;
			--abu-teks: #555;
		}
		* { box-sizing: border-box; }
		body {
			min-height: 100vh;
			margin: 0;
			background: linear-gradient(120deg, var(--hijau-primer), var(--hijau-sekunder));
			font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			color: #333;
		}
		.logo {
			position: fixed;
			top: 32px;
			left: 32px;
			display: flex;
			align-items: center;
			gap: 10px;
			color: var(--putih);
			font-size: 38px;
			font-weight: 800;
		}
		.auth-shell {
			min-height: 100vh;
			display: grid;
			place-items: center;
			padding: 96px 24px 40px;
		}
		.auth-card {
			width: min(612px, 100%);
			background: rgba(255, 255, 255, 0.96);
			border-radius: 22px;
			padding: 58px 56px;
			box-shadow: 0 25px 60px rgba(0, 0, 0, 0.10);
		}
		h1 {
			margin: 0 0 32px;
			color: #2ea84a;
			font-size: 40px;
			font-weight: 500;
		}
		.input-group {
			height: 56px;
			border: 1px solid #d0d0d0;
			border-radius: 999px;
			display: flex;
			align-items: center;
			gap: 14px;
			padding: 0 18px;
			margin-bottom: 26px;
			background: #fff;
		}
		.input-group i {
			color: #83c44b;
			font-size: 19px;
			width: 22px;
			text-align: center;
		}
		.input-group input {
			border: 0;
			outline: 0;
			width: 100%;
			font-size: 20px;
			background: transparent;
			color: var(--abu-teks);
		}
		.submit-btn {
			width: 100%;
			border: 0;
			border-radius: 999px;
			padding: 17px 24px;
			background: #55b95d;
			color: #fff;
			font-size: 24px;
			font-weight: 800;
			cursor: pointer;
			box-shadow: 0 12px 24px rgba(76, 175, 80, 0.25);
		}
		.divider {
			display: flex;
			align-items: center;
			gap: 42px;
			color: #777;
			margin: 42px 0 34px;
		}
		.divider::before,
		.divider::after {
			content: '';
			height: 1px;
			background: #d6d6d6;
			flex: 1;
		}
		.socials {
			display: flex;
			justify-content: center;
			gap: 32px;
			margin-bottom: 38px;
		}
		.social-btn {
			width: 64px;
			height: 64px;
			border-radius: 50%;
			background: #fff;
			border: 1px solid #eee;
			box-shadow: 0 8px 20px rgba(0,0,0,.08);
			display: grid;
			place-items: center;
			font-size: 32px;
		}
		.social-google { color: #ea4335; }
		.social-facebook { color: #1877f2; }
		.switch-link {
			text-align: center;
			color: var(--abu-teks);
			font-size: 18px;
		}
		.switch-link a {
			color: #19993a;
			font-weight: 800;
			text-decoration: none;
		}
		.alert {
			border-radius: 12px;
			padding: 12px 14px;
			margin-bottom: 20px;
			font-weight: 600;
			background: #fdecec;
			color: #b3261e;
		}
		@media (max-width: 680px) {
			.logo { position: static; padding: 26px 24px 0; font-size: 32px; }
			.auth-shell { padding-top: 28px; }
			.auth-card { padding: 36px 24px; }
			h1 { font-size: 34px; }
		}
	</style>
</head>
<body>
	<!-- Logo aplikasi di kiri atas seperti desain referensi. -->
	<div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
	<main class="auth-shell">
		<!-- Kartu putih utama untuk form sign up konsumen. -->
		<section class="auth-card">
			<h1>Sign Up</h1>

			<!-- Pesan error muncul jika validasi registrasi gagal. -->
			<?php if (!empty($error)): ?>
				<div class="alert"><?php echo html_escape($error); ?></div>
			<?php endif; ?>

			<!-- Form dikirim ke Auth/register untuk membuat akun konsumen. -->
			<form action="<?php echo site_url('auth/register'); ?>" method="post">
				<label class="input-group" for="name">
					<i class="fas fa-user"></i>
					<input id="name" type="text" name="name" placeholder="Username" value="<?php echo set_value('name'); ?>" required>
				</label>
				<label class="input-group" for="email">
					<i class="fas fa-envelope"></i>
					<input id="email" type="email" name="email" placeholder="E-mail" value="<?php echo set_value('email'); ?>" required>
				</label>
				<label class="input-group" for="password">
					<i class="fas fa-lock"></i>
					<input id="password" type="password" name="password" placeholder="Password" minlength="6" required>
				</label>
				<label class="input-group" for="password_confirm">
					<i class="fas fa-lock"></i>
					<input id="password_confirm" type="password" name="password_confirm" placeholder="Confirm Password" minlength="6" required>
				</label>
				<button class="submit-btn" type="submit">Sign Up</button>
			</form>

			<!-- Bagian dekoratif agar tampilan mirip referensi desain. -->
			<div class="divider">or</div>
			<div class="socials">
				<div class="social-btn social-google"><i class="fab fa-google"></i></div>
				<div class="social-btn social-facebook"><i class="fab fa-facebook"></i></div>
			</div>
			<!-- Link kembali ke halaman login. -->
			<div class="switch-link">Already have account? <a href="<?php echo site_url(''); ?>">Login</a></div>
		</section>
	</main>
</body>
</html>
