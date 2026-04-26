<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Model ini dipakai buat nyocokin username dan password ke database.
        $this->load->model('User_model');
    }

    public function index()
    {
        // Kalau user sudah login, nggak usah balik ke form login lagi.
        if ($this->session->userdata('login')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    public function login()
    {
        // Method ini cuma buat nerima submit dari form login.
        if ($this->input->method() !== 'post') {
            redirect('auth');
        }

        // Input dirapihin dulu sebelum dicek ke database.
        $username = trim($this->input->post('username', TRUE));
        $password = trim($this->input->post('password', TRUE));
        $user = $this->User_model->check_login($username, $password);

        if ($user) {
            // Kalau cocok, simpan data penting user ke session.
            $this->session->set_userdata(array(
                'login' => TRUE,
                'user_id' => $user->id,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap
            ));
            redirect('dashboard');
        }

        $this->session->set_flashdata('error', 'Login gagal. Username atau password salah.');
        redirect('auth');
    }

    public function logout()
    {
        // Session dibersihin semua biar user benar-benar keluar.
        $this->session->sess_destroy();
        redirect('auth');
    }
}
