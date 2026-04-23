<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek login — kalau belum, redirect ke login
        if (!$this->session->userdata('user_login')) {
            redirect('login');
        }
        $this->load->model('Wishlist_model');
        $this->load->helper('url');
    }

    public function index() {
        $data['title']         = 'Dashboard - WeBandoo+';
        $data['user']          = $this->session->userdata('user_login');
        $data['total_wishlist'] = $this->Wishlist_model->hitung_semua();
        $data['wishlists']     = $this->Wishlist_model->ambil_semua();
        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layouts/footer');
    }
}
