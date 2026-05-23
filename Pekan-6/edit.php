<?php
include("Koneksi.php");
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$controller = new PasienController();
$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$data = $controller->getPasienById($id);

if (!$data) {
    header("Location: dashboard.php?message=" . urlencode("Data pasien tidak ditemukan.") . "&type=error");
    exit;
}

$loggedInUser = $_SESSION["user"]["email"] ?? $_SESSION["user"]["nama"] ?? "Pengguna";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pasien - LifeTrack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
            color: #1f2937;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 23px 100px;
            margin-bottom: 30px;
            box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.2);
            background: white;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bulb {
            width: 16px;
            height: 16px;
            background: #f7c948;
            border-radius: 50%;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .profile-badge {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #d1fae5;
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        .page-header,
        .box {
            width: 90%;
            margin: 0 auto 20px;
        }

        .page-header h1 {
            font-size: 40px;
            margin-bottom: 5px;
            color: #064e3b;
        }

        .subtitle {
            color: #6b7280;
        }

        .box {
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #e5e8ea;
            background: white;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            outline: none;
        }

        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        button,
        .secondary-link {
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        button {
            background-color: #448b84;
            color: white;
        }

        .secondary-link {
            background: #e5e7eb;
            color: #374151;
        }

        @media (max-width: 900px) {
            .navbar {
                padding: 20px;
                flex-direction: column;
                gap: 14px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <span class="bulb"></span>
            <h2>LifeTrack</h2>
        </div>

        <div class="profile">
            <div class="profile-badge"><?= strtoupper(substr($loggedInUser, 0, 1)); ?></div>
            <span><?= htmlspecialchars($loggedInUser); ?></span>
        </div>
    </nav>

    <div class="page-header">
        <h1>Edit Data Pasien</h1>
        <p class="subtitle">Perbarui data pasien melalui halaman edit terpisah.</p>
    </div>

    <div class="box">
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= (int) $data["id"]; ?>">

            <div class="form-grid">
                    <div>
                        <label>Lokasi TPS</label>
                        <input type="text" name="lokasi" placeholder="lokasi" required>
                    </div>

                    <div>
                        <label>Waktu TPS</label>
                        <input type="text" name="waktu" placeholder="Waktu" required>
                    </div>

                    <div>
                        <label>Berat Sampah</label>
                        <input type="text" name="berat" placeholder="Berat(kg)" required>
                    </div>

                    <div>
                        <label>Status Kapasitas</label>
                        <input type="text" name="status"  placeholder="Status" required>
                    </div>

                    <div>
                        <label>Deskripsi</label>
                        <input type="text" name="deskripsi"  placeholder="Penjelasan singkat kondisi lapangan" required>
                    </div>

                    <div>
                        <label>Foto Bukti</label>
                        <input type="img" name="img" placeholder="foto" required>
                    </div>

                <div class="button-row">
                <button type="submit">Simpan Perubahan</button>
                <a class="secondary-link" href="dashboard.php">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>
