<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model: Dashboard_model
// Menyediakan data statistik dan aktivitas untuk halaman dashboard.
// Semua query langsung menggunakan $this->db->query() karena
// mengandung COUNT() agregat yang lebih mudah ditulis dalam SQL mentah.
//
// Perbedaan dari versi native:
//   - $this->conn->query(...)->fetch_assoc() → $this->db->query(...)->row()->kolom
//   - $res->fetch_assoc() di loop → $query->result_array()

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Database sudah di-autoload via autoload.php, tidak perlu connect manual
    }

    // METHOD: getStatistik()
    // Mengambil 4 angka statistik utama untuk kartu ringkasan di dashboard:
    //   - Total semua siswa
    //   - Siswa dengan status 'Aktif'
    //   - Pelatihan dengan status 'Aktif'
    //   - Laporan dengan status 'Baru'
    //
    // Return: array asosiatif berisi 4 nilai integer statistik
    //
    // Dari: $this->conn->query("SELECT COUNT(*) AS total...")->fetch_assoc()['total']
    // Ke  : $this->db->query("SELECT COUNT(*) AS total...")->row()->total
    // row() mengembalikan object baris pertama; ->total mengakses kolom alias 'total'
    public function getStatistik() {
        return array(
            // Hitung total semua baris di tabel siswa (cast ke int untuk keamanan tipe data)
            'total_siswa'     => (int) $this->db->query("SELECT COUNT(*) AS total FROM siswa")->row()->total,
            // Hitung siswa yang statusnya 'Aktif'
            'siswa_aktif'     => (int) $this->db->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Aktif'")->row()->total,
            // Hitung pelatihan yang statusnya 'Aktif' (tabel: pelatihan)
            'pelatihan_aktif' => (int) $this->db->query("SELECT COUNT(*) AS total FROM pelatihan WHERE status = 'Aktif'")->row()->total,
            // Hitung laporan yang statusnya 'Baru' (tabel: laporan)
            'laporan_masuk'   => (int) $this->db->query("SELECT COUNT(*) AS total FROM laporan WHERE status = 'Baru'")->row()->total,
        );
    }

    // METHOD: getAktivitas()
    // Mengambil data aktivitas terbaru dari tiga tabel (siswa, pelatihan, laporan)
    // untuk ditampilkan di feed aktivitas pada halaman dashboard.
    //
    // Return: array of arrays, setiap item berisi:
    //   - 'icon' : nama kelas icon Remix Icon
    //   - 'text' : deskripsi aktivitas
    //   - 'time' : keterangan waktu atau status
    //
    // Dari: while ($row = $res->fetch_assoc()) di versi native
    // Ke  : foreach ($query->result_array() as $row) di CI3
    // result_array() mengembalikan semua baris sebagai array of arrays
    public function getAktivitas() {
        $aktivitas = array();

        // Ambil 2 siswa terbaru berdasarkan ID terbesar (paling baru diinput)
        $res = $this->db->query("SELECT nama, kelas, status FROM siswa ORDER BY id DESC LIMIT 2");
        foreach ($res->result_array() as $row) {
            $aktivitas[] = array(
                'icon' => 'ri-user-line',
                'text' => 'Siswa baru: ' . $row['nama'] . ' — Kelas ' . $row['kelas'],
                'time' => 'Status: ' . $row['status'],
            );
        }

        // Ambil 2 pelatihan terbaru berdasarkan ID terbesar
        $res = $this->db->query("SELECT nama_program, instruktur, status FROM pelatihan ORDER BY id DESC LIMIT 2");
        foreach ($res->result_array() as $row) {
            $aktivitas[] = array(
                'icon' => 'ri-book-open-line',
                'text' => 'Pelatihan: ' . $row['nama_program'],
                'time' => 'Instruktur: ' . $row['instruktur'] . ' — ' . $row['status'],
            );
        }

        // Ambil 1 laporan terbaru yang statusnya 'Baru'
        $res = $this->db->query("SELECT nama_siswa, judul, tanggal FROM laporan WHERE status = 'Baru' ORDER BY id DESC LIMIT 1");
        foreach ($res->result_array() as $row) {
            $aktivitas[] = array(
                'icon' => 'ri-file-warning-line',
                'text' => 'Laporan baru: ' . $row['judul'],
                'time' => 'Siswa: ' . $row['nama_siswa'] . ' — ' . $row['tanggal'],
            );
        }

        return $aktivitas;
    }

    // METHOD: getChartData()
    // Mengambil data untuk dua grafik Chart.js di dashboard:
    //   1. Grafik Donut: distribusi status siswa (Aktif vs Tidak Aktif)
    //   2. Grafik Batang: distribusi kelas (X, XI, XII)
    //
    // Return: array dengan dua key:
    //   - 'status'   : data pie chart status siswa
    //   - 'progress' : data bar chart distribusi kelas
    //
    // Logika sama persis dengan versi native, hanya $this->conn - $this->db
    public function getChartData() {
        // Hitung siswa berdasarkan status
        $aktif      = (int) $this->db->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Aktif'")->row()->total;
        $tidakAktif = (int) $this->db->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Tidak Aktif'")->row()->total;

        // Hitung siswa per kelas
        $kelasX   = (int) $this->db->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'X'")->row()->total;
        $kelasXI  = (int) $this->db->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'XI'")->row()->total;
        $kelasXII = (int) $this->db->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'XII'")->row()->total;

        // Format return yang cocok dengan kebutuhan Chart.js di view (labels + data array)
        return array(
            'status' => array(
                'labels' => array('Aktif', 'Tidak Aktif'),
                'data'   => array($aktif, $tidakAktif),
            ),
            'progress' => array(
                'labels' => array('Kelas X', 'Kelas XI', 'Kelas XII'),
                'data'   => array($kelasX, $kelasXI, $kelasXII),
            ),
        );
    }
}
