<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('login_status')) {
            redirect('Dashboard');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run()) {
                $user = $this->User_model->get_by_username($this->input->post('username', TRUE));
                $input_password = $this->input->post('password');
                $is_hashed_password = $user && !empty($user->password) && strpos($user->password, '$2y$') === 0;
                $is_valid_password = $user && (
                    ($is_hashed_password && password_verify($input_password, $user->password)) ||
                    (!$is_hashed_password && hash_equals($user->password, $input_password))
                );

                if ($is_valid_password) {
                    if (!$is_hashed_password) {
                        $this->User_model->update_password($user->id, password_hash($input_password, PASSWORD_DEFAULT));
                    }

                    $this->session->set_userdata(array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'nama_lengkap' => $user->nama_lengkap,
                        'login_status' => TRUE,
                    ));

                    redirect('Dashboard');
                }

                $this->session->set_flashdata('error', 'Username atau password tidak valid.');
                redirect('login');
            }
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
