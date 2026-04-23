<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor_model extends CI_Model
{
    // Seluruh CRUD dokter difokuskan ke tabel ini.
    private $table = 'dokter';

    public function all($conditions = [])
    {
        // Kalau ada filter, terapkan dulu sebelum query dijalankan.
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }

        return $this->db->get($this->table)->result_array();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_data($id, $data)
    {
        // update_data dipakai supaya nama method tidak bentrok dengan method bawaan.
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_data($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
