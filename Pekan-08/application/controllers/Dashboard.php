<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
    }

    private function require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['username'] = $this->session->userdata('username');
        $data['nama'] = $this->session->userdata('nama');

        $this->load->view('dashboard', $data);
    }
}
