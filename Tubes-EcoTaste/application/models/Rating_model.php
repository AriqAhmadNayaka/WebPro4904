<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rating_model extends CI_Model
{
	public function all_by_user($user_id)
	{
		// Mengambil semua rating milik user yang sedang login.
		return $this->db
			->where('user_id', $user_id)
			->order_by('created_at', 'DESC')
			->get('restaurant_ratings')
			->result();
	}

	public function find_owned($id, $user_id)
	{
		// Memastikan data rating yang dicari benar-benar milik user login.
		return $this->db
			->where('id', $id)
			->where('user_id', $user_id)
			->get('restaurant_ratings')
			->row();
	}

	public function create($data)
	{
		// Menambahkan rating restoran baru ke database.
		$this->db->insert('restaurant_ratings', $data);
		return $this->db->insert_id();
	}

	public function update($id, $user_id, $data)
	{
		// Mengubah rating berdasarkan ID, tetapi hanya jika milik user login.
		return $this->db
			->where('id', $id)
			->where('user_id', $user_id)
			->update('restaurant_ratings', $data);
	}

	public function delete($id, $user_id)
	{
		// Menghapus rating berdasarkan ID, tetapi hanya jika milik user login.
		return $this->db
			->where('id', $id)
			->where('user_id', $user_id)
			->delete('restaurant_ratings');
	}
}
