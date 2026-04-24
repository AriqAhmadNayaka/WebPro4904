<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Cek cookie → set session jika ada
        if (isset($_COOKIE['username']) && !$this->session->userdata('username')) {
            $this->session->set_userdata('username', $_COOKIE['username']);
        }

        // Cek login — jika belum login, redirect ke auth
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['username'] = $this->session->userdata('username');
        $this->load->view('dashboard/index', $data);
    }
}
