<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller { // Pengganti dashboard.php

    public function __construct() {
        parent::__construct(); // Panggil constructor CI_Controller
        $this->load->library('session'); // Load library session untuk manajemen login
        $this->load->helper('url'); // Load helper url untuk redirect

        // Pengganti: if (!isset($_SESSION['login'])) { header("Location: login.php"); }
        if (!$this->session->userdata('login')) {
            redirect('auth');
        }
    }

    public function index() {
        $data['nama_dokter']  = $this->session->userdata('name'); // Ambil nama dari session untuk ditampilkan di dashboard
        $data['profile_pic']  = base_url('assets/img/calm pfp.jpg'); // Gambar profil statis untuk contoh
        $data['ilustrasi']    = base_url('assets/img/doctorilustration.png'); // Gambar ilustrasi statis untuk contoh

        $this->load->view('dashboard/index', $data);
    }
}