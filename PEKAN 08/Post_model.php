<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    public function get_posts() {
        return $this->db->order_by('id', 'DESC')->get('posts')->result_array();
    }

    public function get_post($id) {
        return $this->db->get_where('posts', array('id' => $id))->row_array();
    }

    public function save_post($data) {
        if (isset($data['id']) && $data['id']) {
            $id = $data['id'];
            unset($data['id']);
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            return $this->db->update('posts', $data);
        }

        return $this->db->insert('posts', $data);
    }

    public function delete_post($id) {
        $post = $this->get_post($id);
        if ($post && !empty($post['file_path'])) {
            $file_path = FCPATH . $post['file_path'];
            if (is_file($file_path)) {
                unlink($file_path);
            }
        }
        $this->db->where('id', $id);
        return $this->db->delete('posts');
    }
}

