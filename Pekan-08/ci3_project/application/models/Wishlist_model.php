<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist_model extends CI_Model {

    private $tabel = 'wishlist_wisata';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua data wishlist
    public function ambil_semua() {
        return $this->db->get($this->tabel)->result();
    }

    // Ambil data berdasarkan ID
    public function ambil_by_id($id) {
        $query = $this->db->get_where($this->tabel, array('id' => $id));
        return $query->row();
    }

    // Hitung total data (untuk dashboard)
    public function hitung_semua() {
        return $this->db->count_all($this->tabel);
    }

    // Tambah data baru (CREATE)
    public function tambah($data) {
        return $this->db->insert($this->tabel, $data);
    }

    // Perbarui data (UPDATE)
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    // Hapus data (DELETE)
    public function hapus($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }
}
