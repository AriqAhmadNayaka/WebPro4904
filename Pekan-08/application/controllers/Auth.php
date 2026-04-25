<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->load->view('login');
    }

    public function register()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->method() !== 'post') {
            $this->load->view('register');
            return;
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|trim|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('register');
            return;
        }

        if (!$this->User_model->users_table_ready()) {
            $this->session->set_flashdata('error', 'Sistem login belum siap digunakan. Silakan selesaikan setup database terlebih dahulu.');
            redirect('auth/register');
        }

        $username = $this->input->post('username', TRUE);
        if ($this->User_model->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah terdaftar.');
            redirect('auth/register');
        }

        $saved = $this->User_model->register(array(
            'nama' => $this->input->post('nama', TRUE),
            'username' => $username,
            'password' => $this->input->post('password', TRUE),
        ));

        if (!$saved) {
            $this->session->set_flashdata('error', 'Registrasi gagal. Periksa database db_ci3 dan tabel users.');
            redirect('auth/register');
        }

        $this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login.');
        redirect('auth');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('login');
            return;
        }

        $user = $this->User_model->check_login(
            $this->input->post('username', TRUE),
            $this->input->post('password', TRUE)
        );

        if (!$user) {
            $message = 'Username atau password salah.';
            if (!$this->User_model->users_table_ready()) {
                $message = 'Sistem login belum siap digunakan. Silakan selesaikan setup database terlebih dahulu.';
            }

            $this->session->set_flashdata('error', $message);
            redirect('auth');
        }

        $this->session->set_userdata(array(
            'user_id' => $user->id,
            'username' => $user->username,
            'nama' => isset($user->nama) ? $user->nama : $user->username,
            'logged_in' => TRUE,
        ));

        redirect('dashboard');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
