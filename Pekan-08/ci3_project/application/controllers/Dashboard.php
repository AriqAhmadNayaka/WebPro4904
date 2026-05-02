<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // cek dulu apakah user sudah login
        if (!$this->session->userdata('user_login')) {
            redirect('login');
        }

        // load model wishlist
        $this->load->model('Wishlist_model');
        $this->load->helper('url');
    }

    public function index() {
        // data yang dikirim ke view
        $data['title'] = 'Dashboard - WeBandoo+';
        $data['user']  = $this->session->userdata('user_login');

        // ambil total data wishlist
        $data['total_wishlist'] = $this->Wishlist_model->hitung_semua();

        // ambil semua data wishlist
        $data['wishlists'] = $this->Wishlist_model->ambil_semua();

        // load tampilan
        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layouts/footer');
    }
}