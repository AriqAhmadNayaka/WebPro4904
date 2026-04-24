<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardOrtu extends CI_Controller
{
    protected $childTable;
    protected $childColumns = array();
    protected $publicUploadRelativePath = 'assets/uploads/anak/';
    protected $scheduleTable;

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');

        $this->childTable = $this->detectTable(array('anak', 'data_anak', 'tbl_anak'));
        if ($this->childTable !== null) {
            $this->childColumns = $this->db->list_fields($this->childTable);
        }

        $this->scheduleTable = $this->detectTable(array('jadwal'));
    }

    public function index()
    {
        $child = $this->getPrimaryChild();
        $schedules = $this->getSchedules();
        $data = $this->buildDashboardData($child, $schedules);

        $this->load->view('dashboard/index', $data);
    }

    protected function buildDashboardData($child, array $schedules)
    {
        if (is_array($child)) {
            $child = $this->normalizeChildPhoto($child);
        }

        $childName = $this->childValue($child, array('nama', 'nama_anak'), '-');
        $photo = $this->childValue($child, array('foto'), '');
        $description = $this->childValue($child, array('deskripsi', 'catatan'), '');

        $communication = $this->normalizePercent($this->childValue($child, array('komunikasi'), 0));
        $independence = $this->normalizePercent($this->childValue($child, array('kemandirian'), 0));
        $vocational = $this->normalizePercent($this->childValue($child, array('vokasional'), 0));

        if ($communication === 0 && $independence === 0 && $vocational === 0) {
            $communication = $child ? 72 : 0;
            $independence = $child ? 58 : 0;
            $vocational = $child ? 64 : 0;
        }

        $progress = $this->normalizePercent($this->childValue($child, array('progress'), 0));
        if ($progress === 0 && ($communication + $independence + $vocational) > 0) {
            $progress = (int) round(($communication + $independence + $vocational) / 3);
        }

        $nextSchedule = 'Belum ada';
        if (!empty($schedules)) {
            $first = $schedules[0];
            $nextSchedule = trim(($first['hari'] ?? '-') . ' - ' . ($first['kegiatan'] ?? '-'));
        }

        $summaryTarget = $childName !== '-' ? $childName : 'anak Anda';
        $summaryText = $description !== ''
            ? $description
            : 'Berikut ringkasan perkembangan dan aktivitas ' . $summaryTarget . '.';

        return array(
            'childName' => $childName,
            'description' => $summaryText,
            'independence' => $independence,
            'nextSchedule' => $nextSchedule,
            'photo' => $photo,
            'progress' => $progress,
            'schedules' => $schedules,
            'summaryTitle' => 'Ringkasan Data Anak',
            'userName' => $this->getCurrentUserName(),
            'vocational' => $vocational,
            'communication' => $communication,
        );
    }

    protected function getPrimaryChild()
    {
        if ($this->childTable === null) {
            return null;
        }

        $this->db->from($this->childTable);

        $userId = $this->getCurrentUserId();
        if ($userId !== null && in_array('user_id', $this->childColumns, true)) {
            $this->db->where('user_id', $userId);
        }

        if (in_array('id', $this->childColumns, true)) {
            $this->db->order_by('id', 'DESC');
        }

        return $this->db->get()->row_array();
    }

    protected function getSchedules()
    {
        if ($this->scheduleTable === null) {
            return array();
        }

        $query = $this->db->order_by('id', 'ASC')->get($this->scheduleTable);
        return $query->result_array();
    }

    protected function childValue($child, array $keys, $default = '')
    {
        if (!is_array($child)) {
            return $default;
        }

        foreach ($keys as $key) {
            if (array_key_exists($key, $child) && $child[$key] !== null && $child[$key] !== '') {
                return $child[$key];
            }
        }

        return $default;
    }

    protected function normalizePercent($value)
    {
        $value = (int) $value;
        if ($value < 0) {
            return 0;
        }
        if ($value > 100) {
            return 100;
        }

        return $value;
    }

    protected function detectTable(array $candidates)
    {
        foreach ($candidates as $table) {
            if ($this->db->table_exists($table)) {
                return $table;
            }
        }

        return null;
    }

    protected function getCurrentUserId()
    {
        $sessionData = $this->session->userdata();
        $keys = array('user_id', 'id_user', 'id', 'id_users');

        foreach ($keys as $key) {
            if (isset($sessionData[$key]) && $sessionData[$key] !== '') {
                return (int) $sessionData[$key];
            }
        }

        return null;
    }

    protected function getCurrentUserName()
    {
        $sessionData = $this->session->userdata();
        $keys = array('name', 'nama', 'username', 'full_name');

        foreach ($keys as $key) {
            if (!empty($sessionData[$key])) {
                return (string) $sessionData[$key];
            }
        }

        return 'Pengguna';
    }

    protected function normalizeChildPhoto(array $child)
    {
        if (empty($child['foto'])) {
            return $child;
        }

        $child['foto'] = $this->ensurePublicPhotoPath((string) $child['foto']);

        return $child;
    }

    protected function ensurePublicPhotoPath($storedPath)
    {
        $normalizedPath = str_replace('\\', '/', trim($storedPath));

        if ($normalizedPath === '') {
            return '';
        }

        if (strpos($normalizedPath, $this->publicUploadRelativePath) === 0) {
            return $normalizedPath;
        }

        $sourcePath = FCPATH . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath), DIRECTORY_SEPARATOR);
        $filename = basename($normalizedPath);
        $targetRelativePath = $this->publicUploadRelativePath . $filename;
        $targetPath = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $targetRelativePath);
        $targetDir = dirname($targetPath);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (is_file($sourcePath) && !is_file($targetPath)) {
            @copy($sourcePath, $targetPath);
        }

        if (is_file($targetPath)) {
            return $targetRelativePath;
        }

        return $normalizedPath;
    }
}
