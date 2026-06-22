<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ratings extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// API rating memakai model Rating_model dan hanya boleh diakses konsumen.
		$this->load->model('Rating_model');
		$this->_require_role('konsumen');
	}

	public function index()
	{
		// Membaca HTTP method untuk membedakan aksi RESTful.
		$method = $this->input->method(TRUE);

		if ($method === 'GET') {
			// GET /api/ratings mengambil semua rating milik konsumen login.
			return $this->_json($this->Rating_model->all_by_user($this->session->userdata('user_id')));
		}

		if ($method === 'POST') {
			// POST /api/ratings membuat rating baru.
			return $this->_create();
		}

		return $this->_json(array('message' => 'Method not allowed'), 405);
	}

	public function item($id)
	{
		// Method ini menangani satu data rating berdasarkan ID.
		$method = $this->input->method(TRUE);

		if ($method === 'PUT' || $method === 'PATCH') {
			// PUT/PATCH /api/ratings/{id} mengubah rating.
			return $this->_update($id);
		}

		if ($method === 'DELETE') {
			// DELETE /api/ratings/{id} menghapus rating.
			return $this->_delete($id);
		}

		return $this->_json(array('message' => 'Method not allowed'), 405);
	}

	private function _create()
	{
		// Mengambil payload JSON/form, membersihkan data, lalu menambahkan user_id login.
		$payload = $this->_payload();
		$data = $this->_sanitize($payload);
		$data['user_id'] = $this->session->userdata('user_id');

		if (!$this->_valid($data)) {
			// Jika data tidak lengkap atau rating di luar 1-5, kirim status 422.
			return $this->_json(array('message' => 'Nama restoran, rating 1-5, dan ulasan wajib diisi.'), 422);
		}

		// Simpan data melalui model dan balas dalam format JSON.
		$id = $this->Rating_model->create($data);
		return $this->_json(array('message' => 'Rating berhasil ditambahkan.', 'id' => $id), 201);
	}

	private function _update($id)
	{
		// Pastikan data yang diedit ada dan milik konsumen login.
		$user_id = $this->session->userdata('user_id');
		if (!$this->Rating_model->find_owned($id, $user_id)) {
			return $this->_json(array('message' => 'Data tidak ditemukan.'), 404);
		}

		// Bersihkan dan validasi payload sebelum update.
		$data = $this->_sanitize($this->_payload());
		if (!$this->_valid($data)) {
			return $this->_json(array('message' => 'Nama restoran, rating 1-5, dan ulasan wajib diisi.'), 422);
		}

		$this->Rating_model->update($id, $user_id, $data);
		return $this->_json(array('message' => 'Rating berhasil diperbarui.'));
	}

	private function _delete($id)
	{
		// Pastikan data yang dihapus ada dan milik konsumen login.
		$user_id = $this->session->userdata('user_id');
		if (!$this->Rating_model->find_owned($id, $user_id)) {
			return $this->_json(array('message' => 'Data tidak ditemukan.'), 404);
		}

		$this->Rating_model->delete($id, $user_id);
		return $this->_json(array('message' => 'Rating berhasil dihapus.'));
	}

	private function _sanitize($payload)
	{
		// Mengambil hanya field yang dibutuhkan agar input lain tidak ikut tersimpan.
		return array(
			'restaurant_name' => trim($payload['restaurant_name'] ?? ''),
			'rating' => (int) ($payload['rating'] ?? 0),
			'review' => trim($payload['review'] ?? '')
		);
	}

	private function _valid($data)
	{
		// Validasi sederhana untuk memastikan data wajib terisi.
		return $data['restaurant_name'] !== '' && $data['review'] !== '' && $data['rating'] >= 1 && $data['rating'] <= 5;
	}

	private function _payload()
	{
		// Membaca data JSON dari fetch(); jika bukan JSON, gunakan POST biasa.
		$raw = $this->input->raw_input_stream;
		$json = json_decode($raw, TRUE);
		return is_array($json) ? $json : $this->input->post(NULL, TRUE);
	}

	private function _require_role($role)
	{
		// API dilindungi session, jadi user wajib login sebagai konsumen.
		if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== $role) {
			$this->_json(array('message' => 'Unauthorized'), 401);
			exit;
		}
	}

	private function _json($data, $status = 200)
	{
		// Semua response API dikirim sebagai JSON.
		return $this->output
			->set_status_header($status)
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}
}
