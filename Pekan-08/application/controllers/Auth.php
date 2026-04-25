<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller: Auth
// Bertugas menangani semua proses autentikasi pengguna:
//   - Menampilkan halaman login/register (index)
//   - Proses login (login)
//   - Proses registrasi akun baru (register)
//   - Proses logout (logout)
//
// Di CI3, controller mewarisi CI_Controller dan menggunakan
// $this->session (dari library session) sebagai pengganti
// $_SESSION native PHP, serta $this->input->post() sebagai
// pengganti $_POST langsung.

class Auth extends CI_Controller {

    // Daftar redirect sesuai role - sama dengan versi native.
    // Kunci: nama role (sesuai nilai di database kolom 'role').
    // Nilai: nama controller tujuan redirect setelah login/register berhasil.
    private $ROLE_REDIRECT = array(
        'sekolah' => 'dashboard',
    );

    // __construct() dipanggil otomatis saat controller diakses.
    // Wajib memanggil parent::__construct() agar CI3 menginisialisasi
    // semua komponen framework (session, input, db, dll.).
    // Di sini juga dimuat User_model yang dipakai di semua method controller ini.
    public function __construct() {
        parent::__construct();
        // Memuat User_model agar dapat diakses via $this->User_model di seluruh controller
        $this->load->model('User_model');
    }

    // METHOD: index()
    // URL Akses: /auth atau /auth/index (GET)
    // Menampilkan halaman login/register.
    // Jika user sudah punya session aktif (sudah login), langsung
    // redirect ke dashboard agar tidak bisa kembali ke halaman login.
    public function index() {
        // Cek apakah session 'current_user' sudah ada (artinya sudah login).
        // userdata() mengembalikan data atau NULL jika tidak ada.
        if ($this->session->userdata('current_user')) {
            redirect('dashboard');
        }

        // Array $data berisi variabel yang akan tersedia di dalam view.
        // 'notification'      : pesan notifikasi untuk user (kosong di halaman awal)
        // 'notification_type' : tipe notifikasi untuk styling ('error' / 'success')
        // 'active_section'    : menentukan tab mana yang ditampilkan aktif (login/register)
        $data = array(
            'notification'      => '',
            'notification_type' => '',
            'active_section'    => 'login',
        );

        // Baca cookie "ingat saya" untuk mengisi otomatis form login.
        // bacaCookie() membaca $_COOKIE['inklu_email'] dan $_COOKIE['inklu_role'].
        $cookie = $this->User_model->bacaCookie();
        $data['cookieEmail'] = $cookie['email'];
        $data['cookieRole']  = $cookie['role'];

        // Render view sekolah/auth/index.php dengan mengirimkan array $data
        // sebagai variabel lokal yang bisa langsung dipakai di dalam view.
        $this->load->view('sekolah/auth/index', $data);
    }

    // METHOD: logout()
    // URL Akses: /auth/logout
    // Menghancurkan sesi login aktif, kemudian redirect ke halaman login.
    // Pengganti dari session_unset() + session_destroy() di PHP native.
    public function logout() {
        // sess_destroy() menghapus semua data session milik user yang sedang aktif.
        // Dari: session_unset(); session_destroy();
        // Ke  : $this->session->sess_destroy()
        $this->session->sess_destroy();
        redirect('auth');
    }

