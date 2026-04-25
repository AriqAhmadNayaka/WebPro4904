<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layanan_model extends CI_Model {

    private $table = 'informasi_medis';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua data (pengganti tampilSemua())
    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    // Ambil satu data berdasarkan ID (pengganti ambilSatu())
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    // Simpan data baru (pengganti bagian INSERT di simpan())
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update data (pengganti bagian UPDATE di simpan())
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Hapus data (pengganti hapus())
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}