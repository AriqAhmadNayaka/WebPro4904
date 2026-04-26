<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warga_model extends CI_Model
{
    // Nama tabel dibikin satu tempat biar gampang kalau nanti mau diganti.
    private $table = 'warga';

    public function get_all()
    {
        // Ambil semua data warga, yang terbaru ditampilin duluan.
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        // Ambil satu data warga berdasarkan id.
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        // Simpan data warga baru ke tabel.
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        // Update data warga yang id-nya dipilih.
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        // Hapus data warga berdasarkan id.
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function count_all()
    {
        // Dipakai buat angka ringkas di dashboard.
        return $this->db->count_all($this->table);
    }
}
