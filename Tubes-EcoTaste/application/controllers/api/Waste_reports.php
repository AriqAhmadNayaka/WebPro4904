<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waste_reports extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// API laporan limbah memakai model Waste_report_model dan hanya untuk mitra.
		$this->load->model('Waste_report_model');
		$this->_require_role('mitra');
	}

	public function index()
	{
		// Membaca HTTP method untuk membedakan aksi RESTful.
		$method = $this->input->method(TRUE);

		if ($method === 'GET') {
			// GET /api/waste-reports mengambil semua laporan milik mitra login.
			return $this->_json($this->Waste_report_model->all_by_user($this->session->userdata('user_id')));
		}

		if ($method === 'POST') {
			// POST /api/waste-reports membuat laporan baru.
			return $this->_create();
		}

		return $this->_json(array('message' => 'Method not allowed'), 405);
	}

	public function item($id)
	{
		// Method ini menangani satu data laporan berdasarkan ID.
		$method = $this->input->method(TRUE);

		if ($method === 'PUT' || $method === 'PATCH') {
			// PUT/PATCH /api/waste-reports/{id} mengubah laporan.
			return $this->_update($id);
		}

		if ($method === 'DELETE') {
			// DELETE /api/waste-reports/{id} menghapus laporan.
			return $this->_delete($id);
		}

		return $this->_json(array('message' => 'Method not allowed'), 405);
	}

	private function _create()
	{
		// Mengambil payload, membersihkan data, lalu menambahkan user_id mitra login.
		$data = $this->_sanitize($this->_payload());
		$data['user_id'] = $this->session->userdata('user_id');

		if (!$this->_valid($data)) {
			// Jika data wajib belum lengkap, kirim status 422.
			return $this->_json(array('message' => 'Tanggal, jenis limbah, berat, dan metode pembuangan wajib diisi.'), 422);
		}

		// Simpan laporan melalui model dan balas JSON.
		$id = $this->Waste_report_model->create($data);
		return $this->_json(array('message' => 'Laporan berhasil ditambahkan.', 'id' => $id), 201);
	}

	private function _update($id)
	{
		// Pastikan laporan yang diedit ada dan milik mitra login.
		$user_id = $this->session->userdata('user_id');
		if (!$this->Waste_report_model->find_owned($id, $user_id)) {
			return $this->_json(array('message' => 'Data tidak ditemukan.'), 404);
		}

		// Bersihkan dan validasi payload sebelum update.
		$data = $this->_sanitize($this->_payload());
		if (!$this->_valid($data)) {
			return $this->_json(array('message' => 'Tanggal, jenis limbah, berat, dan metode pembuangan wajib diisi.'), 422);
		}

		$this->Waste_report_model->update($id, $user_id, $data);
		return $this->_json(array('message' => 'Laporan berhasil diperbarui.'));
	}

	private function _delete($id)
	{
		// Pastikan laporan yang dihapus ada dan milik mitra login.
		$user_id = $this->session->userdata('user_id');
		if (!$this->Waste_report_model->find_owned($id, $user_id)) {
			return $this->_json(array('message' => 'Data tidak ditemukan.'), 404);
		}

		$this->Waste_report_model->delete($id, $user_id);
		return $this->_json(array('message' => 'Laporan berhasil dihapus.'));
	}

	private function _sanitize($payload)
	{
		// Mengambil hanya field yang dibutuhkan dari request.
		return array(
			'report_date' => trim($payload['report_date'] ?? ''),
			'waste_type' => trim($payload['waste_type'] ?? ''),
			'weight_kg' => (float) ($payload['weight_kg'] ?? 0),
			'disposal_method' => trim($payload['disposal_method'] ?? ''),
			'notes' => trim($payload['notes'] ?? '')
		);
	}

	private function _valid($data)
	{
		// Validasi sederhana untuk memastikan field wajib sudah benar.
		return $data['report_date'] !== '' && $data['waste_type'] !== '' && $data['weight_kg'] > 0 && $data['disposal_method'] !== '';
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
		// API dilindungi session, jadi user wajib login sebagai mitra.
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
