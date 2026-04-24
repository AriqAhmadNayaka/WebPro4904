<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model { // menggantikan class User extends CI_Model di native

    public function __construct()
    {
        parent::__construct(); // constructor otomatis saat objek dibuat
        $this->load->database(); // inisialisasi koneksi database ke class
    }

    // cek username sudah ada atau belum
    // menggantikan fungsi cekUsername($username) dari class User native
    public function cekUsername($username) // fungsi untuk cek username di database
    {
        // jalankan query ke database (menggantikan mysqli_query di native)
        // query cek username sudah ada (CI3 Active Record otomatis escape SQL injection)
        $result = $this->db->get_where('user', array('username' => $username));
        return $result->num_rows() > 0; // true jika username sudah ditemukan
    }

    // insert data user
    // menggantikan fungsi register($data) dari class User native
    public function register($data) // fungsi untuk menyimpan data user baru
    {
        // jalankan query insert ke database (menggantikan mysqli_query INSERT di native)
        return $this->db->insert('user', array(
            'name'     => $data['name'],     // ambil input nama dari form
            'email'    => $data['email'],    // ambil input email dari form
            'username' => $data['username'], // ambil input username dari form
            'password' => $data['password'], // ambil input password dari form
            'telepon'  => $data['telepon'],  // ambil input nomor telepon user
            'alamat'   => $data['alamat']    // ambil input alamat dari form
        ));
    }
}