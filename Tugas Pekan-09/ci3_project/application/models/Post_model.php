<?php
class Post_model extends CI_Model {

    public function get_posts($id = FALSE){
        if($id === FALSE){
            return $this->db->get('posts')->result_array();
        }

        return $this->db->get_where('posts', array('id' => $id))->row_array();
    }

    public function insert_post(){
        $data = array(
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content')
        );

        return $this->db->insert('posts', $data);
    }

    public function update_post($id){
        $data = array(
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content')
        );

        $this->db->where('id', $id);
        return $this->db->update('posts', $data);
    }

    public function delete_post($id){
        return $this->db->delete('posts', array('id' => $id));
    }
}