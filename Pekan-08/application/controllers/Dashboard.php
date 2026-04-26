<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Dashboard cuma boleh dibuka kalau user sudah login.
        $this->auth_check();
        $this->load->model('Warga_model');
    }

    public function index()
    {
        // Siapin data yang mau ditampilin di halaman dashboard.
        $data = array(
            'title' => 'Dashboard',
            'total_warga' => $this->Warga_model->count_all(),
            'username' => $this->session->userdata('nama_lengkap')
                ? $this->session->userdata('nama_lengkap')
                : $this->session->userdata('username')
        );

        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layouts/footer');
    }

    private function auth_check()
    {
        // Kalau session login nggak ada, lempar balik ke auth.
        if (!$this->session->userdata('login')) {
            redirect('auth');
        }
    }
}
