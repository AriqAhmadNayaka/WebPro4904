<?php
// DATA DUMMY DASHBOARD SEKOLAH
// file ini di-require oleh dashboardSekolah.php

$infoSekolah = [
    'nama' => 'SLB G YBMU Baleendah',
];

$statistik = [
    'total_siswa'     => 135,
    'siswa_aktif'     => 80,
    'pelatihan_aktif' => 4,
    'laporan_masuk'   => 12,
];

$aktivitas = [
    ['text' => 'Mendaftarkan siswa ke pelatihan',  'time' => '10:30 WIB', 'icon' => 'ri-user-add-line'],
    ['text' => 'Menginput laporan perkembangan',   'time' => '09:10 WIB', 'icon' => 'ri-bar-chart-line'],
    ['text' => 'Melihat rekap siswa',              'time' => '08:45 WIB', 'icon' => 'ri-eye-line'],
];

$dataChart = [
    'status' => [
        'labels' => ['Aktif', 'Nonaktif'],
        'data'   => [80, 55],
    ],
    'progress' => [
        'labels' => ['Dasar', 'Menengah', 'Lanjut'],
        'data'   => [40, 25, 15],
    ],
];