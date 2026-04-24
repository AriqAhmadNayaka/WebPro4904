<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Post_model');
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            redirect('posts');
            return;
        }

        $data['title'] = 'Login';
        $this->load->view('auth/login', $data);
    }

    public function register()
    {
        if ($this->session->userdata('login')) {
            redirect('posts');
            return;
        }

        $data['title'] = 'Sign Up';
        $this->load->view('auth/register', $data);
    }

    public function login()
    {
        if ($this->session->userdata('login')) {
            redirect('posts');
            return;
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('login');
            return;
        }

        $email = trim($this->input->post('email', TRUE));
        $password = $this->input->post('password');
        $user = $this->Post_model->get_by_email($email);

        if ($user && password_verify($password, $user->password)) {
            $this->session->set_userdata(array(
                'login' => TRUE,
                'user_id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email
            ));

            redirect('posts');
            return;
        }

        $this->session->set_flashdata('error', 'Email atau Password salah!');
        redirect('login');
    }

    public function store_register()
    {
        if ($this->session->userdata('login')) {
            redirect('posts');
            return;
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('register');
            return;
        }

        $nama = $this->input->post('nama', TRUE);
        $email = trim($this->input->post('email', TRUE));
        $password = $this->input->post('password');

        if ($this->Post_model->get_by_email($email)) {
            $this->session->set_flashdata('error', 'Email sudah digunakan! Gunakan email lain.');
            redirect('register');
            return;
        }

        $saved = $this->Post_model->insert(array(
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'foto' => 'default.png'
        ));

        if ($saved) {
            $this->session->set_flashdata('success', 'Pendaftaran berhasil! Silakan login.');
            redirect('login');
            return;
        }

        $error_message = $this->Post_model->get_last_error();
        $this->session->set_flashdata('error', $error_message ? 'Pendaftaran gagal. ' . $error_message : 'Pendaftaran gagal.');
        redirect('register');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
