<?php
// Mendefinisikan model Laporan_model yang akan digunakan untuk operasi terkait laporan, seperti mengambil, menambah, mengubah, dan menghapus laporan
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Laporan_model extends CI_Model { // membuat class Laporan_model yang merupakan model untuk menangani operasi terkait laporan

    // Fungsi untuk mengambil semua laporan milik pengguna tertentu, diurutkan berdasarkan tanggal secara menurun
    public function get_all($user_id) {
        // Mengambil semua laporan yang dimiliki oleh pengguna dengan user_id tertentu, diurutkan berdasarkan tanggal secara menurun
        $this->db->where('user_id', $user_id);
        // Mengurutkan hasil berdasarkan kolom 'tanggal' dalam urutan menurun (DESC)
        $this->db->order_by('tanggal', 'DESC');
        // Menjalankan query untuk mendapatkan laporan yang sesuai dengan kriteria di atas dan mengembalikan hasilnya sebagai array
        return $this->db->get('laporan')->result_array();
    }

    // Fungsi untuk mengambil laporan berdasarkan ID dan user_id, memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diakses
    public function get_by_id($id, $user_id) {
        // Mengambil laporan dengan ID tertentu yang juga dimiliki oleh pengguna dengan user_id tertentu, memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diakses
        return $this->db->get_where('laporan', ['id' => $id, 'user_id' => $user_id])->row_array();
    }

    // Fungsi untuk menambahkan laporan baru ke dalam database dengan data yang diberikan 
    public function insert($data) {
        // Menyimpan data laporan baru ke dalam tabel 'laporan' di database
        return $this->db->insert('laporan', $data);
    }

    // Fungsi untuk mengubah laporan yang sudah ada berdasarkan ID dan user_id, memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diubah
    public function update($id, $user_id, $data) {
        // Mengubah laporan yang sudah ada berdasarkan ID dan user_id, memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diubah
        $this->db->where('id', $id);
        // Memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diubah dengan menambahkan kondisi user_id
        $this->db->where('user_id', $user_id);
        // Menyimpan perubahan data laporan ke dalam tabel 'laporan' di database
        return $this->db->update('laporan', $data);
    }

    // Fungsi untuk menghapus laporan berdasarkan ID dan user_id, memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat dihapus
    public function hapus($id, $user_id) {
        // Menghapus laporan berdasarkan ID dan user_id, memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat dihapus 
        // dengan menambahkan kondisi user_id pada query delete untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat dihapus
        return $this->db->delete('laporan', ['id' => $id, 'user_id' => $user_id]);
    }
}
?>