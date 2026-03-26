<?php
session_start();

// Data awal yang akan ditampilkan di dashboard, tapi bisa juga diubah
if (!isset($_SESSION['anak'])) {
    $_SESSION['anak'] = [//nama anak, jadwal terdekat, progres, jadwal mingguan, dan perkembangan anak
        'nama' => "Ujang Saepudin",
        'jadwalTerdekat' => "15 Des 2025",
        'progres' => 76,
        'jadwalMingguan' => [
            ['hari' => "Senin", 'kegiatan' => "Desain Grafis - 09.00"],
            ['hari' => "Rabu", 'kegiatan' => "Kemandirian - 10.00"],
            ['hari' => "Jumat", 'kegiatan' => "Barista - 13.00"]
        ],
        'perkembangan' => [//nilai si anak untuk beberapa aspek perkembangan
            'komunikasi' => 70,
            'kemandirian' => 85,
            'vokasional' => 60
        ]
    ];
}

//input data jadwal mingguan baru dari form tambah kegiatan
if (isset($_POST['tambah_jadwal'])) {//cek apakah form tambah jadwal disubmit
    $hari = htmlspecialchars($_POST['hari']);
    $kegiatan = htmlspecialchars($_POST['kegiatan']);
    
//untuk mensimpan data jdwal mingguannya
    $_SESSION['anak']['jadwalMingguan'][] = [
        'hari' => $hari, 
        'kegiatan' => $kegiatan
    ];
    
//untuk si datanya lang muncul dan di perbarui(maaf kalo salah, saya masih belajar)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}