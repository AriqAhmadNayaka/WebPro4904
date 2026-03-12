<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anak</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<div class="sidebar">
    <h2 class="logo">InkluSkill</h2>

    <a href="../dashboard/dashboardOrtu.html"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="#" class="active"><i class="ri-user-3-line"></i>Data Anak</a>
    <a href="../jadwal/index.html"><i class="ri-calendar-check-line"></i>Jadwal</a>
    <a href="../perkembangan/index.html"><i class="ri-bar-chart-line"></i>Status & Perkembangan</a>
    <a href="../profil/index.html"><i class="ri-user-settings-line"></i>Profil</a>

    <button class="logout"><i class="ri-logout-circle-line"></i>Logout</button>
</div>

<div class="content">

    <!-- HEADER -->
    <div class="page-header">
        <h1><i class="ri-user-3-fill"></i> Data Anak</h1>
        <p>Informasi ini membantu sekolah memahami kebutuhan dan perkembangan anak Anda</p>
    </div>

    <!-- CARD UTAMA -->
    <div class="profile-box">

        <!-- FOTO ANAK -->
        <div class="photo-box">
            <img id="fotoProfil" src="https://i.ibb.co/YfyqgLw/default-avatar.png">
            <label class="upload-label">
                <i class="ri-camera-line"></i> Ubah Foto
                <input type="file" id="fotoInput" hidden>
            </label>
        </div>

        <!-- FORM DATA -->
        <div class="big-card">
            <h3>Informasi Anak</h3>

            <div class="info-grid">

                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="nama">
                </div>

                <div class="input-group">
                    <label>Jenis Kelamin</label>
                    <select id="gender">
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" id="tanggalLahir">
                </div>

                <div class="input-group">
                    <label>Kelas / Program</label>
                    <input type="text" id="kelas">
                </div>

                <div class="input-group full">
                    <label>Alamat</label>
                    <input type="text" id="alamat">
                </div>

                <div class="input-group full">
                    <label>Catatan Khusus</label>
                    <textarea id="catatan" placeholder="Contoh: kebutuhan khusus, alergi, atau catatan penting lainnya"></textarea>
                </div>

            </div>

            <button class="save-btn" id="toggleBtn">
                <i class="ri-save-line"></i> Simpan Data
            </button>
        </div>

    </div>

</div>

<script src="main.js"></script>
</body>
</html>
