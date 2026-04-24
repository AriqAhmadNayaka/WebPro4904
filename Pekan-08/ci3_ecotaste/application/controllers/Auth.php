<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        if ($this->session->userdata('is_logged_in')) {
            redirect('dashboard');
        }

        $data['title'] = 'Login EcoTaste';

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');

            if ($this->form_validation->run()) {
                $user = $this->User_model->login(
                    $this->input->post('username', TRUE),
                    $this->input->post('password', TRUE)
                );

                if ($user) {
                    $this->session->set_userdata(array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'nama_lengkap' => $user->nama_lengkap,
                        'is_logged_in' => TRUE
                    ));

                    redirect('dashboard');
                }

                $this->session->set_flashdata('error', 'Username atau password salah.');
                redirect('login');
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('auth/login');
        $this->load->view('templates/footer');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
