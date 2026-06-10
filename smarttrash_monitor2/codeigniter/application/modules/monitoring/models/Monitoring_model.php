<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_model extends CI_Model
{
    private $table = 'trash_bins';

    public function get_all()
    {
        return $this->db->order_by('id', 'DESC')->get($this->table)->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
    }

    public function get_by_code($bin_code)
    {
        return $this->db->get_where($this->table, array('bin_code' => $bin_code))->row_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', (int) $id)->delete($this->table);
    }
}
