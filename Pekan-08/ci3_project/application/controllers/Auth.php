<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form'));
    }

    // Redirect ke dashboard kalau sudah login
    public function index() {
        if ($this->session->userdata('user_login')) {
            redirect('dashboard');
        }
        redirect('login');
    }

    // Halaman login
    public function login() {
        if ($this->session->userdata('user_login')) {
            redirect('dashboard');
        }
        $data['title'] = 'Login - WeBandoo+';
        $this->load->view('auth/login', $data);
    }

    // Proses login
    public function proses_login() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Email dan password wajib diisi.');
            redirect('login');
        }

        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->User_model->cek_login($email);

        if ($user && password_verify($password, $user->password)) {
            $this->session->set_userdata('user_login', $user->nama);
            $this->session->set_userdata('user_id',    $user->id);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Email atau password salah!');
            redirect('login');
        }
    }

    // Halaman register
    public function register() {
        if ($this->session->userdata('user_login')) {
            redirect('dashboard');
        }
        $data['title'] = 'Daftar - WeBandoo+';
        $this->load->view('auth/register', $data);
    }

    // Proses register
    public function proses_register() {
        $this->form_validation->set_rules('nama',     'Nama',     'required');
        $this->form_validation->set_rules('email',    'Email',    'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('register');
        }

        $data = array(
            'nama'     => $this->input->post('nama'),
            'email'    => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        );

        $this->User_model->register($data);
        $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
        redirect('login');
    }

    // Logout
    public function logout() {
        $this->session->unset_userdata('user_login');
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
        redirect('login');
    }
}
