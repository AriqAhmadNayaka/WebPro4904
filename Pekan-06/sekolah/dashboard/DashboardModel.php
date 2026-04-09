<?php
require_once __DIR__ . '/../../Database.php';

// Class DashboardModel Child Class dari Database
// Bertanggung jawab untuk mengambil semua data statistik dashboard
// Sesuai modul 6.4.8: mewarisi $conn dari Database via extends
class DashboardModel extends Database {

    // READ: Ambil semua statistik untuk stat cards di dashboard
    // SELECT COUNT(*) menghitung jumlah baris sesuai materi modul 4.4.4
    public function getStatistik() {
        return [
            'total_siswa' => (int) $this->conn->query("SELECT COUNT(*) AS total FROM siswa")->fetch_assoc()['total'],
            'siswa_aktif' => (int) $this->conn->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Aktif'")->fetch_assoc()['total'],
            'pelatihan_aktif' => (int) $this->conn->query("SELECT COUNT(*) AS total FROM pelatihan WHERE status = 'Aktif'")->fetch_assoc()['total'],
            'laporan_masuk' => (int) $this->conn->query("SELECT COUNT(*) AS total FROM laporan WHERE status = 'Baru'")->fetch_assoc()['total'],
        ];
    }

    // READ: Ambil data aktivitas terbaru dari 3 tabel untuk ditampilkan di activity list
    // fetch_assoc() mengambil hasil query baris per baris sesuai modul 4.4.5
    public function getAktivitas() {
        $aktivitas = [];

        // 2 siswa terbaru
        $res = $this->conn->query("SELECT nama, kelas, status FROM siswa ORDER BY id DESC LIMIT 2");
        while ($row = $res->fetch_assoc()) {
            $aktivitas[] = [
                'icon' => 'ri-user-line',
                'text' => 'Siswa baru: ' . $row['nama'] . ' — Kelas ' . $row['kelas'],
                'time' => 'Status: ' . $row['status'],
            ];
        }

        // 2 pelatihan terbaru
        $res = $this->conn->query("SELECT nama_program, instruktur, status FROM pelatihan ORDER BY id DESC LIMIT 2");
        while ($row = $res->fetch_assoc()) {
            $aktivitas[] = [
                'icon' => 'ri-book-open-line',
                'text' => 'Pelatihan: ' . $row['nama_program'],
                'time' => 'Instruktur: ' . $row['instruktur'] . ' — ' . $row['status'],
            ];
        }

        // 1 laporan baru terbaru
        $res = $this->conn->query("SELECT nama_siswa, judul, tanggal FROM laporan WHERE status = 'Baru' ORDER BY id DESC LIMIT 1");
        while ($row = $res->fetch_assoc()) {
            $aktivitas[] = [
                'icon' => 'ri-file-warning-line',
                'text' => 'Laporan baru: ' . $row['judul'],
                'time' => 'Siswa: ' . $row['nama_siswa'] . ' — ' . $row['tanggal'],
            ];
        }

        return $aktivitas;
    }

    // READ: Ambil data untuk chart Chart.js (pie status siswa + bar per kelas)
    public function getChartData() {
        $aktif = (int) $this->conn->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Aktif'")->fetch_assoc()['total'];
        $tidakAktif = (int) $this->conn->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Tidak Aktif'")->fetch_assoc()['total'];
        $kelasX = (int) $this->conn->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'X'")->fetch_assoc()['total'];
        $kelasXI = (int) $this->conn->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'XI'")->fetch_assoc()['total'];
        $kelasXII = (int) $this->conn->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'XII'")->fetch_assoc()['total'];

        return [
            'status' => ['labels' => ['Aktif', 'Tidak Aktif'], 'data' => [$aktif, $tidakAktif]],
            'progress' => ['labels' => ['Kelas X', 'Kelas XI', 'Kelas XII'], 'data' => [$kelasX, $kelasXI, $kelasXII]],
        ];
    }
}