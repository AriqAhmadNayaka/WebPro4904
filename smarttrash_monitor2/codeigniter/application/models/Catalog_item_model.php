<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog_item_model extends CI_Model
{
    private $table = 'catalog_items';

    public function get_by_category($category_id)
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get_where($this->table, array('category_id' => (int) $category_id))
            ->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
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

    public function count_all()
    {
        return (int) $this->db->count_all($this->table);
    }
}
