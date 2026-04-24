<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model { // menggantikan class Laporan extends CI_Model di native

    public function __construct()
    {
        parent::__construct(); // constructor menerima koneksi dari luar class (di CI3 sudah otomatis)
        $this->load->database(); // simpan koneksi database ke properti class
    }

    // ambil semua data
    // menggantikan fungsi getAll($username) dari class Laporan native
    public function getAll($username) // fungsi mengambil semua data berdasarkan user
    {
        // jalankan query ke database MySQL (menggantikan mysqli_query di native)
        // ambil data urut terbaru
        $this->db->where('username', $username);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('laporan'); // ambil data urut terbaru
        return $query->result_array(); // kembalikan array hasil query
    }

    // ambil 1 data
    // menggantikan fungsi getById($id) dari class Laporan native
    public function getById($id) // fungsi mengambil satu data berdasarkan id
    {
        // jalankan query select satu data (menggantikan mysqli_query di native)
        $query = $this->db->get_where('laporan', array('id' => $id)); // ambil data sesuai id tertentu
        return $query->row_array(); // ubah hasil query jadi array (menggantikan mysqli_fetch_assoc)
    }

    // hapus data
    // menggantikan fungsi delete($id, $username) dari class Laporan native
    public function delete($id, $username) // fungsi untuk menghapus data laporan
    {
        // jalankan query delete database (menggantikan mysqli_query DELETE di native)
        $this->db->where('id', $id);
        $this->db->where('username', $username); // hapus data milik user tertentu
        return $this->db->delete('laporan');
    }

    // upload file
    // menggantikan fungsi uploadFile($file, $oldFile) dari class Laporan native
    public function uploadFile($file, $oldFile = '') // fungsi upload file ke folder server
    {
        if ($file['name'] != '') { // cek apakah file baru diupload user
            $namaFile = $file['name']; // ambil nama file yang diupload
            move_uploaded_file($file['tmp_name'], './uploads/' . $namaFile); // pindahkan file ke folder uploads
            return $namaFile; // kembalikan nama file baru disimpan
        }
        return $oldFile; // jika tidak upload gunakan file lama
    }

    // insert data
    // menggantikan fungsi insert($data, $file, $username) dari class Laporan native
    public function insert($data) // fungsi untuk menambah data baru laporan
    {
        // jalankan query insert ke database (menggantikan mysqli_query INSERT di native)
        return $this->db->insert('laporan', array(
            'tanggal'    => $data['tanggal'],    // tanggal transaksi dari form
            'keterangan' => $data['keterangan'], // keterangan transaksi dari form
            'jenis'      => $data['jenis'],      // jenis transaksi dari form
            'jumlah'     => $data['jumlah'],     // jumlah uang dari form
            'file'       => $data['file'],       // nama file yang sudah diupload
            'username'   => $data['username']    // username pemilik data dari session
        ));
    }

    // update data
    // menggantikan fungsi update($id, $data, $file, $username, $oldFile) dari class Laporan native
    public function update($id, $data, $username) // fungsi update data laporan berdasarkan id
    {
        // jalankan query update ke database (menggantikan mysqli_query UPDATE di native)
        $this->db->where('id', $id);
        $this->db->where('username', $username); // update hanya milik user tersebut
        return $this->db->update('laporan', array(
            'tanggal'    => $data['tanggal'],    // tanggal transaksi yang diperbarui
            'keterangan' => $data['keterangan'], // keterangan yang diperbarui
            'jenis'      => $data['jenis'],      // jenis transaksi yang diperbarui
            'jumlah'     => $data['jumlah'],     // jumlah yang diperbarui
            'file'       => $data['file']        // file baru atau tetap file lama
        ));
    }
}