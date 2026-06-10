<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Achievement_model extends CI_Model
{
    private $table = 'achievements';

    public function get_by_user($user_id, $limit = NULL)
    {
        $this->db->where('user_id', (int) $user_id)->order_by('awarded_at', 'DESC');

        if ($limit !== NULL) {
            $this->db->limit((int) $limit);
        }

        return $this->db->get($this->table)->result_array();
    }
}