    // METHOD: register()
    // URL Akses: /auth/register (POST dari form register)
    // Memproses pendaftaran akun baru.
    // Alur: sanitasi input - validasi - cek duplikat email - simpan DB - set session - redirect
    public function register() {
        // Data default untuk view jika terjadi error validasi dan halaman perlu ditampilkan ulang.
        // active_section 'register' memastikan tab Register yang terbuka, bukan Login.
        $data = array(
            'notification'      => '',
            'notification_type' => '',
            'active_section'    => 'register',
        );
        $cookie = $this->User_model->bacaCookie();
        $data['cookieEmail'] = $cookie['email'];
        $data['cookieRole']  = $cookie['role'];

        // Ambil nilai dari form POST dan sanitasi dengan testInput().
        // testInput() melakukan: trim() - stripslashes() - htmlspecialchars()
        // sehingga input aman dari XSS dan karakter berbahaya.
        // Password TIDAK di-sanitasi htmlspecialchars agar karakter spesial (&, <, >) tetap valid.
        $name     = $this->User_model->testInput($this->input->post('name') ?? '');
        $email    = $this->User_model->testInput($this->input->post('email') ?? '');
        $password = $this->input->post('password') ?? '';
        $role     = $this->User_model->testInput($this->input->post('role') ?? '');

        // Rantai validasi dengan elseif agar hanya satu pesan error yang muncul per submit.
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            // Semua field wajib diisi
            $data['notification'] = 'Semua field wajib diisi';
            $data['notification_type'] = 'error';
        } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            // Nama hanya boleh mengandung huruf A-Z, a-z, dan spasi
            $data['notification'] = 'Nama hanya boleh berisi huruf dan spasi';
            $data['notification_type'] = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Validasi format email standar menggunakan filter bawaan PHP
            $data['notification'] = 'Format email tidak valid';
            $data['notification_type'] = 'error';
        } elseif (strlen($password) < 6) {
            // Password harus minimal 6 karakter
            $data['notification'] = 'Password harus minimal 6 karakter';
            $data['notification_type'] = 'error';
        } elseif (!preg_match("/[\W]/", $password)) {
            // \W cocok dengan karakter non-huruf dan non-angka (simbol/spesial)
            // Sehingga password wajib mengandung setidaknya 1 simbol
            $data['notification'] = 'Password harus mengandung minimal 1 karakter spesial (!@#$%^&*)';
            $data['notification_type'] = 'error';
        } elseif ($this->User_model->cekEmailTerdaftar($email)) {
            // Cek ke tabel users apakah email ini sudah pernah didaftarkan sebelumnya
            $data['notification'] = 'Email sudah terdaftar';
            $data['notification_type'] = 'error';
        } else {
            // Semua validasi lolos → proses simpan ke database melalui model
            $newId = $this->User_model->register($name, $email, $password, $role);
            if ($newId) {
                // Berhasil tersimpan - langsung set session agar user otomatis login
                // Dari: $_SESSION['current_user'] = [...]
                // Ke  : $this->session->set_userdata('current_user', [...])
                // set_userdata() menyimpan array data ke dalam session CI3
                $this->session->set_userdata('current_user', array(
                    'id' => $newId, 'name' => $name, 'email' => $email, 'role' => $role,
                ));
                // Redirect sesuai role; gunakan 'auth' sebagai fallback jika role tidak dikenali
                redirect($this->ROLE_REDIRECT[$role] ?? 'auth');
            } else {
                $data['notification'] = 'Registrasi gagal, silakan coba lagi';
                $data['notification_type'] = 'error';
            }
        }

        // Jika ada error validasi, tampilkan kembali view dengan notifikasi error
        $this->load->view('sekolah/auth/index', $data);
    }

    // METHOD: login()
    // URL Akses: /auth/login (POST dari form login)
    // Memproses autentikasi login pengguna.
    // Alur: ambil input - cari user di DB - verifikasi password - set session - redirect
    public function login() {
        // Data default untuk view jika login gagal
        $data = array(
            'notification'      => '',
            'notification_type' => '',
            'active_section'    => 'login',
        );
        $cookie = $this->User_model->bacaCookie();
        $data['cookieEmail'] = $cookie['email'];
        $data['cookieRole']  = $cookie['role'];

        // Sanitasi input POST dari form login
        $email    = $this->User_model->testInput($this->input->post('email') ?? '');
        $password = $this->input->post('password') ?? ''; // Tidak di-escape agar password bisa diverifikasi dengan benar
        $role     = $this->User_model->testInput($this->input->post('role') ?? '');

        if (empty($email) || empty($password) || empty($role)) {
            $data['notification'] = 'Semua field wajib diisi';
            $data['notification_type'] = 'error';
        } else {
            // Cari user di tabel users berdasarkan kombinasi email DAN role.
            // Ini memastikan satu email bisa punya akun dengan role berbeda.
            // Mengembalikan array data user atau null jika tidak ditemukan.
            $user = $this->User_model->cariUser($email, $role);

            if (!$user || !$this->User_model->verifikasiPassword($password, $user['password'])) {
                // User tidak ditemukan di DB ATAU password tidak cocok dengan hash yang tersimpan
                // Pesan error dibuat umum agar tidak membocorkan info (email/role mana yang salah)
                $data['notification'] = 'Email, password, atau role salah';
                $data['notification_type'] = 'error';
            } else {
                // Login berhasil: simpan informasi user ke session CI3
                // Data ini bisa diakses di controller/view lain via $this->session->userdata('current_user')
                $this->session->set_userdata('current_user', array(
                    'id' => $user['id'], 'name' => $user['name'],
                    'email' => $user['email'], 'role' => $user['role'],
                ));

                // Fitur "Ingat Saya": jika checkbox dicentang, simpan cookie email & role
                // selama 30 hari. Jika tidak, hapus cookie yang mungkin sudah ada sebelumnya.
                if ($this->input->post('ingat_saya')) {
                    $this->User_model->simpanCookie($user['email'], $user['role']);
                } else {
                    $this->User_model->hapusCookie();
                }

                // Redirect ke halaman sesuai role; fallback ke 'auth' jika role tidak dikenali
                redirect($this->ROLE_REDIRECT[$user['role']] ?? 'auth');
            }
        }

        // Jika login gagal, tampilkan kembali halaman auth dengan pesan error
        $this->load->view('sekolah/auth/index', $data);
    }
}
