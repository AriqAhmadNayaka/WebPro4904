<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Auth
 *
 * Menangani proses autentikasi pengguna:
 * - index()    : Redirect ke login
 * - login()    : Tampilkan form login & proses POST
 * - register() : Tampilkan form register & proses POST
 * - logout()   : Hapus sesi & redirect ke login
 *
 * URI: /auth/login | /auth/register | /auth/logout
 */
class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    /**
     * Default method — redirect ke halaman login.
     */
    public function index()
    {
        redirect('auth/login');
    }

    /**
     * Halaman & proses Login.
     * GET  → tampilkan view login
     * POST → validasi input, panggil User_model::login()
     */
    public function login()
    {
        // Jika sudah login, langsung ke dashboard
        if ($this->session->userdata('login')) {
            redirect('proyek');
        }

        $data = ['error' => ''];

        if ($this->input->post('login')) {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password');

            if ($this->User_model->login($username, $password)) {
                redirect('proyek');
            } else {
                $data['error'] = 'Username atau password salah!';
            }
        }

        $this->load->view('auth/login', $data);
    }

    /**
     * Halaman & proses Register.
     * GET  → tampilkan view register
     * POST → validasi input, panggil User_model::register()
     */
    public function register()
    {
        // Jika sudah login, langsung ke dashboard
        if ($this->session->userdata('login')) {
            redirect('proyek');
        }

        $data = ['error' => '', 'sukses' => ''];

        if ($this->input->post('register')) {
            $username        = $this->input->post('username', TRUE);
            $password        = $this->input->post('password');
            $confirm_password = $this->input->post('confirm_password');

            $hasil = $this->User_model->register($username, $password, $confirm_password);

            if ($hasil === 'sukses') {
                $data['sukses'] = 'Registrasi berhasil! Silakan login.';
            } else {
                $data['error'] = $hasil;
            }
        }

        $this->load->view('auth/register', $data);
    }

    /**
     * Logout — hapus semua data sesi & redirect ke login.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
?>
