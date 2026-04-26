<?php
class Warga {
    // Simpan koneksi database biar semua method bisa pakai.
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    // Ambil semua data warga buat ditampilin ke tabel.
    public function getAll() {
        return mysqli_query($this->db, "SELECT * FROM warga");
    }

    // Simpan data warga baru ke database.
    public function create($nama, $alamat, $nohp, $fileName) {
        $nama = mysqli_real_escape_string($this->db, $nama);
        $alamat = mysqli_real_escape_string($this->db, $alamat);
        $nohp = mysqli_real_escape_string($this->db, $nohp);
        
        $sql = "INSERT INTO warga (nama, alamat, nohp, file) VALUES ('$nama', '$alamat', '$nohp', '$fileName')";
        return mysqli_query($this->db, $sql);
    }

    // Hapus data warga berdasarkan id.
    public function delete($id) {
        $id = mysqli_real_escape_string($this->db, $id);
        return mysqli_query($this->db, "DELETE FROM warga WHERE id='$id'");
    }

    // Update data warga yang sudah ada.
    public function update($id, $nama, $alamat, $nohp) {
        $id = mysqli_real_escape_string($this->db, $id);
        $nama = mysqli_real_escape_string($this->db, $nama);
        $alamat = mysqli_real_escape_string($this->db, $alamat);
        $nohp = mysqli_real_escape_string($this->db, $nohp);

        $sql = "UPDATE warga SET nama='$nama', alamat='$alamat', nohp='$nohp' WHERE id='$id'";
        return mysqli_query($this->db, $sql);
    }
}
?>
