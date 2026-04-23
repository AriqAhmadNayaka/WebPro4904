<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/User_model', 'user_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $username = trim((string) $this->input->post('username', TRUE));
            $password = trim((string) $this->input->post('password', TRUE));

            if ($username === '' || $password === '') {
                $this->session->set_flashdata('message', array(
                    'type' => 'error',
                    'text' => 'Username dan password wajib diisi.',
                ));
                redirect('login');
            }

            $user = $this->user_model->verify_credentials($username, $password);

            if ($user) {
                $this->session->set_userdata(array(
                    'logged_in' => TRUE,
                    'user_id' => (int) $user->id,
                    'username' => $user->username,
                ));

                $this->session->set_flashdata('message', array(
                    'type' => 'success',
                    'text' => 'Login berhasil. Selamat datang di dashboard.',
                ));
                redirect('dashboard');
            }

            $this->session->set_flashdata('message', array(
                'type' => 'error',
                'text' => 'Login gagal. Username atau password pada tabel users tidak cocok.',
            ));
            redirect('login');
        }

        $data['message'] = $this->session->flashdata('message');
        $this->load->view('auth/login', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function register()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $nama = trim((string) $this->input->post('nama', TRUE));
            $email = trim((string) $this->input->post('email', TRUE));
            $username = trim((string) $this->input->post('username', TRUE));
            $password = (string) $this->input->post('password');
            $telepon = trim((string) $this->input->post('telepon', TRUE));
            $alamat = trim((string) $this->input->post('alamat', TRUE));

            if ($nama === '' || $email === '' || $username === '' || $password === '' || $telepon === '' || $alamat === '') {
                $this->session->set_flashdata('message', array(
                    'type' => 'error',
                    'text' => 'Semua field pendaftaran wajib diisi.',
                ));
                redirect('register');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('message', array(
                    'type' => 'error',
                    'text' => 'Format email tidak valid.',
                ));
                redirect('register');
            }

            if (strlen($password) < 6) {
                $this->session->set_flashdata('message', array(
                    'type' => 'error',
                    'text' => 'Password minimal 6 karakter.',
                ));
                redirect('register');
            }

            $this->user_model->create(array(
                'nama' => $nama,
                'email' => $email,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'telepon' => $telepon,
                'alamat' => $alamat,
            ));

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('message', array(
                    'type' => 'success',
                    'text' => 'Registrasi berhasil. Silakan login dengan akun yang baru dibuat.',
                ));
                redirect('login');
            }

            $this->session->set_flashdata('message', array(
                'type' => 'error',
                'text' => 'Registrasi gagal disimpan ke database. Cek struktur tabel users.',
            ));
            redirect('register');
        }

        $data['message'] = $this->session->flashdata('message');
        $this->load->view('auth/register', $data);
    }
}
