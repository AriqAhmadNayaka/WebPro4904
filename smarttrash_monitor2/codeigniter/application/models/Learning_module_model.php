<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Learning_module_model extends CI_Model
{
    private $table = 'learning_modules';

    public function get_all()
    {
        return $this->db->order_by('progress_order', 'ASC')->get($this->table)->result_array();
    }
}
