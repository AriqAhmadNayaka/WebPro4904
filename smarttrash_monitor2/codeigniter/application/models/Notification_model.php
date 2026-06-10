<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model
{
    private $table = 'notifications';

    public function get_by_user($user_id, $limit = NULL)
    {
        $this->db->where('user_id', (int) $user_id)->order_by('created_at', 'DESC');

        if ($limit !== NULL) {
            $this->db->limit((int) $limit);
        }

        return $this->db->get($this->table)->result_array();
    }

    public function count_unread($user_id)
    {
        return (int) $this->db
            ->where('user_id', (int) $user_id)
            ->where('is_read', 0)
            ->count_all_results($this->table);
    }
}
