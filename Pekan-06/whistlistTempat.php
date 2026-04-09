<?php
include 'Database.php'; // menghubungkan ke class Database
class Wishlist {
    private $conn; // untuk koneksi database
    // constructor: otomatis jalan saat objek dibuat
    public function __construct() {
        $db = new Database(); // buat objek database
        $this->conn = $db->getConnection(); // ambil koneksi
    }
    // method untuk menambah data wishlist
    public function tambah($nama, $deskripsi, $lokasi, $harga, $gambar) {
        // ambil data gambar
        $namaFile = $gambar['name']; // nama file asli
        $tmp = $gambar['tmp_name'];  // lokasi sementara file
        // buat nama file baru biar tidak sama
        $namaBaru = time() . "_" . $namaFile;
        // tentukan folder tujuan
        $folder = "upload/" . $namaBaru;
        // pindahkan file ke folder upload
        if (move_uploaded_file($tmp, $folder)) {
            // jika berhasil, simpan ke database
            $query = "INSERT INTO wishlist_wisata (nama, deskripsi, lokasi, harga, gambar)
                      VALUES ('$nama', '$deskripsi', '$lokasi', '$harga', '$namaBaru')";
            return $this->conn->query($query);
        } else {
            return false;
        }
    }
    // method untuk menampilkan semua data
    public function tampil() {
        $query = "SELECT * FROM wishlist_wisata";
        return $this->conn->query($query);
    }
    // method untuk menghapus data
    public function hapus($id) {
        // ambil data gambar berdasarkan id
        $data = $this->conn->query("SELECT gambar FROM wishlist_wisata WHERE id='$id'");
        $row = $data->fetch_assoc();
        // hapus file gambar dari folder
        if (file_exists("upload/" . $row['gambar'])) {
            unlink("upload/" . $row['gambar']);
        }
        // hapus data dari database
        return $this->conn->query("DELETE FROM wishlist_wisata WHERE id='$id'");
    }
}
?>