<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CONTROLLER: Dashboard
 * Modul: application/modules/dashboard/controllers/Dashboard.php
 *
 * Halaman ringkasan setelah login.
 * (Tidak ada padanannya di pekan8 — merupakan tambahan CI3 MVC)
 */
class Dashboard extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login')) {
            redirect('auth');
        }
        $this->load->helper('url');
    }

    public function index()
    {
        // Hitung jumlah proyek
        $total_proyek = $this->db->count_all('proyek');
        $total_users  = $this->db->count_all('users');

        $data = [
            'page_title'    => 'Dashboard',
            'page_subtitle' => 'Selamat datang, ' . $this->session->userdata('username') . '!',
            'breadcrumb'    => 'Dashboard',
            'total_proyek'  => $total_proyek,
            'total_users'   => $total_users,
        ];
        $this->load->view('dashboard/index', $data);
    }
}
