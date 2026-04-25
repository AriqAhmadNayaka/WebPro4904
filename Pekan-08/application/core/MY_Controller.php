<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap_database();
    }

    private function bootstrap_database()
    {
        $host = '127.0.0.1';
        $user = 'root';
        $pass = '';
        $port = 3306;
        $database = 'db_ci3';

        mysqli_report(MYSQLI_REPORT_OFF);

        try {
            $mysqli = new mysqli($host, $user, $pass, '', $port);
        } catch (Exception $e) {
            return;
        }

        if ($mysqli->connect_error) {
            return;
        }

        $mysqli->query("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8 COLLATE utf8_general_ci");
        $mysqli->close();

        if (!isset($this->db) || !$this->db->conn_id) {
            $this->load->database();
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama VARCHAR(100) DEFAULT NULL,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(50) NOT NULL
            )
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                judul VARCHAR(150) NOT NULL,
                deskripsi TEXT NOT NULL,
                file VARCHAR(255) DEFAULT NULL
            )
        ");

        $admin = $this->db->get_where('users', array('username' => 'admin'))->row();
        if (!$admin) {
            $this->db->insert('users', array(
                'nama' => 'Administrator',
                'username' => 'admin',
                'password' => 'admin123',
            ));
        }

        $upload_path = FCPATH . 'uploads';
        if (!is_dir($upload_path)) {
            @mkdir($upload_path, 0755, TRUE);
        }

        $index_file = $upload_path . DIRECTORY_SEPARATOR . 'index.html';
        if (!file_exists($index_file)) {
            @file_put_contents($index_file, '');
        }
    }
}
