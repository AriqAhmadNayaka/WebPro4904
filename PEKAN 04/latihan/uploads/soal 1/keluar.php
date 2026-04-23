<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Keluar - WeBandoo+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Variabel Warna Tema Bandung Heritage (Optimasi) */
        :root {
            --color-primary-green: #4A8645;
            --color-white: #ffffff;
            --color-off-white: #f0f2f5;
            --color-text-dark: #2c3e50;
            --color-text-grey: #7f8c8d;
            --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.08);
            --color-red-danger: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            min-height: 100vh;
            background-color: var(--color-off-white);
            color: var(--color-text-dark);
            display: flex;
            flex-direction: column;
        }

        .navbar {
            width: 100%;
            background-color: var(--color-text-dark);
            color: var(--color-white);
            padding: 20px 30px;
            box-shadow: var(--shadow-elevation);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 100;
        }

        .navbar .logo {
            font-size: 26px;
            font-weight: 700;
            color: var(--color-primary-green);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #bdc3c7;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
            font-weight: 500;
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 18px;
        }

        .nav-link:hover {
            background-color: #34495e;
            color: var(--color-white);
        }

        .nav-link.active {
            background-color: var(--color-primary-green);
            color: var(--color-white);
            box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-left: 15px;
            border: 2px solid var(--color-primary-green);
            overflow: hidden;
            cursor: pointer;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .toggle-btn-leave {
            background-color: var(--color-primary-green);
            color: var(--color-white);
            box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
            font-size: 15px;
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s;
            margin-left: 15px;
            text-decoration: none;
        }

        .toggle-btn-set {
            background-color: var(--color-red-danger);
            color: var(--color-white);
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
            font-size: 15px;
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s;
            margin-left: 15px;
            text-decoration: none;
        }

        .main-content {
            flex-grow: 1;
            margin-top: 80px;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
        }

        .logout-card {
            background-color: var(--color-white);
            padding: 40px;
            border-radius: 15px;
            box-shadow: var(--shadow-elevation);
            text-align: center;
            max-width: 450px;
            width: 100%;
        }

        .logout-card i {
            font-size: 48px;
            color: var(--color-red-danger);
            margin-bottom: 20px;
        }

        .logout-card h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .logout-card p {
            color: var(--color-text-grey);
            margin-bottom: 30px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .btn-logout {
            flex: 1;
            padding: 12px 20px;
            background-color: var(--color-red-danger);
            color: var(--color-white);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-logout:hover {
            background-color: #c0392b;
        }

        .btn-cancel {
            flex: 1;
            padding: 12px 20px;
            background-color: transparent;
            color: var(--color-text-dark);
            border: 1px solid #ddd;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: background-color 0.3s;
        }

        .btn-cancel:hover {
            background-color: var(--color-off-white);
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 10px;
            }

            .nav-links {
                flex-direction: column;
                margin-top: 10px;
            }

            .nav-links li {
                margin-left: 0;
                margin-bottom: 10px;
            }

            .navbar-right {
                margin-top: 10px;
            }

            .main-content {
                margin-top: 150px;
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="home page.html" class="logo">WeBandoo+</a>
        <ul class="nav-links">
            <li><a href="home page.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="warisan.html" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
            <li><a href="peta.html" class="nav-link"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
            <li><a href="event.html" class="nav-link"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
            <li><a href="eduction.html" class="nav-link"><i class="fas fa-book"></i> Belajar</a></li>
        </ul>
        <div class="navbar-right">
            <div class="avatar">
                <a href="profil.html">
                   <img id="navAvatar" src="Raihan.jpg" alt="Avatar">
                </a>
            </div>
            <a href="pengaturan.php" class="toggle-btn-leave"><i class="fas fa-cog"></i></a>
            <a href="keluar.php" name="keluar" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="logout-card">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>Anda Yakin Ingin Keluar?</h2>
            <p>Sesi Anda saat ini akan diakhiri. Anda akan diarahkan kembali ke halaman Login.</p>
            <div class="action-buttons">
                <a href="index.php" class="btn-logout">Ya, Keluar</a>
                <a href="home page.php" class="btn-cancel">Batal</a>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
    const savedPhoto = localStorage.getItem('userPhoto');
    const navAvatar = document.getElementById('navAvatar');

    if (savedPhoto && navAvatar) {
        navAvatar.src = savedPhoto;
    }
    });
    </script>

</body>

</html>