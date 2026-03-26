<?php
include 'koneksi.php';
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Management Dashboard | WeBandoo+</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --wb-green: #4A8645; 
            --wb-light-green: #76ba6f;
            --wb-dark: #1a2e18;
            --wb-accent: #E6B325;
            --wb-soft-bg: #f4f7f5;
        }

        body { 
            background: var(--wb-soft-bg);
            /* Mesh Gradient Background agar tidak polos */
            background-image: 
                radial-gradient(at 0% 0%, rgba(74, 134, 69, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(230, 179, 37, 0.1) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(74, 134, 69, 0.05) 0px, transparent 50%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0; min-height: 100vh;
            color: var(--wb-dark);
            overflow-x: hidden;
        }

        /* Navbar Transparan */
        .navbar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            padding: 18px 60px;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 1000;
        }

        .brand { font-size: 22px; font-weight: 800; color: var(--wb-dark); text-decoration: none; }
        .brand span { color: var(--wb-green); }

        .container { max-width: 1200px; margin: 40px auto; padding: 0 25px; }

        /* Header Section dengan Animasi */
        .page-header {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-title h1 { font-size: 34px; font-weight: 800; margin: 0; letter-spacing: -1px; }
        .header-title p { color: #666; margin: 5px 0 0; font-size: 16px; }

        .btn-add-main {
            background: var(--wb-green);
            color: white; padding: 16px 30px; border-radius: 20px;
            text-decoration: none; font-weight: 700;
            display: flex; align-items: center; gap: 12px;
            transition: all 0.3s;
            box-shadow: 0 10px 25px rgba(74, 134, 69, 0.2);
        }

        .btn-add-main:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 35px rgba(74, 134, 69, 0.3);
            background: var(--wb-dark);
        }

        /* Glass Card for Content */
        .content-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 40px 100px rgba(0,0,0,0.03);
        }

        /* Custom Table Styling */
        .modern-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
        
        .modern-table th { 
            padding: 0 25px 10px; text-align: left; 
            color: #999; font-size: 12px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px;
        }

        .modern-table tr { transition: 0.3s; }

        .modern-table td { 
            background: white; padding: 25px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.01);
            transition: 0.3s;
        }

        .modern-table td:first-child { border-radius: 25px 0 0 25px; }
        .modern-table td:last-child { border-radius: 0 25px 25px 0; }

        .modern-table tr:hover td { 
            background: #fdfdfd; 
            transform: scale(1.01);
            box-shadow: 0 15px 35px rgba(0,0,0,0.04);
            z-index: 10;
        }

        /* User Identity Design */
        .user-info { display: flex; align-items: center; gap: 15px; }
        .avatar {
            width: 45px; height: 45px; border-radius: 15px;
            background: linear-gradient(135deg, var(--wb-green), var(--wb-light-green));
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 18px;
            box-shadow: 0 8px 15px rgba(74, 134, 69, 0.2);
        }

        .name-wrapper b { display: block; font-size: 16px; color: var(--wb-dark); }
        .name-wrapper span { font-size: 12px; color: var(--wb-green); font-weight: 700; text-transform: uppercase; }

        .email-text { color: #777; font-size: 14px; font-weight: 500; }

        /* Action Buttons */
        .action-pills { display: flex; gap: 10px; }
        .pill {
            padding: 10px 18px; border-radius: 12px;
            text-decoration: none; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; gap: 8px;
            transition: 0.3s;
        }

        .pill-edit { background: #e3f2fd; color: #1976d2; }
        .pill-edit:hover { background: #1976d2; color: white; }

        .pill-delete { background: #ffebee; color: #d32f2f; }
        .pill-delete:hover { background: #d32f2f; color: white; }

        /* Floating Decoration */
        .shape {
            position: absolute; z-index: -1; filter: blur(50px); opacity: 0.3;
            animation: float 6s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-30px); }
        }
    </style>
</head>
<body>

    <div class="shape" style="top: 10%; right: 10%; width: 200px; height: 200px; background: var(--wb-accent);"></div>
    <div class="shape" style="bottom: 10%; left: 5%; width: 250px; height: 250px; background: var(--wb-green);"></div>

    <nav class="navbar">
        <a href="#" class="brand">WeBandoo<span>+</span></a>
        <div style="font-weight: 600; font-size: 14px; color: #666;">
            <i class="fa-solid fa-circle-user" style="color: var(--wb-green); margin-right: 5px;"></i> 
            Admin Panel
        </div>
    </nav>

    <div class="container">
        <header class="page-header">
            <div class="header-title">
                <p>Welcome back, Admin</p>
                <h1>Database <span>Pengunjung</span></h1>
            </div>
            <a href="daftar.php" class="btn-add-main">
                <i class="fa-solid fa-user-plus"></i>
                Daftarkan User Baru
            </a>
        </header>

        <div class="content-card">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Identitas User</th>
                        <th>Alamat Surel</th>
                        <th>Manajemen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="avatar"><?= strtoupper(substr($row['nama'], 0, 1)) ?></div>
                                    <div class="name-wrapper">
                                        <span>ID-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></span>
                                        <b><?= htmlspecialchars($row['nama']) ?></b>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="email-text">
                                    <i class="fa-regular fa-envelope-open" style="margin-right: 8px; opacity: 0.5;"></i>
                                    <?= htmlspecialchars($row['email']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="action-pills">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="pill pill-edit">
                                        <i class="fa-solid fa-pen-nib"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" class="pill pill-delete" onclick="return confirm('Hapus user ini?')">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align: center; color: #999; padding: 50px;">Belum ada data pengunjung.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>