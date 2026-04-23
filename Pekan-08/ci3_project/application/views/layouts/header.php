<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo isset($title) ? $title : 'WeBandoo+'; ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { background-color: #f0f4f8; min-height: 100vh; }

/* ── Navbar ── */
.navbar {
    display: flex; justify-content: space-between; align-items: center;
    background-color: #0f4c3a; color: white; padding: 12px 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.navbar a.logo { font-weight: 700; font-size: 1.4rem; color: white; text-decoration: none; }
.nav-links { list-style: none; display: flex; margin: 0; padding: 0; gap: 8px; }
.nav-links a {
    color: white; text-decoration: none; font-weight: 500;
    padding: 6px 14px; border-radius: 6px; transition: background 0.2s;
    display: flex; align-items: center; gap: 6px;
}
.nav-links a:hover, .nav-links a.active { background: rgba(255,255,255,0.15); }
.navbar-right { display: flex; align-items: center; gap: 12px; }
.navbar-right span { color: rgba(255,255,255,0.8); font-size: 0.9rem; }
.navbar-right a {
    color: white; text-decoration: none; padding: 6px 14px;
    border-radius: 6px; transition: background 0.2s; font-size: 0.9rem;
}
.navbar-right a:hover { background: rgba(255,255,255,0.15); }
.btn-logout { background: rgba(231,76,60,0.3); }
.btn-logout:hover { background: rgba(231,76,60,0.6) !important; }

/* ── Container ── */
.container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

/* ── Alert ── */
.alert {
    padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;
    font-size: 14px; display: flex; align-items: center; gap: 10px;
}
.alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
.alert-error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

/* ── Card ── */
.card {
    background: white; border-radius: 12px; padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08); margin-bottom: 24px;
}
.card h2 { color: #0f4c3a; margin-bottom: 16px; }

/* ── Tombol ── */
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 8px; font-weight: 600;
    text-decoration: none; cursor: pointer; border: none;
    font-size: 14px; transition: all 0.2s;
}
.btn-primary   { background: #0f4c3a; color: white; }
.btn-primary:hover { background: #0a3328; }
.btn-success   { background: #00b894; color: white; }
.btn-success:hover { background: #019875; }
.btn-warning   { background: #f39c12; color: white; }
.btn-warning:hover { background: #d68910; }
.btn-danger    { background: #e74c3c; color: white; }
.btn-danger:hover { background: #c0392b; }
.btn-secondary { background: #95a5a6; color: white; }
.btn-secondary:hover { background: #7f8c8d; }

/* ── Form ── */
.form-group { margin-bottom: 18px; }
.form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px; }
.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group textarea,
.form-group input[type="file"] {
    width: 100%; padding: 10px 12px; border: 1.5px solid #ddd;
    border-radius: 8px; font-size: 14px; transition: border-color 0.2s;
}
.form-group input:focus,
.form-group textarea:focus { outline: none; border-color: #00b894; }
.form-group textarea { resize: vertical; min-height: 100px; }
.form-actions { display: flex; gap: 10px; margin-top: 10px; }

/* ── Tabel ── */
table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; }
table thead { background: #0f4c3a; color: white; }
table th, table td { padding: 12px 14px; text-align: left; font-size: 14px; }
table td { border-bottom: 1px solid #f0f0f0; }
table tbody tr:hover { background: #f9fffe; }
table tbody tr:last-child td { border-bottom: none; }
.actions { display: flex; gap: 6px; flex-wrap: wrap; }

/* ── Wishlist card grid ── */
.wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
.wishlist-card {
    background: white; border-radius: 12px; overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.2s;
}
.wishlist-card:hover { transform: translateY(-4px); }
.wishlist-card img { width: 100%; height: 180px; object-fit: cover; }
.wishlist-card .no-img {
    width: 100%; height: 180px; background: #ecf0f1;
    display: flex; align-items: center; justify-content: center;
    color: #95a5a6; font-size: 13px;
}
.wishlist-card .card-body { padding: 16px; }
.wishlist-card .card-body h3 { color: #0f4c3a; margin-bottom: 8px; font-size: 16px; }
.wishlist-card .card-body p { color: #555; font-size: 13px; margin-bottom: 4px; }
.wishlist-card .card-body .harga { color: #00b894; font-weight: 700; font-size: 15px; margin: 8px 0; }
.wishlist-card .card-footer { padding: 12px 16px; border-top: 1px solid #f0f0f0; display: flex; gap: 8px; }

/* ── Dashboard stats ── */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card {
    background: white; border-radius: 12px; padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07); text-align: center;
}
.stat-card .stat-icon { font-size: 2rem; margin-bottom: 8px; }
.stat-card .stat-number { font-size: 2rem; font-weight: 700; color: #0f4c3a; }
.stat-card .stat-label { font-size: 13px; color: #888; margin-top: 4px; }

/* ── Gambar lama preview ── */
.img-preview { margin-top: 8px; }
.img-preview img { width: 120px; border-radius: 8px; border: 1px solid #ddd; }
</style>
</head>
<body>

<?php if ($this->session->userdata('user_login')): ?>
<nav class="navbar">
    <a href="<?php echo base_url('dashboard'); ?>" class="logo">
        <i class="fas fa-map-marked-alt"></i> WeBandoo+
    </a>
    <ul class="nav-links">
        <li>
            <a href="<?php echo base_url('dashboard'); ?>"
               class="<?php echo ($this->router->fetch_class() == 'Dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo base_url('wishlist'); ?>"
               class="<?php echo ($this->router->fetch_class() == 'Wishlist') ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Wishlist
            </a>
        </li>
    </ul>
    <div class="navbar-right">
        <span><i class="fas fa-user-circle"></i> <?php echo $this->session->userdata('user_login'); ?></span>
        <a href="<?php echo base_url('logout'); ?>" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </div>
</nav>
<?php endif; ?>
