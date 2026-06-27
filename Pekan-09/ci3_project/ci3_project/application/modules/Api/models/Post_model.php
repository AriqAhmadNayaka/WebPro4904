<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model
{
    private $table = 'posts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->order_by('id', 'DESC');
        $posts = $this->db->get($this->table)->result();

        foreach ($posts as $post) {
            $post->image_url = $post->image ? base_url('uploads/' . $post->image) : null;
        }

        return $posts;
    }

    public function get_by_id($id)
    {
        $post = $this->db->get_where($this->table, array('id' => $id))->row();

        if ($post) {
            $post->image_url = $post->image ? base_url('uploads/' . $post->image) : null;
        }

        return $post;
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
        return $this->db->get_where($this->table, array('id' => $id))->num_rows() > 0;
    }
}
