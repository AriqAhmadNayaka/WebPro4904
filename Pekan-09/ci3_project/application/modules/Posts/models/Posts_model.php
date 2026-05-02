<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_model extends CI_Model
{
    private $table = 'posts';

    public function ensure_table()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `posts` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `author` VARCHAR(255) NOT NULL,
                `article` TEXT NOT NULL,
                `image` VARCHAR(255) DEFAULT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function get_all()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->get($this->table)
            ->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }
}
