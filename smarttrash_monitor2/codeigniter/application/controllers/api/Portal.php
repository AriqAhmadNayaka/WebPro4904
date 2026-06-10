<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Learning_progress_model');
        $this->load->model('Alert_model');
        $this->load->model('Achievement_model');
        $this->load->model('Notification_model');
    }

    public function dashboard()
    {
        $user = $this->require_api_auth();
        $timeline = $this->Learning_progress_model->get_timeline_by_user($user['id']);
        $alerts = $this->Alert_model->get_latest(2);
        $notifications = $this->Notification_model->get_by_user($user['id'], 3);
        $unread_count = $this->Notification_model->count_unread($user['id']);

        $average_progress = $this->calculate_average_progress($timeline);

        return $this->json_response(array(
            'status' => TRUE,
            'data' => array(
                'user' => $this->serialize_user($user),
                'summary' => array(
                    'awareness_score' => 80,
                    'learning_progress' => $average_progress,
                    'active_reports' => 3,
                    'certificates' => 6,
                    'notification_unread' => $unread_count
                ),
                'hero' => array(
                    'title' => 'Hai, ' . $this->first_name($user['name']) . '!',
                    'subtitle' => 'Selamat Datang di CyberVault',
                    'description' => 'CyberVault siap hadir untuk melindungi data pribadi anda dari kejahatan siber demi mendukung keamanan di era Smart-City.'
                ),
                'quick_actions' => array(
                    array('title' => 'Mulai Belajar', 'description' => 'Akses materi & kuis keamanan digital', 'icon' => 'book'),
                    array('title' => 'Laporkan Insiden', 'description' => 'Laporkan kejadian mencurigakan', 'icon' => 'alert'),
                    array('title' => 'Cek Data Pribadi', 'description' => 'Pantau & kelola data pribadi Anda', 'icon' => 'profile'),
                    array('title' => 'Baca Cyber Alert', 'description' => 'Dapatkan berita & peringatan terbaru', 'icon' => 'article')
                ),
                'progress_breakdown' => array(
                    array('label' => 'Keamanan Akun & Password', 'value' => 70),
                    array('label' => 'Privasi Data Pribadi', 'value' => 50),
                    array('label' => 'Phising & Social Engineering', 'value' => 35)
                ),
                'alerts' => $this->format_alerts($alerts),
                'notifications' => $this->format_notifications($notifications)
            )
        ));
    }

    public function timeline()
    {
        $user = $this->require_api_auth();
        $timeline = $this->Learning_progress_model->get_timeline_by_user($user['id']);
        $achievements = $this->Achievement_model->get_by_user($user['id'], 6);

        $completed = 0;
        $minutes_total = 0;
        foreach ($timeline as $item) {
            if ($item['status'] === 'completed') {
                $completed++;
            }
            $minutes_total += (int) $item['minutes_spent'];
        }

        return $this->json_response(array(
            'status' => TRUE,
            'data' => array(
                'hero' => array(
                    'title' => 'Timeline Belajar',
                    'description' => 'Ikuti roadmap pembelajaran yang terstruktur untuk meningkatkan literasi keamanan digital Anda. Terus belajar, raih sertifikat, dan jadi bagian dari masyarakat digital yang aman.'
                ),
                'stats' => array(
                    'total_minutes' => $minutes_total,
                    'weekly_minutes' => 252,
                    'completed_modules' => $completed,
                    'level_name' => 'Level 2 - Waspada',
                    'xp' => 500
                ),
                'roadmap' => $this->format_roadmap($timeline),
                'chart' => array(
                    array('label' => '20 Apr', 'minutes' => 120),
                    array('label' => '27 Apr', 'minutes' => 180),
                    array('label' => '4 Mei', 'minutes' => 270),
                    array('label' => '11 Mei', 'minutes' => 240),
                    array('label' => '18 Mei', 'minutes' => 300)
                ),
                'achievements' => $this->format_achievements($achievements)
            )
        ));
    }

    public function notifications()
    {
        $user = $this->require_api_auth();
        $notifications = $this->Notification_model->get_by_user($user['id'], 20);

        return $this->json_response(array(
            'status' => TRUE,
            'data' => array(
                'items' => $this->format_notifications($notifications),
                'unread_count' => $this->Notification_model->count_unread($user['id'])
            )
        ));
    }

    public function account()
    {
        $user = $this->require_api_auth();

        if ($this->request_method() === 'GET') {
            return $this->json_response(array(
                'status' => TRUE,
                'data' => array('user' => $this->serialize_user($user))
            ));
        }

        if ($this->request_method() !== 'POST') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Method not allowed'), 405);
        }

        $payload = $this->request_data();
        $name = trim(isset($payload['name']) ? $payload['name'] : '');
        $email = strtolower(trim(isset($payload['email']) ? $payload['email'] : ''));

        if ($name === '' || $email === '') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Nama dan email wajib diisi.'), 422);
        }

        $existing = $this->User_model->get_by_email($email);
        if ($existing && (int) $existing['id'] !== (int) $user['id']) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Email sudah digunakan pengguna lain.'), 409);
        }

        $this->User_model->update($user['id'], array(
            'name' => $name,
            'email' => $email
        ));

        $updated = $this->User_model->get_by_id($user['id']);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Profil berhasil diperbarui.',
            'data' => array('user' => $this->serialize_user($updated))
        ));
    }

    private function calculate_average_progress($timeline)
    {
        if (empty($timeline)) {
            return 0;
        }

        $total = 0;
        foreach ($timeline as $item) {
            $total += (int) $item['progress_percent'];
        }

        return (int) round($total / count($timeline));
    }

    private function format_alerts($alerts)
    {
        return array_map(function ($alert) {
            return array(
                'id' => (int) $alert['id'],
                'title' => $alert['title'],
                'summary' => $alert['summary'],
                'type' => $alert['alert_type'],
                'badge' => $alert['badge_text'],
                'time_label' => $this->relative_time($alert['published_at'])
            );
        }, $alerts);
    }

    private function format_notifications($notifications)
    {
        return array_map(function ($item) {
            return array(
                'id' => (int) $item['id'],
                'title' => $item['title'],
                'body' => $item['body'],
                'type' => $item['notification_type'],
                'is_read' => (bool) $item['is_read'],
                'time_label' => $this->relative_time($item['created_at'])
            );
        }, $notifications);
    }

    private function format_roadmap($timeline)
    {
        return array_map(function ($item) {
            return array(
                'id' => (int) $item['id'],
                'title' => $item['title'],
                'summary' => $item['summary'],
                'icon' => $item['icon_key'],
                'status' => $item['status'],
                'progress_percent' => (int) $item['progress_percent'],
                'minutes_spent' => (int) $item['minutes_spent'],
                'duration_label' => $this->minutes_label($item['duration_minutes']),
                'status_label' => $item['status'] === 'completed' ? 'Selesai' : ($item['status'] === 'active' ? 'Sekarang' : 'belum')
            );
        }, $timeline);
    }

    private function format_achievements($achievements)
    {
        return array_map(function ($item) {
            return array(
                'id' => (int) $item['id'],
                'title' => $item['title'],
                'description' => $item['description'],
                'xp' => (int) $item['xp_earned'],
                'date_label' => date('j M Y', strtotime($item['awarded_at'])),
                'icon' => $item['icon_key']
            );
        }, $achievements);
    }

    private function serialize_user($user)
    {
        return array(
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'photo' => !empty($user['photo']) ? base_url('uploads/profiles/' . $user['photo']) : NULL
        );
    }

    private function first_name($name)
    {
        $parts = preg_split('/\s+/', trim($name));
        return $parts && $parts[0] !== '' ? $parts[0] : $name;
    }

    private function relative_time($datetime)
    {
        $seconds = time() - strtotime($datetime);
        if ($seconds < 3600) {
            return max(1, (int) floor($seconds / 60)) . ' menit lalu';
        }
        if ($seconds < 86400) {
            return max(1, (int) floor($seconds / 3600)) . ' jam lalu';
        }

        return max(1, (int) floor($seconds / 86400)) . ' hari lalu';
    }

    private function minutes_label($minutes)
    {
        $hours = floor($minutes / 60);
        $remaining = $minutes % 60;

        if ($hours > 0 && $remaining > 0) {
            return $hours . ' Jam ' . $remaining . ' Menit';
        }

        if ($hours > 0) {
            return $hours . ' Jam';
        }

        return $remaining . ' Menit';
    }
}
