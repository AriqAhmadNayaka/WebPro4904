<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_setup_model extends CI_Model {

    public function ensure_tables() {
        $this->ensure_users_table();
        $this->ensure_posts_table();
    }

    public function ensure_users_table() {
        if ($this->db->table_exists('users')) {
            return;
        }

        $sql = "CREATE TABLE `users` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(50) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `username_unique` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->db->query($sql);
    }

    public function ensure_posts_table() {
        if ($this->db->table_exists('posts')) {
            return;
        }

        $sql = "CREATE TABLE `posts` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `content` TEXT NOT NULL,
            `file_path` VARCHAR(255) DEFAULT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->db->query($sql);
    }
}
