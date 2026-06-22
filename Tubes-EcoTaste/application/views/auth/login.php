<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login EcoTaste</title>
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
		.form-row {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 16px;
			margin: 4px 4px 30px;
			font-size: 16px;
		}
		.form-row a,
		.switch-link a {
			color: #19993a;
			font-weight: 800;
			text-decoration: none;
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
		.switch-link {
			margin-top: 38px;
			text-align: center;
			color: var(--abu-teks);
			font-size: 18px;
		}
		.alert {
			border-radius: 12px;
			padding: 12px 14px;
			margin-bottom: 20px;
			font-weight: 600;
		}
		.alert-danger { background: #fdecec; color: #b3261e; }
		.alert-success { background: #eaf7ec; color: #1b7f34; }
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
		<!-- Kartu putih utama untuk form login. -->
		<section class="auth-card">
			<h1>Sign In</h1>

			<!-- Pesan error muncul jika login gagal. -->
			<?php if (!empty($error)): ?>
				<div class="alert alert-danger"><?php echo html_escape($error); ?></div>
			<?php endif; ?>

			<!-- Pesan sukses muncul setelah registrasi berhasil. -->
			<?php if (!empty($success)): ?>
				<div class="alert alert-success"><?php echo html_escape($success); ?></div>
			<?php endif; ?>

			<!-- Form dikirim ke Auth/login untuk dicek email dan password-nya. -->
			<form action="<?php echo site_url('auth/login'); ?>" method="post">
				<label class="input-group" for="email">
					<i class="fas fa-user"></i>
					<input id="email" type="email" name="email" placeholder="Username / E-mail" required>
				</label>
				<label class="input-group" for="password">
					<i class="fas fa-lock"></i>
					<input id="password" type="password" name="password" placeholder="Password" required>
				</label>
				<div class="form-row">
					<label><input type="checkbox"> Remember me</label>
					<a href="#">Forgot password?</a>
				</div>
				<button class="submit-btn" type="submit">Login</button>
			</form>

			<!-- Link menuju halaman sign up konsumen. -->
			<div class="switch-link">Don't have account? <a href="<?php echo site_url('auth/register'); ?>">Sign Up</a></div>
		</section>
	</main>
</body>
</html>
