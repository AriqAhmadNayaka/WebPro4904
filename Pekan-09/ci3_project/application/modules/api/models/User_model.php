<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function ensure_table()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->add_column_if_missing('name', "VARCHAR(255) NULL AFTER `id`");
        $this->add_column_if_missing('created_at', "DATETIME NULL DEFAULT CURRENT_TIMESTAMP");
        $this->add_column_if_missing('updated_at', "DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

    public function email_exists($email)
    {
        return $this->db
            ->where('email', $email)
            ->count_all_results($this->table) > 0;
    }

    public function create($data)
    {
        $now = date('Y-m-d H:i:s');
        $insert = array(
            'name' => isset($data['name']) ? $data['name'] : '',
            'username' => isset($data['name']) ? $data['name'] : '',
            'email' => $data['email'],
            'password' => $data['password'],
            'created_at' => $now,
            'updated_at' => $now,
        );

        $this->db->insert($this->table, $insert);
        return $this->db->insert_id();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('id, name, username, email, created_at, updated_at')
            ->where('id', (int) $id)
            ->get($this->table)
            ->row();
    }

    public function get_by_email($email)
    {
        return $this->db
            ->where('email', $email)
            ->get($this->table)
            ->row();
    }

    private function add_column_if_missing($column, $definition)
    {
        if (!$this->db->field_exists($column, $this->table)) {
            $this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `{$column}` {$definition}");
        }
    }
}
