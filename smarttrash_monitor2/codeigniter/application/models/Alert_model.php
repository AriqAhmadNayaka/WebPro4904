<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alert_model extends CI_Model
{
    private $table = 'alerts';

    public function get_latest($limit = 5)
    {
        return $this->db
            ->order_by('published_at', 'DESC')
            ->limit((int) $limit)
            ->get($this->table)
            ->result_array();
    }
}
