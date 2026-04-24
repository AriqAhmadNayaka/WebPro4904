<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    private $table = 'laporan';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua data milik user, urut terbaru
    public function get_all($username)
    {
        $this->db->where('username', $username);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    // Ambil satu data berdasarkan id
    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->row_array();
    }

    // Insert data baru
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Update data berdasarkan id milik user tertentu
    public function update($id, $data, $username)
    {
        $this->db->where('id', $id);
        $this->db->where('username', $username);
        return $this->db->update($this->table, $data);
    }

    // Hapus data berdasarkan id milik user tertentu
    public function delete($id, $username)
    {
        $this->db->where('id', $id);
        $this->db->where('username', $username);
        return $this->db->delete($this->table);
    }
}
