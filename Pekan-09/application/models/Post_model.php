<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    private $table = 'posts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all($limit = 10, $offset = 0)
    {
        return $this->db
            ->select('id, title, author, article, image, created_at, updated_at')
            ->limit($limit, $offset)
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('id, title, author, article, image, created_at, updated_at')
            ->get_where($this->table, array('id' => $id))
            ->row();
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
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
        return $this->db->get_where($this->table, array('id' => $id))->num_rows() > 0;
    }

    public function search($keyword, $limit = 10, $offset = 0)
    {
        $this->db->like('title', $keyword);
        $this->db->or_like('article', $keyword);
        $this->db->limit($limit, $offset);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}