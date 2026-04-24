<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataAnak extends CI_Controller
{
    protected $childTable;
    protected $publicUploadRelativePath = 'assets/uploads/anak/';

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');

        $this->childTable = $this->detectChildTable();
    }

    public function index()
    {
        if ($this->childTable === null) {
            show_error('Tabel data anak belum ditemukan di database.', 500);
            return;
        }

        $userId = $this->getCurrentUserId();
        $editId = (int) $this->input->get('edit');
        $error = '';
        $success = $this->session->flashdata('success') ?: '';

        $formData = $this->emptyFormData();

        if ($this->input->method(TRUE) === 'POST') {
            if ($this->input->post('hapus') !== null) {
                $result = $this->handleDelete($userId);
                if ($result['success']) {
                    $this->session->set_flashdata('success', $result['message']);
                    redirect('data-anak');
                    return;
                }

                $error = $result['message'];
            }

            if ($this->input->post('simpan') !== null) {
                $result = $this->handleSave($userId);
                if ($result['success']) {
                    $this->session->set_flashdata('success', $result['message']);
                    redirect('data-anak?edit=' . $result['id']);
                    return;
                }

                $error = $result['message'];
                $formData = $result['formData'];
                $editId = (int) $formData['id'];
            }
        }

        if ($editId > 0 && $this->input->method(TRUE) !== 'POST') {
            $child = $this->findChild($editId, $userId);
            if ($child !== null) {
                $formData = $this->mapChildToFormData($child);
            }
        }

        $children = $this->getChildren($userId);

        $this->load->view('data_anak/index', array(
            'children' => $children,
            'error' => $error,
            'success' => $success,
            'formData' => $formData,
            'userName' => $this->getCurrentUserName(),
        ));
    }

    protected function handleDelete($userId)
    {
        $id = (int) $this->input->post('id');
        $child = $this->findChild($id, $userId);

        if ($child === null) {
            return array(
                'success' => false,
                'message' => 'Data anak yang ingin dihapus tidak ditemukan.',
            );
        }

        $this->db->where('id', $id)->delete($this->childTable);
        $this->deletePhoto($child);

        return array(
            'success' => true,
            'message' => 'Data anak berhasil dihapus.',
        );
    }

    protected function handleSave($userId)
    {
        $formData = array(
            'id' => (int) $this->input->post('id'),
            'nama' => trim((string) $this->input->post('nama')),
            'gender' => trim((string) $this->input->post('gender')),
            'tanggal_lahir' => trim((string) $this->input->post('tanggal')),
            'kelas' => trim((string) $this->input->post('kelas')),
            'alamat' => trim((string) $this->input->post('alamat')),
            'catatan' => trim((string) $this->input->post('catatan')),
            'foto' => trim((string) $this->input->post('foto_lama')),
        );

        if ($formData['nama'] === '' || $formData['gender'] === '' || $formData['tanggal_lahir'] === '' || $formData['kelas'] === '') {
            return array(
                'success' => false,
                'message' => 'Nama, gender, tanggal lahir, dan kelas wajib diisi.',
                'formData' => $formData,
            );
        }

        $existingChild = null;
        if ($formData['id'] > 0) {
            $existingChild = $this->findChild($formData['id'], $userId);
            if ($existingChild === null) {
                return array(
                    'success' => false,
                    'message' => 'Data anak yang akan diperbarui tidak ditemukan.',
                    'formData' => $formData,
                );
            }
        }

        $uploadResult = $this->uploadPhoto();
        if ($uploadResult['success'] === false) {
            return array(
                'success' => false,
                'message' => $uploadResult['message'],
                'formData' => $formData,
            );
        }

        if ($uploadResult['path'] !== null) {
            if ($existingChild !== null) {
                $this->deletePhoto($existingChild);
            }
            $formData['foto'] = $uploadResult['path'];
        } elseif ($existingChild !== null && isset($existingChild['foto'])) {
            $formData['foto'] = (string) $existingChild['foto'];
        }

        $payload = $this->buildPayload($formData, $userId);

        if ($formData['id'] > 0) {
            $this->db->where('id', $formData['id'])->update($this->childTable, $payload);
            $savedId = $formData['id'];
            $message = 'Data anak berhasil diperbarui.';
        } else {
            $this->db->insert($this->childTable, $payload);
            $savedId = (int) $this->db->insert_id();
            $message = 'Data anak berhasil ditambahkan.';
        }

        return array(
            'success' => true,
            'message' => $message,
            'id' => $savedId,
        );
    }

    protected function getChildren($userId)
    {
        $this->db->from($this->childTable);

        if ($this->hasColumn('user_id') && $userId !== null) {
            $this->db->where('user_id', $userId);
        }

        if ($this->hasColumn('id')) {
            $this->db->order_by('id', 'DESC');
        }

        $children = $this->db->get()->result_array();

        foreach ($children as &$child) {
            $child = $this->normalizeChildPhoto($child);
        }

        return $children;
    }

    protected function findChild($id, $userId)
    {
        if ($id <= 0) {
            return null;
        }

        $this->db->from($this->childTable);
        $this->db->where('id', $id);

        if ($this->hasColumn('user_id') && $userId !== null) {
            $this->db->where('user_id', $userId);
        }

        $child = $this->db->get()->row_array();

        if ($child !== null) {
            $child = $this->normalizeChildPhoto($child);
        }

        return $child;
    }

    protected function buildPayload(array $formData, $userId)
    {
        $payload = array();
        $columns = $this->db->list_fields($this->childTable);

        $mappedFields = array(
            'nama' => $formData['nama'],
            'gender' => $formData['gender'],
            'tanggal_lahir' => $formData['tanggal_lahir'],
            'kelas' => $formData['kelas'],
            'alamat' => $formData['alamat'],
            'catatan' => $formData['catatan'],
            'foto' => $formData['foto'],
        );

        foreach ($mappedFields as $field => $value) {
            if (in_array($field, $columns, true)) {
                $payload[$field] = $value;
            }
        }

        if ($userId !== null && in_array('user_id', $columns, true)) {
            $payload['user_id'] = $userId;
        }

        return $payload;
    }

    protected function uploadPhoto()
    {
        if (empty($_FILES['foto']) || empty($_FILES['foto']['name'])) {
            return array(
                'success' => true,
                'path' => null,
            );
        }

        $uploadDir = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $this->publicUploadRelativePath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $config = array(
            'upload_path' => $uploadDir,
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size' => 2048,
            'encrypt_name' => true,
        );

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            return array(
                'success' => false,
                'message' => strip_tags($this->upload->display_errors('', '')),
                'path' => null,
            );
        }

        $uploadedData = $this->upload->data();

        return array(
            'success' => true,
            'path' => $this->publicUploadRelativePath . $uploadedData['file_name'],
        );
    }

    protected function deletePhoto(array $child)
    {
        if (empty($child['foto'])) {
            return;
        }

        $relativePath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $child['foto']);
        $absolutePath = FCPATH . ltrim($relativePath, DIRECTORY_SEPARATOR);

        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    protected function mapChildToFormData(array $child)
    {
        $child = $this->normalizeChildPhoto($child);

        return array(
            'id' => isset($child['id']) ? (int) $child['id'] : 0,
            'nama' => isset($child['nama']) ? (string) $child['nama'] : '',
            'gender' => isset($child['gender']) ? (string) $child['gender'] : '',
            'tanggal_lahir' => isset($child['tanggal_lahir']) ? (string) $child['tanggal_lahir'] : '',
            'kelas' => isset($child['kelas']) ? (string) $child['kelas'] : '',
            'alamat' => isset($child['alamat']) ? (string) $child['alamat'] : '',
            'catatan' => isset($child['catatan']) ? (string) $child['catatan'] : '',
            'foto' => isset($child['foto']) ? (string) $child['foto'] : '',
        );
    }

    protected function emptyFormData()
    {
        return array(
            'id' => 0,
            'nama' => '',
            'gender' => '',
            'tanggal_lahir' => '',
            'kelas' => '',
            'alamat' => '',
            'catatan' => '',
            'foto' => '',
        );
    }

    protected function detectChildTable()
    {
        $candidates = array('data_anak', 'anak', 'tbl_anak');

        foreach ($candidates as $table) {
            if ($this->db->table_exists($table)) {
                return $table;
            }
        }

        return null;
    }

    protected function hasColumn($column)
    {
        return in_array($column, $this->db->list_fields($this->childTable), true);
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
}
