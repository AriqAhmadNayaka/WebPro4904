<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mitra extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Semua method di controller ini hanya boleh diakses role mitra.
		$this->_require_role('mitra');
	}

	public function limbah()
	{
		// Data dikirim ke layout untuk judul halaman dan menu aktif.
		$data = array(
			'title' => 'Pelaporan Limbah',
			'active' => 'limbah'
		);

		// Memecah tampilan menjadi layout header, navigasi, konten, dan footer.
		$this->load->view('layouts/header', $data);
		$this->load->view('layouts/sidebar', $data);
		$this->load->view('mitra/limbah');
		$this->load->view('layouts/footer');
	}

	private function _require_role($role)
	{
		// Proteksi halaman: user wajib login dan role session harus mitra.
		if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== $role) {
			$this->session->set_flashdata('error', 'Silakan login sesuai role untuk mengakses halaman.');
			redirect('');
		}
	}
}
