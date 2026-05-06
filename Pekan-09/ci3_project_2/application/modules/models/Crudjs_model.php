<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crudjs_model extends CI_Model {

    private $table = 'posts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $query   = $this->db->get($this->table);
        $records = $query->result();

        foreach ($records as $record) {
            if ($record->image) {
                $imagePath         = str_replace('posts/', '', $record->image);
                $record->image_url = base_url('uploads/posts/' . $imagePath);
            } else {
                $record->image_url = null;
            }
        }

        return $records;
    }

    public function get_by_id($id)
    {
        $query  = $this->db->get_where($this->table, array('id' => $id));
        $record = $query->row();

        if ($record && $record->image) {
            $imagePath         = str_replace('posts/', '', $record->image);
            $record->image_url = base_url('uploads/posts/' . $imagePath);
        }

        return $record;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function exists($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->num_rows() > 0;
    }
}