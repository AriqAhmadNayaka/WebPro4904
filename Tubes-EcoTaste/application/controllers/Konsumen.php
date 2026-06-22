<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konsumen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Semua method di controller ini hanya boleh diakses role konsumen.
		$this->_require_role('konsumen');
	}

	public function rating()
	{
		// Data ini dikirim ke layout untuk judul halaman dan menu aktif.
		$data = array(
			'title' => 'Rating Restoran',
			'active' => 'rating'
		);

		// Memecah tampilan menjadi layout header, navigasi, konten, dan footer.
		$this->load->view('layouts/header', $data);
		$this->load->view('layouts/sidebar', $data);
		$this->load->view('konsumen/rating');
		$this->load->view('layouts/footer');
	}

	public function beranda()
	{
		// Halaman awal konsumen setelah login.
		$data = array(
			'title' => 'Beranda',
			'active' => 'beranda'
		);

		$this->load->view('konsumen/beranda', $data);
	}

	private function _require_role($role)
	{
		// Proteksi halaman: user wajib login dan role session harus konsumen.
		if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== $role) {
			$this->session->set_flashdata('error', 'Silakan login sesuai role untuk mengakses halaman.');
			redirect('');
		}
	}
}
