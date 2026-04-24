<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // inisialisasi koneksi database ke class (seperti $this->conn = $db di native)
        $this->load->database();
        // load model User_model untuk keperluan register
        $this->load->model('User_model');
        // load helper url dan form untuk CI3
        $this->load->helper(array('url', 'form', 'cookie'));
        // load library session, form_validation (sudah di autoload, tapi eksplisit untuk kejelasan)
        $this->load->library(array('session', 'form_validation'));
    }

    // CEK LOGIN
    // menggantikan fungsi isLogin() dari class Auth native
    // dan fungsi checkLogin() dari class Session native
    private function _isLogin()
    {
        // cek jika username tersedia di session CI3
        return $this->session->userdata('username') ? true : false;
    }

    // HALAMAN LOGIN
    // menggantikan bagian render HTML di login2.php native
    public function index()
    {
        // ambil dari cookie jika session belum ada (checkCookie dari class Session native)
        if ($this->input->cookie('username') && !$this->session->userdata('username')) {
            // set session dari cookie yang tersimpan
            $this->session->set_userdata('username', $this->input->cookie('username'));
        }

        // kalau sudah login, redirect ke halaman dashboard
        if ($this->_isLogin()) {
            redirect('dashboard'); // redirect ke halaman dashboard utama
        }

        $data['username'] = $this->input->cookie('username') ?? ''; // ambil username dari cookie browser
        $data['error']    = '';

        $this->load->view('auth/login', $data); // tampilkan halaman login
    }

    // PROSES LOGIN 
    // menggantikan fungsi login() dari class Auth native
    // dan blok if(isset($_POST['login'])) di login2.php native
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $this->input->post('username'); // ambil input username dari form
            $password = $this->input->post('password'); // ambil input password dari form
            $remember = $this->input->post('remember'); // cek apakah remember me dicentang

            // mengamankan input dari SQL injection (CI3 Active Record otomatis escape)
            // query akan cek user di database
            $query = $this->db->get_where('user', array(
                'username' => $username,
                'password' => $password
            ));

            // jika data ditemukan (login berhasil)
            if ($query->num_rows() > 0) {

                $this->session->set_userdata('username', $username); // simpen username ke session

                if ($remember) { // jika user centang remember me
                    // simpan username ke cookie selama 7 hari
                    $this->input->set_cookie('username', $username, 60 * 60 * 24 * 7);
                }

                redirect('dashboard'); // redirect ke halaman dashboard utama

            } else {
                $data['error'] = 'Username atau password salah!'; // simpan pesan error login gagal
                $data['username'] = $username;
                $this->load->view('auth/login', $data); // tampilkan halaman login dengan error
            }

        } else {
            redirect('auth'); // kembali ke halaman login jika bukan POST
        }
    }

    // LOGOUT
    // menggantikan fungsi logout() dari class Auth native
    // dan file logout.php native
    public function logout()
    {
        $this->session->sess_destroy(); // hapus semua data session
        // hapus cookie dengan waktu kadaluarsa (set ke waktu lampau)
        delete_cookie('username');
        redirect('auth'); // redirect ke halaman login
    }

    // HALAMAN REGISTER
    // menggantikan file register.php native
    public function register()
    {
        // kalau sudah login, redirect ke dashboard
        if ($this->_isLogin()) {
            redirect('dashboard');
        }

        $data['message'] = '';

        $this->load->view('auth/register', $data); // tampilkan halaman register
    }

    // PROSES REGISTER
    // menggantikan blok if(isset($_POST['submit'])) di register.php native
    public function do_register()
    {
        // cek apakah form sudah disubmit user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ambil data dari form (array untuk menampung semua input user)
            $data = array(
                'name'     => $this->input->post('name'),     // ambil input nama dari form
                'email'    => $this->input->post('email'),    // ambil input email dari form
                'username' => $this->input->post('username'), // ambil input username dari form
                'password' => $this->input->post('password'), // ambil input password dari form
                'telepon'  => $this->input->post('telepon'),  // ambil input nomor telepon user
                'alamat'   => $this->input->post('alamat')    // ambil input alamat dari form
            );

            // cek username sudah ada atau belum (fungsi cekUsername dari class User native)
            if ($this->User_model->cekUsername($data['username'])) {
                $message = 'Username sudah digunakan!'; // set pesan jika username sudah ada
            } else {
                // simpan data user ke database (fungsi register dari class User native)
                if ($this->User_model->register($data)) {
                    $message = 'Registrasi berhasil!'; // pesan jika berhasil registrasi
                } else {
                    $message = 'Terjadi error!'; // pesan jika gagal simpan data
                }
            }

            $view_data['message'] = $message;
            $this->load->view('auth/register', $view_data);
        } else {
            redirect('auth/register');
        }
    }
}