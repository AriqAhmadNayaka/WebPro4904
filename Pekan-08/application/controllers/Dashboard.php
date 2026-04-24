<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // inisialisasi koneksi database ke class
        $this->load->database();
        // load helper url
        $this->load->helper(array('url'));
        // load library session
        $this->load->library('session');

        // ambil session dari cookie jika belum ada (checkCookie dari class Session native)
        if ($this->input->cookie('username') && !$this->session->userdata('username')) {
            // set session dari cookie yang tersimpan
            $this->session->set_userdata('username', $this->input->cookie('username'));
        }

        // cek apakah user sudah login (checkLogin dari class Session native)
        if (!$this->session->userdata('username')) {
            redirect('auth'); // redirect ke halaman login
        }
    }

    // HALAMAN DASHBOARD
    // menggantikan konten utama dashboard.php native
    public function index()
    {
        // mengambil username dari session (getUser dari class Session native)
        $data['username'] = $this->session->userdata('username'); // untuk mengambil username

        $this->load->view('dashboard/index', $data); // tampilkan halaman dashboard
    }
}