<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/MX/Controller.php';

class MY_Controller extends MX_Controller
{
    protected $api_user_cache = NULL;

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
        $database = 'smarttrash_monitor2';

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
            CREATE TABLE IF NOT EXISTS trash_bins (
                id INT AUTO_INCREMENT PRIMARY KEY,
                bin_code VARCHAR(30) NOT NULL UNIQUE,
                location_name VARCHAR(120) NOT NULL,
                waste_level TINYINT UNSIGNED NOT NULL DEFAULT 0,
                status ENUM('AMAN', 'WASPADA', 'PENUH', 'DIANGKUT') NOT NULL DEFAULT 'AMAN',
                last_collection DATETIME DEFAULT NULL,
                notes TEXT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS waste_categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category_name VARCHAR(100) NOT NULL UNIQUE,
                category_slug VARCHAR(120) NOT NULL UNIQUE,
                description TEXT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS catalog_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category_id INT NOT NULL,
                item_name VARCHAR(120) NOT NULL,
                item_type VARCHAR(80) NOT NULL,
                item_condition ENUM('Layak Daur Ulang', 'Perlu Dibersihkan', 'Residu') NOT NULL DEFAULT 'Layak Daur Ulang',
                example_photo VARCHAR(255) DEFAULT NULL,
                notes TEXT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_catalog_category
                    FOREIGN KEY (category_id) REFERENCES waste_categories(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS api_tokens (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                token VARCHAR(128) NOT NULL UNIQUE,
                expires_at DATETIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_api_token_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS password_reset_tokens (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                otp_code VARCHAR(12) NOT NULL,
                expires_at DATETIME NOT NULL,
                used_at DATETIME DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_reset_token_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS learning_modules (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(120) NOT NULL,
                slug VARCHAR(140) NOT NULL UNIQUE,
                summary TEXT DEFAULT NULL,
                icon_key VARCHAR(40) NOT NULL DEFAULT 'shield',
                duration_minutes INT NOT NULL DEFAULT 60,
                progress_order INT NOT NULL DEFAULT 1,
                xp_reward INT NOT NULL DEFAULT 50,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS user_learning_progress (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                module_id INT NOT NULL,
                status ENUM('completed', 'active', 'locked') NOT NULL DEFAULT 'locked',
                progress_percent TINYINT UNSIGNED NOT NULL DEFAULT 0,
                minutes_spent INT NOT NULL DEFAULT 0,
                last_activity_at DATETIME DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_user_module (user_id, module_id),
                CONSTRAINT fk_progress_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT fk_progress_module
                    FOREIGN KEY (module_id) REFERENCES learning_modules(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS alerts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(160) NOT NULL,
                summary TEXT DEFAULT NULL,
                alert_type ENUM('warning', 'info') NOT NULL DEFAULT 'info',
                badge_text VARCHAR(40) DEFAULT NULL,
                published_at DATETIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS achievements (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(120) NOT NULL,
                description TEXT DEFAULT NULL,
                xp_earned INT NOT NULL DEFAULT 0,
                awarded_at DATETIME NOT NULL,
                icon_key VARCHAR(40) NOT NULL DEFAULT 'badge',
                CONSTRAINT fk_achievement_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(160) NOT NULL,
                body TEXT DEFAULT NULL,
                notification_type ENUM('info', 'warning', 'success') NOT NULL DEFAULT 'info',
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL,
                CONSTRAINT fk_notification_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

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

        $default_bins = array(
            array('BIN-001', 'Lobby Kampus', 35, 'AMAN', NULL, 'Kondisi normal.'),
            array('BIN-002', 'Area Parkir Timur', 78, 'WASPADA', NULL, 'Perlu dicek sore ini.'),
            array('BIN-003', 'Kantin Utama', 95, 'PENUH', NULL, 'Prioritas pengangkutan.')
        );

        foreach ($default_bins as $bin) {
            $bin_code = $this->db->escape($bin[0]);
            $location_name = $this->db->escape($bin[1]);
            $waste_level = (int) $bin[2];
            $status = $this->db->escape($bin[3]);
            $last_collection = $bin[4] === NULL ? 'NULL' : $this->db->escape($bin[4]);
            $notes = $bin[5] === NULL ? 'NULL' : $this->db->escape($bin[5]);

            $this->db->query("
                INSERT INTO trash_bins (bin_code, location_name, waste_level, status, last_collection, notes)
                VALUES ({$bin_code}, {$location_name}, {$waste_level}, {$status}, {$last_collection}, {$notes})
                ON DUPLICATE KEY UPDATE
                    location_name = VALUES(location_name),
                    waste_level = VALUES(waste_level),
                    status = VALUES(status),
                    notes = VALUES(notes)
            ");
        }

        $default_categories = array(
            array('Organik', 'Sampah yang mudah terurai seperti sisa makanan, daun, dan kulit buah.'),
            array('Non Organik', 'Sampah anorganik seperti botol plastik, kaleng, dan kemasan.'),
            array('B3', 'Sampah bahan berbahaya dan beracun seperti baterai dan lampu bekas.')
        );

        foreach ($default_categories as $category) {
            $name = trim($category[0]);
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
            $description = $category[1];

            $this->db->query("
                INSERT INTO waste_categories (category_name, category_slug, description)
                VALUES (" . $this->db->escape($name) . ", " . $this->db->escape($slug) . ", " . $this->db->escape($description) . ")
                ON DUPLICATE KEY UPDATE
                    description = VALUES(description),
                    category_name = VALUES(category_name)
            ");
        }

        $default_items = array(
            array('Organik', 'Sisa Nasi', 'Sisa Makanan', 'Layak Daur Ulang', 'Bisa masuk komposter.'),
            array('Non Organik', 'Botol Plastik', 'Plastik PET', 'Perlu Dibersihkan', 'Bilas sebelum didaur ulang.'),
            array('B3', 'Baterai Bekas', 'Limbah Elektronik', 'Residu', 'Simpan di wadah khusus B3.')
        );

        foreach ($default_items as $item) {
            $category = $this->db->get_where('waste_categories', array('category_name' => $item[0]))->row_array();
            if (!$category) {
                continue;
            }

            $exists = $this->db
                ->get_where('catalog_items', array(
                    'category_id' => $category['id'],
                    'item_name' => $item[1]
                ))
                ->row_array();

            if ($exists) {
                continue;
            }

            $this->db->insert('catalog_items', array(
                'category_id' => $category['id'],
                'item_name' => $item[1],
                'item_type' => $item[2],
                'item_condition' => $item[3],
                'notes' => $item[4]
            ));
        }

        $default_modules = array(
            array('Dasar Keamanan Digital', 'dasar-keamanan-digital', 'Pahami konsep dasar keamanan digital dan ancaman umum.', 'heart', 150, 1, 80),
            array('Phising & Social Engineering', 'phising-social-engineering', 'Kenali berbagai kejadian phishing dan cara menghindarinya.', 'monitor', 195, 2, 70),
            array('Password & Autentikasi', 'password-autentikasi', 'Cara membuat password yang kuat dan melakukan autentikasi dua faktor.', 'lock', 135, 3, 60),
            array('Privasi Data Pribadi', 'privasi-data-pribadi', 'Lindungi data pribadi Anda di dunia digital.', 'shield', 150, 4, 55),
            array('Keamanan di Smart City', 'keamanan-smart-city', 'Keamanan digital dalam ekosistem Smart City.', 'globe', 210, 5, 90)
        );

        foreach ($default_modules as $module) {
            $exists = $this->db->get_where('learning_modules', array('slug' => $module[1]))->row_array();
            if ($exists) {
                $this->db->where('id', $exists['id'])->update('learning_modules', array(
                    'title' => $module[0],
                    'summary' => $module[2],
                    'icon_key' => $module[3],
                    'duration_minutes' => $module[4],
                    'progress_order' => $module[5],
                    'xp_reward' => $module[6]
                ));
                continue;
            }

            $this->db->insert('learning_modules', array(
                'title' => $module[0],
                'slug' => $module[1],
                'summary' => $module[2],
                'icon_key' => $module[3],
                'duration_minutes' => $module[4],
                'progress_order' => $module[5],
                'xp_reward' => $module[6]
            ));
        }

        $default_alerts = array(
            array('Peringatan: Modus Penipuan QRIS Palsu Meningkat', 'Waspadai modus penipuan menggunakan QRIS palsu yang marak terjadi di berbagai kota. Jangan sembarangan scan QR yang tidak dikenal.', 'warning', 'Peringatan', date('Y-m-d H:i:s', strtotime('-2 hours'))),
            array('Kebocoran Data di Platform E-Commerce', 'Beberapa data pengguna e-commerce dilaporkan bocor. Segera ubah password dan lakukan autentikasi dua faktor.', 'info', 'Informasi', date('Y-m-d H:i:s', strtotime('-2 hours'))),
            array('Tips Aman Menggunakan Wi-Fi Publik', 'Hindari transaksi sensitif saat memakai Wi-Fi publik tanpa VPN.', 'info', 'Tips', date('Y-m-d H:i:s', strtotime('-1 day')))
        );

        foreach ($default_alerts as $alert) {
            $exists = $this->db->get_where('alerts', array('title' => $alert[0]))->row_array();
            if ($exists) {
                continue;
            }

            $this->db->insert('alerts', array(
                'title' => $alert[0],
                'summary' => $alert[1],
                'alert_type' => $alert[2],
                'badge_text' => $alert[3],
                'published_at' => $alert[4]
            ));
        }

        $users = $this->db->order_by('id', 'ASC')->get('users')->result_array();
        $modules = $this->db->order_by('progress_order', 'ASC')->get('learning_modules')->result_array();

        foreach ($users as $user) {
            foreach ($modules as $index => $module) {
                $progress = $this->db->get_where('user_learning_progress', array(
                    'user_id' => $user['id'],
                    'module_id' => $module['id']
                ))->row_array();

                if ($progress) {
                    continue;
                }

                $seed_map = array(
                    0 => array('completed', 100, 150, date('Y-m-d H:i:s', strtotime('-14 days'))),
                    1 => array('active', 60, 135, date('Y-m-d H:i:s', strtotime('-7 days'))),
                    2 => array('locked', 0, 0, NULL),
                    3 => array('locked', 0, 0, NULL),
                    4 => array('locked', 0, 0, NULL)
                );

                $seed = isset($seed_map[$index]) ? $seed_map[$index] : array('locked', 0, 0, NULL);

                $this->db->insert('user_learning_progress', array(
                    'user_id' => $user['id'],
                    'module_id' => $module['id'],
                    'status' => $seed[0],
                    'progress_percent' => $seed[1],
                    'minutes_spent' => $seed[2],
                    'last_activity_at' => $seed[3]
                ));
            }

            $default_notifications = array(
                array('Selamat datang di CyberVault', 'Akun Anda aktif. Mulai perjalanan belajar keamanan siber sekarang.', 'success', 0, date('Y-m-d H:i:s', strtotime('-1 day'))),
                array('3 notifikasi baru menunggu', 'Ada peringatan dan pembaruan materi terbaru untuk Anda.', 'info', 0, date('Y-m-d H:i:s', strtotime('-3 hours'))),
                array('Aktifkan autentikasi 2 faktor', 'Tingkatkan keamanan akun Anda dengan autentikasi dua faktor.', 'warning', 1, date('Y-m-d H:i:s', strtotime('-2 days')))
            );

            foreach ($default_notifications as $notification) {
                $exists = $this->db->get_where('notifications', array(
                    'user_id' => $user['id'],
                    'title' => $notification[0]
                ))->row_array();

                if ($exists) {
                    continue;
                }

                $this->db->insert('notifications', array(
                    'user_id' => $user['id'],
                    'title' => $notification[0],
                    'body' => $notification[1],
                    'notification_type' => $notification[2],
                    'is_read' => $notification[3],
                    'created_at' => $notification[4]
                ));
            }

            $default_achievements = array(
                array('Pahlawan Phishing', 'Menyelesaikan materi Phising & Social Engineering', 70, date('Y-m-d H:i:s', strtotime('-36 days')), 'badge'),
                array('Akun Lebih Aman', 'Melakukan autentikasi 2 faktor', 100, date('Y-m-d H:i:s', strtotime('-37 days')), 'shield'),
                array('Pembelajar Aktif', 'Belajar selama 7 hari berturut-turut', 120, date('Y-m-d H:i:s', strtotime('-39 days')), 'book')
            );

            foreach ($default_achievements as $achievement) {
                $exists = $this->db->get_where('achievements', array(
                    'user_id' => $user['id'],
                    'title' => $achievement[0]
                ))->row_array();

                if ($exists) {
                    continue;
                }

                $this->db->insert('achievements', array(
                    'user_id' => $user['id'],
                    'title' => $achievement[0],
                    'description' => $achievement[1],
                    'xp_earned' => $achievement[2],
                    'awarded_at' => $achievement[3],
                    'icon_key' => $achievement[4]
                ));
            }
        }

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

        $items_path = FCPATH . 'uploads/items';
        if (!is_dir($items_path)) {
            @mkdir($items_path, 0755, TRUE);
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

    protected function bearer_token()
    {
        $header = $this->input->get_request_header('Authorization', TRUE);
        if (!$header) {
            return '';
        }

        if (preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
            return trim($matches[1]);
        }

        return '';
    }

    protected function current_api_user()
    {
        if ($this->api_user_cache !== NULL) {
            return $this->api_user_cache;
        }

        $token = $this->bearer_token();
        if ($token === '') {
            $this->api_user_cache = FALSE;
            return FALSE;
        }

        $record = $this->db
            ->select('api_tokens.*, users.id as user_id, users.name, users.email, users.role, users.photo')
            ->from('api_tokens')
            ->join('users', 'users.id = api_tokens.user_id')
            ->where('api_tokens.token', $token)
            ->where('api_tokens.expires_at >=', date('Y-m-d H:i:s'))
            ->get()
            ->row_array();

        if (!$record) {
            $this->api_user_cache = FALSE;
            return FALSE;
        }

        $this->api_user_cache = array(
            'id' => (int) $record['user_id'],
            'name' => $record['name'],
            'email' => $record['email'],
            'role' => $record['role'],
            'photo' => $record['photo'],
            'token' => $record['token']
        );

        return $this->api_user_cache;
    }

    protected function require_api_auth()
    {
        $user = $this->current_api_user();
        if (!$user) {
            $this->json_response(array(
                'status' => FALSE,
                'message' => 'Akses ditolak. Silakan login kembali.'
            ), 401)->_display();
            exit;
        }

        return $user;
    }

    protected function issue_api_token($user_id)
    {
        $token = bin2hex(random_bytes(32));
        $this->db->insert('api_tokens', array(
            'user_id' => (int) $user_id,
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
        ));

        return $token;
    }

    protected function revoke_api_token($token)
    {
        if ($token === '') {
            return;
        }

        $this->db->where('token', $token)->delete('api_tokens');
    }
}
