<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_service
{
    // Path absolut ke folder uploads di root project.
    private $upload_path;
    // Ekstensi yang diizinkan agar file liar tidak ikut ter-upload.
    private $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];

    public function __construct()
    {
        $this->upload_path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;
    }

    public function upload($file_data)
    {
        // Error code 4 = input file kosong.
        if (!isset($file_data) || (int) $file_data['error'] === 4) {
            return ['status' => 'error', 'msg' => 'Pilih file terlebih dahulu!'];
        }

        $file_name = $file_data['name'];
        $file_size = (int) $file_data['size'];
        $tmp_name = $file_data['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi jenis file.
        if (!in_array($ext, $this->allowed_extensions, true)) {
            return ['status' => 'error', 'msg' => 'Format file tidak didukung! (JPG, PNG, PDF, DOCX)'];
        }

        // Batasi maksimal ukuran 2MB biar server tidak cepat penuh.
        if ($file_size > 2000000) {
            return ['status' => 'error', 'msg' => 'Ukuran file terlalu besar! (Maksimal 2MB)'];
        }

        // Pastikan folder tujuan ada.
        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0777, true);
        }

        // Gunakan nama unik agar tidak menimpa file lama.
        $new_name = uniqid('', true) . '.' . $ext;

        if (move_uploaded_file($tmp_name, $this->upload_path . $new_name)) {
            return ['status' => 'success', 'filename' => $new_name];
        }

        return ['status' => 'error', 'msg' => 'Gagal mengupload file ke server.'];
    }

    public function delete($file_name)
    {
        // Kalau nama file kosong, tidak ada yang perlu dihapus.
        if (empty($file_name)) {
            return false;
        }

        $file_path = $this->upload_path . $file_name;
        if (file_exists($file_path)) {
            return unlink($file_path);
        }

        return false;
    }

    public function handle_update($file_input, $old_file_name, $default_file = 'default.png')
    {
        // Saat edit data, file baru opsional.
        if (isset($file_input['error']) && (int) $file_input['error'] !== 4) {
            $upload = $this->upload($file_input);
            if ($upload['status'] === 'success') {
                // Hapus file lama supaya storage tetap bersih.
                if (!empty($old_file_name) && $old_file_name !== $default_file) {
                    $this->delete($old_file_name);
                }
                return ['status' => 'success', 'filename' => $upload['filename']];
            }

            return ['status' => 'error', 'msg' => $upload['msg']];
        }

        // Jika tidak upload file baru, pakai file lama.
        return ['status' => 'no_file', 'filename' => $old_file_name];
    }
}
