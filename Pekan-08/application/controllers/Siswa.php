<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller: Siswa
// Bertugas menangani operasi CRUD data siswa:
//   - index()   : Menampilkan daftar siswa dengan filter & pencarian
//   - simpan()  : Menyimpan data baru (CREATE) atau update data (UPDATE)
//   - hapus()   : Menghapus data siswa beserta foto-nya
//   - getById() : Mengembalikan data siswa tertentu dalam format JSON (untuk modal edit AJAX)
//
// Semua method dilindungi guard session di __construct() agar hanya
// user yang sudah login yang bisa mengakses halaman ini.

class Siswa extends CI_Controller {

    // __construct() dijalankan otomatis sebelum method apapun.
    // Memuat Siswa_model dan menjaga akses hanya untuk user yang sudah login.
    public function __construct() {
        parent::__construct();
        // Memuat Siswa_model agar bisa diakses via $this->Siswa_model
        $this->load->model('Siswa_model');
        // Guard: redirect ke halaman auth jika belum login
        if (!$this->session->userdata('current_user')) {
            redirect('auth');
        }
    }

    // METHOD: index()
    // URL Akses: /siswa (GET)
    // Menampilkan daftar seluruh siswa dengan dukungan:
    //   - Filter berdasarkan kelas dan status
    //   - Pencarian berdasarkan keyword (nama, NISN, kelas)
    //   - Notifikasi hasil operasi CRUD via flashdata CI3
    public function index() {
        // Ambil parameter filter dari URL query string (?kelas=X&status=Aktif&keyword=...)
        // Menggunakan $this->input->get() sebagai pengganti $_GET yang aman
        $filterKelas  = $this->input->get('kelas')   ?? '';
        $filterStatus = $this->input->get('status')  ?? '';
        $keyword      = $this->input->get('keyword') ?? '';

        // Ambil data siswa dari model berdasarkan filter yang aktif.
        // Jika filter kosong, model mengembalikan semua data siswa.
        $listSiswa = $this->Siswa_model->getAll($filterKelas, $filterStatus, $keyword);

        // Ambil notifikasi sukses dari flashdata CI3.
        // flashdata hanya tersedia satu kali (satu request) setelah di-set,
        // cocok digunakan untuk notifikasi setelah redirect (Post/Redirect/Get pattern).
        $notification      = $this->session->flashdata('notification') ?? '';
        $notification_type = $this->session->flashdata('notification_type') ?? '';

        // Cek juga apakah ada error validasi yang dikirim via flashdata terpisah
        // (di-set dengan key 'notification_error' dari method simpan())
        if ($this->session->flashdata('notification_error')) {
            $notification      = $this->session->flashdata('notification_error');
            $notification_type = 'error';
        }

        // Siapkan data untuk dikirim ke view
        $data = array(
            'listSiswa'         => $listSiswa,         // Array berisi semua data siswa (hasil query)
            'notification'      => $notification,       // Pesan notifikasi (kosong jika tidak ada)
            'notification_type' => $notification_type, // Tipe notifikasi: 'success' / 'error'
            'filterKelas'       => $filterKelas,        // Nilai filter kelas aktif (untuk mempertahankan pilihan di dropdown)
            'filterStatus'      => $filterStatus,       // Nilai filter status aktif
            'keyword'           => $keyword,            // Keyword pencarian aktif (untuk mempertahankan isian di input)
            'current_user'      => $this->session->userdata('current_user'), // Data user login untuk ditampilkan di topbar
        );

        $this->load->view('sekolah/siswa/index', $data);
    }

