<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Proyek_model
 *
 * Bertanggung jawab atas semua operasi CRUD pada tabel 'proyek',
 * termasuk proses upload file berkas.
 *
 * Menggunakan CI3 Query Builder ($this->db) dan Upload Library
 * ($this->load->library('upload')) sebagai pengganti fungsi manual.
 */
class Proyek_model extends CI_Model {

    // Folder tujuan upload file (relatif terhadap root CI)
    private string $upload_path = './uploads/';

    /**
     * Mengambil semua data proyek dari database.
     *
     * @return array hasil query semua proyek
     */
    public function get_all(): array
    {
        return $this->db->get('proyek')->result_array();
    }

    /**
     * Mengambil satu data proyek berdasarkan ID (untuk mode Edit).
     *
     * @param  int   $id
     * @return array data proyek sebagai associative array
     */
    public function get_by_id(int $id): array
    {
        $this->db->where('id', $id);
        return $this->db->get('proyek')->row_array();
    }

    /**
     * Menambahkan proyek baru ke database (Create).
     * Menangani upload file jika ada berkas yang di-upload.
     *
     * @param  string $nama_proyek
     * @param  string $deskripsi
     * @return bool
     */
    public function create(string $nama_proyek, string $deskripsi): bool
    {
        $nama_file = $this->_upload_file();

        return $this->db->insert('proyek', [
            'nama_proyek' => $nama_proyek,
            'deskripsi'   => $deskripsi,
            'nama_file'   => $nama_file,
        ]);
    }

    /**
     * Memperbarui data proyek (Update).
     * Jika ada file baru, upload dan gunakan nama file baru;
     * jika tidak, pertahankan nama file lama.
     *
     * @param  int    $id
     * @param  string $nama_proyek
     * @param  string $deskripsi
     * @param  string $file_lama   Nama file lama (fallback jika tidak ada upload baru)
     * @return bool
     */
    public function update(int $id, string $nama_proyek, string $deskripsi, string $file_lama): bool
    {
        // Coba upload file baru; jika tidak ada, tetap pakai file lama
        $nama_file = $_FILES['berkas']['name']
            ? $this->_upload_file()
            : $file_lama;

        $this->db->where('id', $id);
        return $this->db->update('proyek', [
            'nama_proyek' => $nama_proyek,
            'deskripsi'   => $deskripsi,
            'nama_file'   => $nama_file,
        ]);
    }

    /**
     * Menghapus proyek berdasarkan ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->db->where('id', $id);
        return $this->db->delete('proyek');
    }

    /**
     * Method private untuk proses upload file menggunakan CI Upload Library.
     * Dipanggil oleh create() dan update().
     *
     * @return string Nama file yang berhasil di-upload, atau string kosong
     */
    private function _upload_file(): string
    {
        // Jika tidak ada file yang di-upload, kembalikan string kosong
        if (empty($_FILES['berkas']['name'])) {
            return '';
        }

        $this->load->library('upload', [
            'upload_path'   => $this->upload_path,
            'allowed_types' => '*',
            'overwrite'     => TRUE,
        ]);

        if ($this->upload->do_upload('berkas')) {
            return $this->upload->data('file_name');
        }

        return '';
    }
}
?>
