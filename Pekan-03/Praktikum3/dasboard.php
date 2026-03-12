<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event & Jadwal - WeBandoo+</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    :root {
        --color-primary-green: #4A8645;
        --color-secondary-brown: #8B4513;
        --color-white: #ffffff;
        --color-off-white: #f0f2f5;
        --color-text-dark: #2c3e50;
        --color-text-grey: #7f8c8d;
        --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.08);
        --color-blue-info: #3498db;
        --color-orange-warning: #f39c12;
    }

    * {
        margin: 0; padding: 0; box-sizing: border-box;
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

    /* Penyesuaian Style Utama */
    .main-content {
        flex-grow: 1;
        margin-top: 80px;
        padding: 40px;
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        background: var(--color-white);
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: var(--shadow-elevation);
    }

    .header-bar h1 {
        font-size: 30px;
        font-weight: 700;
        color: var(--color-text-dark);
    }

    .user-profile {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: var(--color-text-grey);
    }

    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--color-text-dark);
        margin: 40px 0 25px 0;
        border-bottom: 3px solid var(--color-primary-green);
        display: inline-block;
        padding-bottom: 5px;
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .event-card {
        background: var(--color-white);
        border-radius: 12px;
        box-shadow: var(--shadow-elevation);
        overflow: hidden;
        transition: transform 0.3s ease;
        border-left: 8px solid var(--color-blue-info);
    }

    .event-card.festival {
        border-left-color: var(--color-primary-green);
    }

    .event-card.workshop {
        border-left-color: var(--color-orange-warning);
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background-color: var(--color-off-white);
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
    }

    .card-header h3 {
        font-size: 20px;
        color: var(--color-text-dark);
        margin-bottom: 5px;
    }

    .card-header .date {
        font-weight: 600;
        color: var(--color-blue-info);
    }

    .event-details {
        padding: 20px;
    }

    .event-details p {
        margin-bottom: 15px;
        color: var(--color-text-grey);
    }

    .schedule-table-container {
        overflow-x: auto;
        background: var(--color-white);
        border-radius: 12px;
        box-shadow: var(--shadow-elevation);
        padding: 20px;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .schedule-table th {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        font-weight: 600;
        text-transform: uppercase;
        padding: 12px 15px;
        text-align: left;
    }

    .schedule-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid var(--color-off-white);
    }

    .schedule-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .schedule-table td .tag {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Atur agar tabel tidak memaksakan lebar, dan cell bisa membungkus teks */
.schedule-table {
    table-layout: fixed; /* Memaksa tabel mengikuti lebar container */
    width: 100%;
}

.schedule-table td, .schedule-table th {
    word-wrap: break-word;   /* Membungkus kata yang sangat panjang */
    overflow-wrap: break-word;
    white-space: normal;     /* Mengizinkan teks pindah baris */
    padding: 12px;
}

/* Berikan lebar khusus agar kolom Detail lebih luas dibanding yang lain */
.schedule-table td:nth-child(5), .schedule-table th:nth-child(5) {
    width: 30%; /* Kolom detail akan mengambil 30% lebar tabel */
}

    .tag.seni { background-color: #d6eaf8; color: var(--color-blue-info); }
    .tag.musik { background-color: #d4edda; color: var(--color-primary-green); }
    .tag.sejarah { background-color: #f9e79f; color: var(--color-orange-warning); }

    /* Styling Tombol Detail */
.btn-detail {
    background-color: var(--color-blue-info);
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-detail:hover {
    background-color: #2980b9;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}


    /* --- Styling untuk Modal/Pop-up --- */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: none; /* Default hidden */
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .overlay.active {
        display: flex;
    }

    .modal-content {
        background-color: var(--color-white);
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.3s ease-out;
    }

    .modal-content h3 {
        color: var(--color-primary-green);
        margin-bottom: 20px;
        border-bottom: 2px solid var(--color-off-white);
        padding-bottom: 10px;
    }

    .modal-content button {
        margin-top: 20px;
        padding: 10px 20px;
        background-color: var(--color-primary-green);
        color: var(--color-white);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .modal-content button:hover {
        background-color: #3b7436;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
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
            margin-top: 200px;
            padding: 20px;
        }
        .header-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        .header-bar h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .event-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>
<body>

<div id="eventOverlay" class="overlay">
    <div class="modal-content" style="max-width: 600px;">
        <h3><i class="fas fa-calendar-plus"></i> Ajukan Event Budaya</h3>
        <p style="font-size: 14px; margin-bottom: 20px; color: var(--color-text-grey);">
            Silakan lengkapi formulir di bawah ini untuk mendaftarkan event budaya Anda.
        </p>
        
        <form id="eventRegistrationForm" method="POST" action="">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Nama Event</label>
                <input type="text" name="NamaEvent" id="eventName" required placeholder="Contoh: Festival Tari Merak" 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">Tanggal</label>
                    <input type="date" name="tanggal" id="eventDate" required 
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">Kategori</label>
                    <select id="eventCategory" name="Kategori" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="Seni">Seni</option>
                        <option value="Sejarah">Sejarah</option>
                        <option value="Musik">Musik</option>
                        <option value="Workshop">Workshop</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Lokasi</label>
                <input type="text" id="eventLocation" name="Lokasi" required placeholder="Contoh: Gedung Sate" 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Deskripsi Singkat</label>
                <textarea id="eventDesc" name="Detail" rows="3" required placeholder="Jelaskan detail acara..." 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" id="closeModalBtn" style="background-color: #bdc3c7;">Batal</button>
                <button type="submit" style="background-color: var(--color-primary-green);">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<div id="infoModal" class="overlay">
    <div class="modal-content">
        <h3 id="infoTitle">Detail Event</h3>
        <p id="infoBody" style="color: var(--color-text-grey); margin-bottom: 20px;"></p>
        <button onclick="document.getElementById('infoModal').classList.remove('active')">Tutup</button>
    </div>
</div>

    <div class="main-content">
        <div class="header-bar">
            <h1>📅 Event & Jadwal Budaya Kota Bandung</h1>
            <p class="user-profile">Jangan Lewatkan Agenda Seru!</p>
        </div>

        <h2 class="section-title"><i class="fas fa-bullhorn"></i> Event Mendatang Pilihan</h2>

        <div class="event-grid">
            <div class="event-card festival">
                <div class="card-header">
                    <h3>Asia Africa Festival 2026</h3>
                    <span class="date"><i class="fas fa-calendar-alt"></i> 18 - 20 April 2026</span>
                </div>
                <div class="event-details">
                    <p>Perayaan bersejarah Konferensi Asia Afrika (KAA). Akan ada parade budaya, pameran seni, dan pertunjukan dari berbagai negara.</p>
                </div>
            </div>

            <div class="event-card workshop">
                <div class="card-header">
                    <h3>Workshop Intensif Batik Priangan</h3>
                    <span class="date"><i class="fas fa-calendar-alt"></i> 5 Maret 2026</span>
                </div>
                <div class="event-details">
                    <p>Belajar teknik membatik tulis khas Sunda, mengenal motif-motif tradisional Parahyangan, dan membuat karya batik Anda sendiri.</p>
                </div>
            </div>

            <div class="event-card">
                <div class="card-header">
                    <h3>Pagelaran Wayang Golek Ki Dalang</h3>
                    <span class="date" style="color: var(--color-secondary-brown);"><i class="fas fa-calendar-alt"></i> Setiap Malam Minggu</span>
                </div>
                <div class="event-details">
                    <p>Sajian rutin wayang golek semalam suntuk dengan kisah Mahabarata/Ramayana. Dilaksanakan di panggung terbuka.</p>
                </div>
            </div>
        </div>

        <h2 class="section-title"><i class="fas fa-table"></i> Jadwal Aktivitas Budaya Mingguan</h2>

        <div class="schedule-table-container">
            <table class="schedule-table" id="mainScheduleTable">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama Aktivitas</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($_POST["NamaEvent"])){
                    $tgl = date("l, d M Y", strtotime($_POST["tanggal"]));
                    $kat = htmlspecialchars($_POST["Kategori"]);
                    $tag = strtolower($kat);
                    echo "<tr>
                            <td>{$tgl}</td>
                            <td><strong>".htmlspecialchars($_POST["NamaEvent"])."</strong></td>
                            <td><span class='tag {$tag}'>{$kat}</span></td>
                            <td>".htmlspecialchars($_POST["Lokasi"])."</td>
                            <td>".htmlspecialchars($_POST["Detail"])."</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
        </div>

        <div class="header-bar" style="margin-top: 40px; justify-content: center; background: var(--color-primary-green); color: var(--color-white);">
            <p style="color: var(--color-white); font-weight: 600;">Punya Event Budaya? Daftarkan di sini!</p>
            <a href="#" id="openModalBtn" class="toggle-btn-leave" style="background-color: var(--color-white); color: var(--color-primary-green); box-shadow: none;"><i class="fas fa-plus-circle"></i> Ajukan Event</a>
        </div>
    </div>

<script>
    const openButton = document.getElementById('openModalBtn');
    const closeButton = document.getElementById('closeModalBtn');
    const overlay = document.getElementById('eventOverlay');
    const eventForm = document.getElementById('eventRegistrationForm');
    const tableBody = document.querySelector('.schedule-table tbody');

    openButton.addEventListener('click', function(e) {
        e.preventDefault();
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    const closeModal = () => {
        overlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    };

    closeButton.addEventListener('click', closeModal);

    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeModal();
    });

    eventForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const nama = document.getElementById('eventName').value;
        const tanggalRaw = document.getElementById('eventDate').value;
        const kategori = document.getElementById('eventCategory').value;
        const lokasi = document.getElementById('eventLocation').value;

        const dateObj = new Date(tanggalRaw);
        const opsi = { weekday: 'long', hour: '2-digit', minute: '2-digit' };
        const tanggalFormatted = dateObj.toLocaleDateString('id-ID', { weekday: 'long' }) + ", " + 
        (dateObj.getHours().toString().padStart(2, '0')) + ".00";

        let tagClass = 'seni'; // default
        if (kategori === 'Sejarah') tagClass = 'sejarah';
        if (kategori === 'Musik') tagClass = 'musik';
        if (kategori === 'Workshop') tagClass = 'seni';

// Ambil nilai detail dari textarea
const detail = document.getElementById('eventDesc').value;

// 4. Buat baris tabel baru (Row)
const newRow = document.createElement('tr');
newRow.innerHTML = `
    <td>${tanggalFormatted}</td>
    <td>${nama}</td>
    <td><span class="tag ${tagClass}">${kategori}</span></td>
    <td>${lokasi}</td>
    <td>
        <button class="btn-detail" style="padding: 5px 10px; cursor: pointer;">Lihat Detail</button>
    </td>
`;

// 5. Tambahkan Event Listener ke tombol tersebut agar membuka infoModal
newRow.querySelector('.btn-detail').addEventListener('click', () => {
    document.getElementById('infoTitle').innerText = nama;
    document.getElementById('infoBody').innerText = detail;
    document.getElementById('infoModal').classList.add('active');
});

tableBody.prepend(newRow);

        // 5. Tambahkan ke baris paling atas tabel
        tableBody.prepend(newRow);

        // Beri highlight sedikit pada baris baru agar terlihat
        newRow.style.backgroundColor = "#e8f5e9";
        setTimeout(() => { newRow.style.backgroundColor = "transparent"; }, 2000);

        // Reset dan tutup
        alert("Sukses! Event Anda telah ditambahkan ke jadwal mingguan.");
        eventForm.reset();
        closeModal();
    });
</script>
</body>
</html>