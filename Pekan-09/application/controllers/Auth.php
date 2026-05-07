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
        if ($this->is_logged_in()) {
            redirect('dashboard');
        }

        $this->login();
    }

    public function login()
    {
        if ($this->is_logged_in()) {
            redirect('dashboard');
        }

        $data = array(
            'error' => '',
            'success' => $this->session->flashdata('success')
        );

        if ($this->request_method() === 'POST') {
            $email = trim($this->input->post('email', TRUE));
            $password = (string) $this->input->post('password');

            $user = $this->User_model->get_by_email($email);

            if ($user && password_verify($password, $user['password'])) {
                $this->session->set_userdata(array(
                    'logged_in' => TRUE,
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ));

                redirect('dashboard');
            }

            $data['error'] = 'Email atau password salah!';
        }

        $this->load->view('login', $data);
    }

    public function register()
    {
        if ($this->is_logged_in()) {
            redirect('dashboard');
        }

        $data = array('error' => '');

        if ($this->request_method() === 'POST') {
            $name = trim($this->input->post('name', TRUE));
            $email = trim($this->input->post('email', TRUE));
            $password = (string) $this->input->post('password');
            $confirm_password = (string) $this->input->post('confirm_password');

            if ($name === '' || $email === '' || $password === '' || $confirm_password === '') {
                $data['error'] = 'Semua field wajib diisi!';
            } elseif ($password !== $confirm_password) {
                $data['error'] = 'Konfirmasi password tidak cocok!';
            } elseif (strlen($password) < 6) {
                $data['error'] = 'Password minimal 6 karakter!';
            } elseif ($this->User_model->get_by_email($email)) {
                $data['error'] = 'Email sudah terdaftar atau sistem error!';
            } else {
                $this->User_model->insert(array(
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => 'user',
                    'photo' => NULL
                ));

                $this->session->set_flashdata('success', 'Pendaftaran akun ' . $name . ' berhasil! Silakan login.');
                redirect('auth/login');
            }
        }

        $this->load->view('register', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
