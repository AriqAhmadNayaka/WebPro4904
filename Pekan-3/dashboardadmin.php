<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Laporan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            background-attachment: fixed;
            color: #e0e6ed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .main-header {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(100, 200, 255, 0.3);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            padding: 0.5rem 0;
        }

        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #00d4ff;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            color: #e0e6ed;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: linear-gradient(90deg, #00d4ff, #0099cc);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: #00d4ff;
            text-shadow: 0 0 5px rgba(0, 212, 255, 0.3);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-logout {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
            background: linear-gradient(45deg, #ff0040, #cc0033);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 0, 64, 0.3);
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 0, 64, 0.4);
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            padding-top: 80px;
        }

        .sidebar {
            width: 250px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(15px);
            border-right: 1px solid rgba(0, 212, 255, 0.2);
            padding: 2rem 1rem;
            position: fixed;
            height: calc(100vh - 80px);
            overflow-y: auto;
            top: 80px;
            left: 0;
        }

        .sidebar h3 {
            color: #00d4ff;
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar a {
            color: #b8c1cc;
            text-decoration: none;
            display: block;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(0, 212, 255, 0.1);
            color: #00d4ff;
            border-left: 3px solid #00d4ff;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .page-section {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(0, 212, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .page-section h2 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #00d4ff, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 212, 255, 0.2);
        }

        .stat-card h3 {
            font-size: 2rem;
            color: #00d4ff;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #b8c1cc;
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .reports-table th, .reports-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
        }

        .reports-table th {
            background: rgba(0, 212, 255, 0.1);
            color: #00d4ff;
            font-weight: 600;
        }

        .reports-table tr:hover {
            background: rgba(0, 212, 255, 0.05);
        }

        .status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status.pending {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .status.reviewed {
            background: rgba(0, 212, 255, 0.2);
            color: #00d4ff;
        }

        .status.resolved {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 20px;
            background: linear-gradient(45deg, #00d4ff, #0099cc);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.3);
        }

        .main-footer {
            background: rgba(0, 0, 0, 0.95);
            border-top: 1px solid rgba(0, 212, 255, 0.2);
            padding: 3rem 2rem 1rem;
            margin-left: 250px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-links h4, .social-media h4 {
            color: #00d4ff;
            margin-bottom: 1rem;
            text-shadow: 0 0 5px rgba(0, 212, 255, 0.3);
        }

        .footer-links ul, .social-media {
            list-style: none;
        }

        .footer-links a, .social-media a {
            color: #b8c1cc;
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
        }

        .footer-links a:hover, .social-media a:hover {
            color: #00d4ff;
            padding-left: 1rem;
            text-shadow: 0 0 5px rgba(0, 212, 255, 0.3);
        }

        .social-media {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .social-media a {
            padding: 0.5rem 1rem;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 20px;
            border: 1px solid rgba(0, 212, 255, 0.2);
        }

        .copyright {
            text-align: center;
            color: #666;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                margin-bottom: 2rem;
                top: auto;
            }
            .main-content {
                margin-left: 0;
            }
            .main-footer {
                margin-left: 0;
            }
            .reports-table {
                font-size: 0.9rem;
            }
            .nav-links {
                display: none;
            }
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-section {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(#00d4ff, #0099cc);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(#0099cc, #00d4ff);
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="dashboardAdmin.html">Dashboard</a></li>
                <li><a href="kelolaLaporan.html">Laporan</a></li>
                <li><a href="penggunaAdmin.html">Pengguna</a></li>
                <li><a href="pengaturanadmin.html">Pengaturan</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="HomePage.html" class="btn-logout">Logout</a>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <h3>Admin Menu</h3>
            <ul>
                <li><a href="dashboardAdmin.html" class="active">Dashboard</a></li>
                <li><a href="kelolaLaporan.html">Kelola Laporan</a></li>
                <li><a href="validasiCSRIT.html">Laporan Bug & CSIRT</a></li>
                <li><a href="analitik.html">Analitik</a></li>
                <li><a href="pengaturanadmin.html">Pengaturan Sistem</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <section class="page-section">
                <h2>Dashboard Admin</h2>
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <?php 
                        if (isset($_POST['jumlahLaporan'])) {
                        $jumlahLaporan = $_POST['jumlahLaporan'];
                        echo "<p>Jumlah Laporan</p>";
                        echo "<h3>$jumlahLaporan</h3>";
                        } else {
                        $jumlahLaporan = 0; 
                        echo "Data belum diisi.";
                        }
                        ?>
                    </div>
                    <div class="stat-card">
                        <?php 
                        if (isset($_POST['laporanPending'])) {
                        $laporanPending = $_POST['laporanPending'];
                        echo "<p>Jumlah Laporan Pending</p>";
                        echo "<h3>$laporanPending</h3>";
                        } else {
                        $laporanPending = 0; 
                        echo "Data belum diisi.";
                        }
                        ?>
                    </div>
                    <div class="stat-card">
                        <?php 
                        if (isset($_POST['laporanDitinjau'])) {
                        $laporanDitinjau = $_POST['laporanDitinjau'];
                        echo "<p>Jumlah Laporan Ditinjau</p>";
                        echo "<h3>$laporanDitinjau</h3>";
                        } else {
                        $laporanDitinjau = 0; 
                        echo "Data belum diisi.";
                        }
                        ?>
                    </div>
                    <div class="stat-card">
                        <?php 
                        if (isset($_POST['laporanSelesai'])) {
                        $laporanSelesai = $_POST['laporanSelesai'];
                        echo "<p>Jumlah Laporan Selesai</p>";
                        echo "<h3>$laporanSelesai</h3>";
                        } else {
                        $laporanSelesai = 0; 
                        echo "Data belum diisi.";
                        }
                        ?>
                    </div>
                </div>
            </section>

            <section class="page-section">
                <h2>Daftar Laporan Terbaru</h2>
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Phishing</td>
                            <td>Email mencurigakan dari bank...</td>
                            <td>2023-10-01</td>
                            <td><span class="status pending">Pending</span></td>
                            <td><button class="btn-action">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Malware</td>
                            <td>Infeksi virus pada komputer...</td>
                            <td>2023-10-02</td>
                            <td><span class="status reviewed">Ditinjau</span></td>
                            <td><button class="btn-action">Lihat Detail</button></td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>Data Breach</td>
                            <td>Kebocoran data pribadi...</td>
                            <td>2023-10-03</td>
                            <td><span class="status resolved">Diselesaikan</span></td>
                            <td><button class="btn-action">Lihat Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-brand">
                <h3 style="font-size:2rem; background:linear-gradient(90deg,#00d4ff,#00ffaa); background-clip:text; -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
                    CyberVault
                </h3>
                <p style="color:#8892b0; max-width:300px;">Keamanan siber modern untuk masa depan digital Anda.</p>
            </div>

            <div class="footer-links">
                <h4>Link Cepat</h4>
                <ul>
                    <li><a href="HomePage.html">Home</a></li>
                    <li><a href="loginpage.html">Service</a></li>
                    <li><a href="About.html">About</a></li>
                    <li><a href="About.html">Contact</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Ikuti Kami</h4>
                <ul>
                    <li>
                        <a href="https://facebook.com" target="_blank">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com" target="_blank">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com" target="_blank">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="copyright">
            © 2025 <span>CyberVault</span>. All rights reserved.
        </div>
    </footer>

    <script>
const role = localStorage.getItem('userRole');
const loggedIn = localStorage.getItem('isLoggedIn');
</script>

</body>
</html>