<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct(); // Panggil constructor CI_Controller
        $this->load->model('User_model'); // Load model User_model untuk akses database
        $this->load->library(array('session', 'form_validation')); // Load library session untuk manajemen login dan form_validation untuk validasi form
        $this->load->helper(array('url', 'form')); // Load helper url untuk redirect dan form untuk form helper
    }

    // Menampilkan & memproses form login (pengganti login.php)
    public function index() {
        // Jika sudah login, langsung ke dashboard
        if ($this->session->userdata('login')) {
            redirect('dashboard');
        }

        $data['message'] = '';
        $data['type']    = '';
        $data['role']    = '';
        $data['email']   = '';

        if ($this->input->server('REQUEST_METHOD') == 'POST') { // Cek jika form disubmit
            $role     = $this->input->post('role'); // Ambil role dari form (dokter/pasien)
            $email    = $this->input->post('email'); // Ambil email dari form
            $password = $this->input->post('password'); // Ambil password dari form

            // Logika login (pengganti LoginSystem::login())
            $user = $this->User_model->get_by_email_role($email, $role);

            if ($user && password_verify($password, $user->password)) {
                // Simpan session (pengganti $_SESSION)
                $this->session->set_userdata(array(
                    'login' => true, // Tandai sebagai sudah login
                    'name'  => $user->name, // Simpan nama untuk ditampilkan di dashboard
                    'role'  => $user->role, // Simpan role untuk keperluan akses kontrol
                ));
                redirect('dashboard'); // Redirect ke dashboard setelah login sukses
            } else {
                $data['message'] = 'Email atau Password salah!'; // Pesan error jika login gagal
                $data['type']    = 'danger'; // Tipe pesan untuk styling (misal: bootstrap alert-danger)
                $data['role']    = $role; // Simpan kembali role yang dipilih agar tetap terisi di form
                $data['email']   = $email; // Simpan kembali email yang dimasukkan agar tetap terisi di form
            }
        }

        $this->load->view('auth/login', $data); // Load view login dan kirim data untuk pesan error dan isi form
    }

    // Menampilkan & memproses form registrasi (pengganti registrasi.php)
    public function registrasi() {
        if ($this->session->userdata('login')) { // Jika sudah login, langsung ke dashboard
            redirect('dashboard'); // Pengganti: if (isset($_SESSION['login'])) { header("Location: dashboard.php"); exit; }
        }

        $data['message'] = '';
        $data['type']    = '';
        $data['role']    = '';
        $data['name']    = '';
        $data['email']   = '';

        if ($this->input->server('REQUEST_METHOD') == 'POST') { // Cek jika form disubmit
            $role     = $this->input->post('role'); // Ambil role dari form (dokter/pasien)
            $name     = trim($this->input->post('name')); // Ambil nama dari form dan trim whitespace
            $email    = trim($this->input->post('email')); // Ambil email dari form dan trim whitespace
            $password = trim($this->input->post('password')); // Ambil password dari form dan trim whitespace

            // Logika validasi (pengganti UserAuth::daftar())
            if (empty($role) || empty($name) || empty($email) || empty($password)) { // Cek jika ada field yang kosong
                $data['message'] = 'Semua field wajib diisi!'; // Pesan error jika ada field yang kosong
                $data['type']    = 'danger';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT); // Hash password menggunakan bcrypt
                $result = $this->User_model->insert(array( // Simpan data user baru ke database
                    'role'     => $role,
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $hashed,
                ));

                if ($result) {
                    $data['message'] = 'Akun berhasil dibuat! Silakan login.'; // Pesan sukses jika akun berhasil dibuat
                    $data['type']    = 'success';
                } else {
                    $data['message'] = 'Gagal membuat akun.'; // Pesan error jika terjadi masalah saat menyimpan ke database
                    $data['type']    = 'danger';
                }
            }

            $data['role']  = $role;
            $data['name']  = $name;
            $data['email'] = $email;
        }

        $this->load->view('auth/registrasi', $data); // Load view registrasi dan kirim data untuk pesan error/sukses dan isi form
    }

    // Logout
    public function logout() { // Pengganti logout.php
        $this->session->sess_destroy();
        redirect('auth');
    }
}