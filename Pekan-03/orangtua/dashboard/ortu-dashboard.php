<?php
session_start();

// Data Awal (Read)
if (!isset($_SESSION['anak'])) {
    $_SESSION['anak'] = [
        'nama' => "Ujang Saepudin",
        'jadwalTerdekat' => "15 Des 2025",
        'progres' => 76,
        'jadwalMingguan' => [
            ['hari' => "Senin", 'kegiatan' => "Desain Grafis - 09.00"],
            ['hari' => "Rabu", 'kegiatan' => "Kemandirian - 10.00"],
            ['hari' => "Jumat", 'kegiatan' => "Barista - 13.00"]
        ],
        'perkembangan' => [
            'komunikasi' => 70,
            'kemandirian' => 85,
            'vokasional' => 60
        ]
    ];
}

// Logika Tambah Data (Create)
if (isset($_POST['tambah_jadwal'])) {
    $hari = htmlspecialchars($_POST['hari']);
    $kegiatan = htmlspecialchars($_POST['kegiatan']);
    
    // Masukkan ke array session
    $_SESSION['anak']['jadwalMingguan'][] = [
        'hari' => $hari, 
        'kegiatan' => $kegiatan
    ];
    
    // Refresh agar form tidak resubmit saat F5
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}