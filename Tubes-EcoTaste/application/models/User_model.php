<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	public function find_by_email($email)
	{
		// Mencari user aktif berdasarkan email untuk proses login.
		return $this->db
			->where('email', $email)
			->where('is_active', 1)
			->get('users')
			->row();
	}

	public function create($data)
	{
		// Menyimpan user baru dari proses sign up konsumen.
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}
}
