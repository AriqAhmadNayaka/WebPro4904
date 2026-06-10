<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Learning_progress_model extends CI_Model
{
    private $table = 'user_learning_progress';

    public function get_timeline_by_user($user_id)
    {
        return $this->db
            ->select('user_learning_progress.*, learning_modules.title, learning_modules.slug, learning_modules.summary, learning_modules.icon_key, learning_modules.duration_minutes, learning_modules.progress_order, learning_modules.xp_reward')
            ->from($this->table)
            ->join('learning_modules', 'learning_modules.id = user_learning_progress.module_id')
            ->where('user_learning_progress.user_id', (int) $user_id)
            ->order_by('learning_modules.progress_order', 'ASC')
            ->get()
            ->result_array();
    }
}
