<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller dashboard hanya bisa diakses oleh user yang sudah login.
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Pastikan akses dashboard terlindungi oleh session login.
        $this->require_login();
        $this->load->model('Participant_model');
    }

    public function index()
    {
        // Ambil data peserta dan statistik untuk ditampilkan di dashboard.
        $participants = $this->Participant_model->get_all();
        $stats = $this->Participant_model->get_stats();

        $data = array(
            'title' => 'Dashboard | InkluSkill',
            'participants' => $participants,
            'stats' => $stats,
        );

        $this->load->view('dashboard/index', $data);
    }

    private function require_login()
    {
        // Redirect ke halaman login jika session user tidak ditemukan.
        if (!$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }
    }
}
