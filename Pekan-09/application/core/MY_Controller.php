<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/MX/Controller.php';

class MY_Controller extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap_database();
    }

    protected function bootstrap_database()
    {
        $host = '127.0.0.1';
        $user = 'root';
        $pass = '';
        $port = 3306;
        $database = 'db_pekan09_ci3';

        mysqli_report(MYSQLI_REPORT_OFF);

        try {
            $mysqli = new mysqli($host, $user, $pass, '', $port);
        } catch (Exception $e) {
            return;
        }

        if ($mysqli->connect_error) {
            return;
        }

        $mysqli->query("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $mysqli->close();

        if (!isset($this->db) || !$this->db->conn_id) {
            $this->load->database();
        }

        $this->db->query("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
                photo VARCHAR(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                content TEXT NOT NULL,
                file VARCHAR(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $post_columns = $this->db->query("SHOW COLUMNS FROM posts LIKE 'file'")->result_array();
        if (empty($post_columns)) {
            $this->db->query("ALTER TABLE posts ADD COLUMN file VARCHAR(255) DEFAULT NULL AFTER content");
        }

        $admin_name = $this->db->escape('Administrator Utama');
        $admin_email = $this->db->escape('admin@cybervault.com');
        $admin_password = $this->db->escape(password_hash('admin123', PASSWORD_DEFAULT));

        $this->db->query("
            INSERT INTO users (name, email, password, role, photo)
            VALUES ({$admin_name}, {$admin_email}, {$admin_password}, 'admin', NULL)
            ON DUPLICATE KEY UPDATE
                role = VALUES(role),
                name = name
        ");

        $upload_path = FCPATH . 'uploads';
        if (!is_dir($upload_path)) {
            @mkdir($upload_path, 0755, TRUE);
        }

        $profile_path = FCPATH . 'uploads/profiles';
        if (!is_dir($profile_path)) {
            @mkdir($profile_path, 0755, TRUE);
        }

        $posts_path = FCPATH . 'uploads/posts';
        if (!is_dir($posts_path)) {
            @mkdir($posts_path, 0755, TRUE);
        }
    }

    protected function json_response($payload, $status_code = 200)
    {
        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    protected function request_data()
    {
        $content_type = $this->input->server('CONTENT_TYPE');
        $raw = trim($this->input->raw_input_stream);
        $server_method = strtoupper((string) $this->input->server('REQUEST_METHOD', TRUE));

        if ($raw !== '' && stripos((string) $content_type, 'application/json') !== FALSE) {
            $decoded = json_decode($raw, TRUE);
            return is_array($decoded) ? $decoded : array();
        }

        if ($server_method === 'POST') {
            $post_data = $this->input->post(NULL, TRUE);
            if (!empty($post_data)) {
                return $post_data;
            }
        }

        if ($this->request_method() === 'GET') {
            return $this->input->get(NULL, TRUE) ?: array();
        }

        if ($this->request_method() === 'POST') {
            return $this->input->post(NULL, TRUE) ?: array();
        }

        if ($raw !== '') {
            $parsed = array();
            parse_str($raw, $parsed);
            return $parsed;
        }

        return array();
    }

    protected function request_method()
    {
        $method = $this->input->server('HTTP_X_HTTP_METHOD_OVERRIDE', TRUE);

        if (!$method) {
            $method = $this->input->post('_method', TRUE);
        }

        if (!$method) {
            $method = $this->input->server('REQUEST_METHOD', TRUE);
        }

        if (!$method) {
            $method = 'GET';
        }

        return strtoupper($method);
    }

    protected function is_logged_in()
    {
        return (bool) $this->session->userdata('logged_in');
    }

    protected function current_user()
    {
        return array(
            'id' => $this->session->userdata('user_id'),
            'name' => $this->session->userdata('name'),
            'email' => $this->session->userdata('email'),
            'role' => $this->session->userdata('role')
        );
    }

    protected function require_login()
    {
        if (!$this->is_logged_in()) {
            redirect('auth');
        }
    }

    protected function require_admin()
    {
        $this->require_login();

        if ($this->session->userdata('role') !== 'admin') {
            redirect('dashboard');
        }
    }
}
