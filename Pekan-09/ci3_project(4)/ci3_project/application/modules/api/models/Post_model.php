<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    private $table = 'posts';

    public function get_all()
    {
        $this->db->order_by('id', 'DESC');
        $posts = $this->db->get($this->table)->result();

        foreach ($posts as $post) {
            $post->image_url = $this->image_url($post->image);
        }

        return $posts;
    }

    public function get_by_id($id)
    {
        $post = $this->db->get_where($this->table, array('id' => $id))->row();

        if ($post) {
            $post->image_url = $this->image_url($post->image);
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
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    private function image_url($image)
    {
        if (empty($image)) {
            return null;
        }

        return base_url('uploads/posts/' . basename($image));
    }
}
