<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Memuat model user agar controller bisa mencari dan membuat akun.
		$this->load->model('User_model');
	}

	public function index()
	{
		// Jika user sudah login, jangan tampilkan login lagi. Arahkan sesuai role.
		if ($this->session->userdata('logged_in')) {
			return $this->_redirect_by_role($this->session->userdata('role'));
		}

		// Menampilkan halaman login sekaligus mengirim flash message jika ada.
		$this->load->view('auth/login', array(
			'error' => $this->session->flashdata('error'),
			'success' => $this->session->flashdata('success')
		));
	}

	public function login()
	{
		// Validasi input login agar email dan password wajib diisi dengan format benar.
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === FALSE) {
			// Jika validasi gagal, simpan pesan error lalu kembali ke halaman login.
			$this->session->set_flashdata('error', validation_errors(' ', ' '));
			return redirect('');
		}

		// Ambil user berdasarkan email, lalu bandingkan password dari form.
		$user = $this->User_model->find_by_email($this->input->post('email', TRUE));
		$password = $this->input->post('password', TRUE);

		if (!$user || $password !== $user->password) {
			// Login ditolak jika user tidak ada atau password tidak sama.
			$this->session->set_flashdata('error', 'Email atau password tidak sesuai.');
			return redirect('');
		}

		// Simpan identitas user ke session agar status login terbaca di halaman lain.
		$this->session->set_userdata(array(
			'user_id' => (int) $user->id,
			'name' => $user->name,
			'email' => $user->email,
			'role' => $user->role,
			'logged_in' => TRUE
		));

		// Setelah login berhasil, user diarahkan berdasarkan role.
		return $this->_redirect_by_role($user->role);
	}

	public function register()
	{
		// Jika sudah login, user tidak perlu membuka halaman register.
		if ($this->session->userdata('logged_in')) {
			return $this->_redirect_by_role($this->session->userdata('role'));
		}

		// Request GET berarti hanya menampilkan form sign up.
		if ($this->input->method(TRUE) === 'GET') {
			return $this->load->view('auth/register', array(
				'error' => $this->session->flashdata('error')
			));
		}

		// Request POST berarti form dikirim. Validasi semua field registrasi.
		$this->form_validation->set_rules('name', 'Nama', 'required|min_length[3]|max_length[120]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('password_confirm', 'Konfirmasi password', 'required|matches[password]');

		if ($this->form_validation->run() === FALSE) {
			// Jika input tidak valid, user dikembalikan ke form register.
			$this->session->set_flashdata('error', validation_errors(' ', ' '));
			return redirect('auth/register');
		}

		// Membuat akun baru khusus role konsumen.
		$this->User_model->create(array(
			'name' => $this->input->post('name', TRUE),
			'email' => $this->input->post('email', TRUE),
			'password' => $this->input->post('password', TRUE),
			'role' => 'konsumen',
			'is_active' => 1
		));

		// Setelah sign up berhasil, user diarahkan ke login untuk masuk manual.
		$this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login dengan akun konsumen Anda.');
		return redirect('');
	}

	public function logout()
	{
		// Menghapus semua data session, lalu kembali ke login.
		$this->session->sess_destroy();
		redirect('');
	}

	private function _redirect_by_role($role)
	{
		// Role mitra masuk ke halaman laporan limbah.
		if ($role === 'mitra') {
			return redirect('mitra/limbah');
		}

		// Role konsumen masuk ke beranda terlebih dahulu.
		return redirect('konsumen/beranda');
	}
}
