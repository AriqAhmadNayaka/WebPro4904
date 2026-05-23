<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'user1';

    public function get_by_username($username)
    {
        return $this->db->get_where($this->table, [
            'nama' => $username
        ])->row();
    }
}
