<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller: Dashboard
// Bertugas menampilkan halaman ringkasan/dashboard sekolah.
// Data statistik (jumlah siswa, siswa aktif, pelatihan, laporan)
// dan data chart diambil dari Dashboard_model lalu dikirim ke view.
//
// Controller ini dilindungi dengan pengecekan session di __construct(),
// sehingga hanya user yang sudah login yang bisa mengaksesnya.

class Dashboard extends CI_Controller {

    // __construct() dijalankan otomatis sebelum method apapun di controller ini.
    // Memuat Dashboard_model dan melakukan guard (penjaga akses) berdasarkan session.
    public function __construct() {
        parent::__construct();
        // Memuat Dashboard_model agar dapat diakses via $this->Dashboard_model
        $this->load->model('Dashboard_model');
        // Guard: jika tidak ada session 'current_user' (belum login), redirect ke halaman auth.
        // Ini mencegah akses langsung ke /dashboard tanpa login terlebih dahulu.
        if (!$this->session->userdata('current_user')) {
            redirect('auth');
        }
    }

    // METHOD: index()
    // URL Akses: /dashboard atau /dashboard/index
    // Mengambil semua data yang dibutuhkan halaman dashboard dari model,
    // lalu mengirimkannya ke view untuk ditampilkan.
    public function index() {
        // Buat shortcut alias agar penulisan kode lebih ringkas
        $dashboard = $this->Dashboard_model;

        // Ambil data statistik berisi array dengan kunci:
        // 'total_siswa', 'siswa_aktif', 'pelatihan_aktif', 'laporan_masuk'
        $statistik = $dashboard->getStatistik();

        // Ambil data aktivitas terbaru (array of activity items)
        // yang akan ditampilkan di feed aktivitas sidebar dashboard
        $aktivitas = $dashboard->getAktivitas();

        // Ambil data untuk Chart.js (status aktif/tidak aktif & distribusi kelas)
        // Sudah dalam format array siap di-encode ke JSON
        $chartData = $dashboard->getChartData();

        // Siapkan array $data yang akan menjadi variabel di view.
        // Setiap key array menjadi variabel PHP yang bisa langsung dipakai di view.
        $data = array(
            // Nama pengguna yang login, diambil dari session, di-escape untuk keamanan XSS
            'namaSekolah'    => htmlspecialchars($this->session->userdata('current_user')['name'] ?? 'Sekolah'),

            // Nilai-nilai statistik dari model, di-cast ke int untuk memastikan tipe data
            'totalSiswa'     => $statistik['total_siswa'],
            'siswaAktif'     => $statistik['siswa_aktif'],
            'pelatihanAktif' => $statistik['pelatihan_aktif'],
            'laporanMasuk'   => $statistik['laporan_masuk'],

            // Data JSON untuk Chart.js di view.
            // json_encode() mengubah array PHP menjadi string JSON yang bisa dibaca JavaScript.
            'aktivitasJson'  => json_encode($aktivitas),
            'dataChartJson'  => json_encode($chartData),
        );

        // Render view dashboard dengan data yang sudah disiapkan
        $this->load->view('sekolah/dashboard/index', $data);
    }
}
