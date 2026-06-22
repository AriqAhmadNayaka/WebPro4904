<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waste_report_model extends CI_Model
{
	public function all_by_user($user_id)
	{
		// Mengambil semua laporan limbah milik mitra yang sedang login.
		return $this->db
			->where('user_id', $user_id)
			->order_by('report_date', 'DESC')
			->order_by('created_at', 'DESC')
			->get('waste_reports')
			->result();
	}

	public function find_owned($id, $user_id)
	{
		// Memastikan laporan limbah yang dicari benar-benar milik mitra login.
		return $this->db
			->where('id', $id)
			->where('user_id', $user_id)
			->get('waste_reports')
			->row();
	}

	public function create($data)
	{
		// Menambahkan laporan limbah baru ke database.
		$this->db->insert('waste_reports', $data);
		return $this->db->insert_id();
	}

	public function update($id, $user_id, $data)
	{
		// Mengubah laporan berdasarkan ID, tetapi hanya jika milik mitra login.
		return $this->db
			->where('id', $id)
			->where('user_id', $user_id)
			->update('waste_reports', $data);
	}

	public function delete($id, $user_id)
	{
		// Menghapus laporan berdasarkan ID, tetapi hanya jika milik mitra login.
		return $this->db
			->where('id', $id)
			->where('user_id', $user_id)
			->delete('waste_reports');
	}
}
