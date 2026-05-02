<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // load model user + library yang dipakai
        $this->load->model('User_model');
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form'));
    }

    // kalau sudah login langsung ke dashboard
    public function index() {
        if ($this->session->userdata('user_login')) {
            redirect('dashboard');
        }
        redirect('login');
    }

    // tampil halaman login
    public function login() {
        if ($this->session->userdata('user_login')) {
            redirect('dashboard');
        }
        $data['title'] = 'Login - WeBandoo+';
        $this->load->view('auth/login', $data);
    }

    // proses login (cek email & password)
    public function proses_login() {
        // validasi input
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        // kalau gagal validasi
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Email dan password wajib diisi.');
            redirect('login');
        }

        // ambil input
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        // cek ke database
        $user = $this->User_model->cek_login($email);

        // kalau user ada & password cocok
        if ($user && password_verify($password, $user->password)) {
            // simpan session login
            $this->session->set_userdata('user_login', $user->nama);
            $this->session->set_userdata('user_id', $user->id);
            redirect('dashboard');
        } else {
            // kalau salah
            $this->session->set_flashdata('error', 'Email atau password salah!');
            redirect('login');
        }
    }

    // tampil halaman register
    public function register() {
        if ($this->session->userdata('user_login')) {
            redirect('dashboard');
        }
        $data['title'] = 'Daftar - WeBandoo+';
        $this->load->view('auth/register', $data);
    }

    // proses register user baru
    public function proses_register() {
        // validasi input
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        // kalau gagal
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('register');
        }

        // data yang disimpan
        $data = array(
            'nama'     => $this->input->post('nama'),
            'email'    => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT), // enkripsi password
        );

        // simpan ke database
        $this->User_model->register($data);

        $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
        redirect('login');
    }

    // logout user
    public function logout() {
        // hapus session
        $this->session->unset_userdata('user_login');
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
        redirect('login');
    }
}