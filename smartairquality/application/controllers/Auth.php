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
        if ($this->session->userdata('masuk')) {
            redirect('posts');
            return;
        }

        $data['title'] = 'Masuk';
        $this->load->view('auth/masuk', $data);
    }

    public function register()
    {
        if ($this->session->userdata('masuk')) {
            redirect('posts');
            return;
        }

        $data['title'] = 'Daftar';
        $this->load->view('auth/daftar', $data);
    }

    public function masuk()
    {
        if ($this->session->userdata('masuk')) {
            redirect('posts');
            return;
        }

        $this->form_validation->set_rules('id', 'id_user', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('masuk');
            return;
        }

        $email = trim($this->input->post('email', TRUE));
        $password = $this->input->post('password');
        $user = $this->Post_model->get_by_email($email);

        if ($user && password_verify($password, $user->password)) {
            $this->session->set_userdata(array(
                'masuk' => TRUE,
                'user_id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email
            ));

            redirect('posts');
            return;
        }

        $this->session->set_flashdata('error', 'Email atau Password salah!');
        redirect('masuk');
    }

    public function store_register()
    {
        if ($this->session->userdata('masuk')) {
            redirect('posts');
            return;
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('daftar');
            return;
        }

        $nama = $this->input->post('nama', TRUE);
        $email = trim($this->input->post('email', TRUE));
        $password = $this->input->post('password');

        if ($this->Post_model->get_by_email($email)) {
            $this->session->set_flashdata('error', 'Email sudah digunakan! Gunakan email lain.');
            redirect('daftar');
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
            redirect('masuk');
            return;
        }

        $error_message = $this->Post_model->get_last_error();
        $this->session->set_flashdata('error', $error_message ? 'Pendaftaran gagal. ' . $error_message : 'Pendaftaran gagal.');
        redirect('daftar');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('masuk');
    }
}