    // METHOD: simpan()
    // URL Akses: /siswa/simpan (POST dari form modal tambah/edit)
    // Menangani dua operasi sekaligus:
    //   - CREATE: jika editId = 0, tambah data siswa baru
    //   - UPDATE: jika editId > 0, perbarui data siswa yang ada
    // Setelah selesai, selalu redirect ke /siswa (Post/Redirect/Get pattern)
    public function simpan() {
        // Validasi bahwa request benar-benar berasal dari form siswa (bukan request liar)
        // Jika bukan POST dari form dengan action='simpan', redirect langsung
        if ($this->input->post('action') !== 'simpan') redirect('siswa');

        // Buat alias agar penulisan lebih ringkas
        $model = $this->Siswa_model;

        // Ambil semua input dari POST dan sanitasi dengan testInput()
        // testInput() = trim() + stripslashes() + htmlspecialchars()
        $nisn         = $model->testInput($this->input->post('nisn') ?? '');
        $nama         = $model->testInput($this->input->post('namaLengkap') ?? '');
        $kelas        = $model->testInput($this->input->post('kelas') ?? '');
        $jenisKelamin = $model->testInput($this->input->post('jenisKelamin') ?? '');
        $email        = $model->testInput($this->input->post('email') ?? '');
        $noTelepon    = $model->testInput($this->input->post('noTelepon') ?? '');
        $alamat       = $model->testInput($this->input->post('alamat') ?? '');
        $status       = $model->testInput($this->input->post('status') ?? 'Aktif'); // Default 'Aktif' jika tidak dikirim
        $editId       = (int) ($this->input->post('editId') ?? 0); // 0 = mode tambah, > 0 = mode edit

        // Validasi input — logika SAMA PERSIS dengan versi native PHP
        // Error disimpan ke flashdata agar bisa ditampilkan setelah redirect
        if (empty($nisn)) {
            $this->session->set_flashdata('notification_error', 'NISN wajib diisi');
        } elseif (empty($nama)) {
            $this->session->set_flashdata('notification_error', 'Nama lengkap wajib diisi');
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $nama)) {
            // Nama hanya boleh berisi huruf dan spasi
            $this->session->set_flashdata('notification_error', 'Nama hanya boleh berisi huruf dan spasi');
        } elseif (empty($kelas)) {
            $this->session->set_flashdata('notification_error', 'Kelas wajib dipilih');
        } elseif (empty($jenisKelamin)) {
            $this->session->set_flashdata('notification_error', 'Jenis kelamin wajib dipilih');
        } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Email bersifat opsional, hanya divalidasi format-nya jika diisi
            $this->session->set_flashdata('notification_error', 'Format email tidak valid');
        } else {
            // Semua validasi teks lolos - proses upload foto (jika ada)
            // uploadFoto() menerima data dari $_FILES['foto']
            // Return: null (tidak ada file), nama file (sukses), atau 'ERROR:...' (gagal)
            $hasilUpload = $model->uploadFoto($_FILES['foto'] ?? null);

            if ($hasilUpload !== null && str_starts_with((string)$hasilUpload, 'ERROR:')) {
                // Upload gagal: ambil pesan error setelah prefix 'ERROR:'
                $this->session->set_flashdata('notification_error', substr($hasilUpload, 6));
            } else {
                // $fotoBaru = nama file foto yang baru di-upload (atau null jika tidak ada upload)
                $fotoBaru = $hasilUpload;

                if ($editId > 0) {
                    // MODE EDIT: update data siswa yang sudah ada
                    $siswaLama = $model->getById($editId); // Ambil data lama untuk mendapatkan nama foto lama
                    $fotoLama  = $siswaLama['foto'] ?? null;
                    // model->update() akan hapus foto lama jika ada foto baru
                    $model->update($editId, $nisn, $nama, $kelas, $jenisKelamin, $email, $noTelepon, $alamat, $status, $fotoLama, $fotoBaru);
                    // Set flashdata sukses untuk ditampilkan setelah redirect
                    $this->session->set_flashdata('notification', 'Data siswa berhasil diperbarui');
                    $this->session->set_flashdata('notification_type', 'success');
                } else {
                    // MODE TAMBAH: insert data siswa baru
                    $model->tambah($nisn, $nama, $kelas, $jenisKelamin, $email, $noTelepon, $alamat, $status, $fotoBaru);
                    $this->session->set_flashdata('notification', 'Siswa berhasil ditambahkan');
                    $this->session->set_flashdata('notification_type', 'success');
                }
            }
        }

        // Selalu redirect ke /siswa setelah proses simpan (pola PRG: Post/Redirect/Get)
        // Ini mencegah form tersubmit ulang jika user me-refresh halaman
        redirect('siswa');
    }

    // METHOD: hapus($id)
    // URL Akses: /siswa/hapus/{id}
    // Menghapus data siswa beserta file foto-nya dari server.
    // Logika penghapusan foto ada di dalam model (Siswa_model::hapus()).
    public function hapus($id) {
        // Cast $id ke int untuk keamanan (mencegah SQL injection)
        // Logika hapus foto otomatis dilakukan di dalam Siswa_model::hapus()
        $this->Siswa_model->hapus((int)$id);

        // Set notifikasi sukses via flashdata sebelum redirect
        $this->session->set_flashdata('notification', 'Data siswa berhasil dihapus');
        $this->session->set_flashdata('notification_type', 'success');

        redirect('siswa');
    }

    // METHOD: getById($id)
    // URL Akses: /siswa/getById/{id} (dipanggil via AJAX dari JavaScript modal edit)
    // Mengembalikan data satu siswa dalam format JSON.
    // Digunakan oleh fungsi openEditModal() di view untuk mengisi form edit.
    public function getById($id) {
        // Ambil data siswa berdasarkan ID dari model
        $siswa = $this->Siswa_model->getById((int)$id);

        // Set header response sebagai JSON agar browser/JavaScript tahu tipe data-nya
        header('Content-Type: application/json');

        // Encode array PHP menjadi string JSON dan tampilkan sebagai response
        echo json_encode($siswa);
    }
}
