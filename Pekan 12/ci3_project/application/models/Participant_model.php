<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model ini menangani operasi database untuk data peserta.
class Participant_model extends CI_Model
{
    private $table = 'ci3_participants';

    public function get_all()
    {
        // Ambil seluruh peserta dan urutkan dari data terbaru.
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function find($id)
    {
        // Ambil satu data peserta berdasarkan id.
        return $this->db->get_where($this->table, array('id' => (int) $id))->row();
    }

    public function create($data)
    {
        // Tambahkan data peserta baru.
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        // Perbarui data peserta berdasarkan id.
        return $this->db->where('id', (int) $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        // Hapus data peserta berdasarkan id.
        return $this->db->where('id', (int) $id)->delete($this->table);
    }

    public function get_stats()
    {
        // Hitung ringkasan data untuk dashboard.
        $participants = $this->db->count_all($this->table);
        $female = $this->db->where('jenis_kelamin', 'Perempuan')->count_all_results($this->table);
        $male = $this->db->where('jenis_kelamin', 'Laki-laki')->count_all_results($this->table);

        return array(
            'total' => $participants,
            'female' => $female,
            'male' => $male,
            'training_count' => count($this->training_options()),
        );
    }

    public function training_options()
    {
        // Daftar pelatihan default yang ditampilkan di form peserta.
        return array('Moshing', 'Icikiwir', 'Desain Grafis', 'Menjahit', 'Public Speaking');
    }
}
