<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper('url');
    }

    public function index()
    {
        if ($this->session->userdata('login_status')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {

            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {

                $username = $this->input->post('username', TRUE);
                $password = $this->input->post('password', TRUE);

                $user = $this->User_model->get_by_username($username);

                if ($user && $password == $user->password) {

                    $this->session->set_userdata([
                        'user_id' => $user->id,
                        'username' => $user->nama,
                        'login_status' => TRUE
                    ]);

                    redirect('dashboard');
                }

                $this->session->set_flashdata('error', 'Username atau Password salah');
                redirect('auth');
            }
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}