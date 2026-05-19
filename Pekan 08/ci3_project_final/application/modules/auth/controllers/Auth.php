<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CONTROLLER: Auth
 * Modul: application/modules/auth/controllers/Auth.php
 *
 * Menggabungkan logika dari:
 *  - pekan8/login.php    → method index() & do_login()
 *  - pekan8/register.php → method register() & do_register()
 *  - logout              → method logout()
 *
 * Di pekan8 logika bisnis (login/register) campur dengan HTML di satu file.
 * Di CI3-MVC: Controller hanya routing & memanggil Model, lalu load View.
 */
class Auth extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Tampilkan halaman login.
     * Jika sudah login → redirect ke proyek.
     * Setara: pekan8/login.php (bagian tampilan form)
     */
    public function index()
    {
        // Sudah login → langsung ke dashboard proyek
        if ($this->session->userdata('login')) {
            redirect('proyek');
        }
        $this->load->view('auth/login');
    }

    /**
     * Proses login (POST).
     * Setara: pekan8/login.php (bagian if isset($_POST['login']))
     */
    public function do_login()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        if ($this->Auth_model->login($username, $password)) {
            redirect('proyek');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('auth');
        }
    }

    /**
     * Tampilkan halaman register.
     * Setara: pekan8/register.php (bagian tampilan form)
     */
    public function register()
    {
        if ($this->session->userdata('login')) {
            redirect('proyek');
        }
        $this->load->view('auth/register');
    }

    /**
     * Proses register (POST).
     * Setara: pekan8/register.php (bagian if isset($_POST['register']))
     */
    public function do_register()
    {
        $username  = $this->input->post('username', TRUE);
        $password  = $this->input->post('password');
        $confirm   = $this->input->post('confirm_password');

        $hasil = $this->Auth_model->register($username, $password, $confirm);

        if ($hasil === 'sukses') {
            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
            redirect('auth');
        } else {
            $this->session->set_flashdata('error', $hasil);
            redirect('auth/register');
        }
    }

    /**
     * Logout — hapus semua session.
     * Setara: pekan8/login.php href="login.php" (logout manual)
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
