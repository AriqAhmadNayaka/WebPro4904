<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form'));
    }

    // =====================
    // LOGIN
    // =====================
    public function index()
    {
        // Kalau sudah login, redirect ke dashboard
        if ($this->session->userdata('username')) {
            redirect('dashboard');
        }

        // Ambil username dari cookie jika ada
        $data['username_cookie'] = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
        $data['error'] = '';

        if ($this->input->post('login')) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember');

            // Cek login ke database
            $user = $this->User_model->cek_login($username, $password);

            if ($user) {
                // Set session username
                $this->session->set_userdata('username', $username);

                // Jika remember me dicentang, set cookie 7 hari
                if ($remember) {
                    setcookie("username", $username, time() + (86400 * 7), "/");
                }

                $this->session->set_flashdata('success', 'Login berhasil!');
                redirect('dashboard');
            } else {
                $data['error'] = 'Username atau password salah!';
            }
        }

        $this->load->view('auth/login', $data);
    }

    // =====================
    // LOGOUT
    // =====================
    public function logout()
    {
        // Hapus session
        $this->session->sess_destroy();
        // Hapus cookie
        setcookie("username", "", time() - 3600, "/");
        redirect('auth');
    }

    // =====================
    // REGISTER
    // =====================
    public function register()
    {
        $data['message'] = '';

        if ($this->input->post('submit')) {
            $username = $this->input->post('username');

            // Cek username sudah ada atau belum
            if ($this->User_model->cek_username($username)) {
                $data['message'] = 'Username sudah digunakan!';
            } else {
                $user_data = array(
                    'name'     => $this->input->post('name'),
                    'email'    => $this->input->post('email'),
                    'username' => $username,
                    'password' => $this->input->post('password'),
                    'telepon'  => $this->input->post('telepon'),
                    'alamat'   => $this->input->post('alamat'),
                );

                if ($this->User_model->register($user_data)) {
                    $data['message'] = 'Registrasi berhasil!';
                } else {
                    $data['message'] = 'Terjadi error!';
                }
            }
        }

        $this->load->view('auth/register', $data);
    }
}
