		<!-- Header dashboard berisi nama aplikasi, sapaan user, menu, dan logout. -->
		<header class="app-header">
			<div class="brand-title">
				<i class="fa-solid fa-leaf"></i>
				<span><?php echo ($this->session->userdata('role') === 'mitra') ? 'Dashboard EcoTaste' : 'EcoTaste'; ?></span>
			</div>
			<nav class="nav-actions">
				<span>Halo, <?php echo html_escape($this->session->userdata('name')); ?>!</span>
				<!-- Menu yang muncul dibedakan berdasarkan role user. -->
				<?php if ($this->session->userdata('role') === 'konsumen'): ?>
					<a class="<?php echo ($active ?? '') === 'beranda' ? 'active' : ''; ?>" href="<?php echo site_url('konsumen/beranda'); ?>">Beranda</a>
					<a class="<?php echo ($active ?? '') === 'rating' ? 'active' : ''; ?>" href="<?php echo site_url('konsumen/rating'); ?>">Rating</a>
				<?php endif; ?>
				<?php if ($this->session->userdata('role') === 'mitra'): ?>
					<a class="<?php echo ($active ?? '') === 'limbah' ? 'active' : ''; ?>" href="<?php echo site_url('mitra/limbah'); ?>">Laporan Limbah</a>
				<?php endif; ?>
				<a class="logout" href="<?php echo site_url('auth/logout'); ?>">Logout</a>
			</nav>
		</header>
		<main class="content">
