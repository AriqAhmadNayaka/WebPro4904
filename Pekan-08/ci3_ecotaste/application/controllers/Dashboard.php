<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->load->model('Menu_model');
    }

    public function index()
    {
        $data['title'] = 'Dashboard EcoTaste';
        $data['total_menu'] = $this->Menu_model->count_all();
        $data['menus'] = $this->Menu_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }

    private function _cek_login()
    {
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }
    }
}
