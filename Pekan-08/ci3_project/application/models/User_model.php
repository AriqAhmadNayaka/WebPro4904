<?php
// Mendefinisikan model User_model yang akan digunakan untuk operasi terkait pengguna, seperti registrasi dan login 
defined('BASEPATH') OR exit('No direct script access allowed'); 

// User_model adalah model yang menangani operasi terkait pengguna, seperti registrasi dan login
class User_model extends CI_Model {
    
    // Fungsi untuk mendaftarkan pengguna baru ke dalam database
    public function register($data) {
        // Menyimpan data pengguna baru ke dalam tabel 'users' di database
        return $this->db->insert('users', $data);
    }

    // Fungsi untuk memverifikasi kredensial pengguna saat login
    public function login($username, $password) {
        // Mencari pengguna dengan username dan password yang sesuai di tabel 'users'
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        // Menjalankan query untuk mendapatkan pengguna yang cocok
        $query = $this->db->get('users');
        
        // Jika ditemukan pengguna yang cocok, kembalikan data pengguna sebagai array, jika tidak, kembalikan false
        if ($query->num_rows() > 0) {
            // Mengembalikan data pengguna yang ditemukan sebagai array 
            return $query->row_array();
        }
        // Jika tidak ditemukan pengguna yang cocok, kembalikan false
        return false;
    }
}