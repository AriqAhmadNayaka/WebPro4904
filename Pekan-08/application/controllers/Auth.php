<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    public function login() {
        if ($this->session->userdata('user_id')) {
            redirect('datauser');
        }

        if ($this->input->post()) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->User_model->login($email, $password);

            if ($user) {
                $this->session->set_userdata([
                    'user_id' => $user['id'],
                    'email'   => $user['email']
                ]);
                redirect('datauser');
            } else {
                $this->session->set_flashdata('error', 'Email atau password salah!');
                redirect('auth/login');
            }
        }

        $this->load->view('auth/login');
    }

    public function register() {
        if ($this->input->post()) {
            $email = trim($this->input->post('email'));
            $password = $this->input->post('password');

            if ($this->User_model->register($email, $password)) {
                $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Registrasi gagal. Email mungkin sudah digunakan.');
            }
        }
        $this->load->view('auth/register');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}