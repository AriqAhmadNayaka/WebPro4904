<?php
class Keuangan {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // 1. Fungsi CREATE (Simpan Data Saja)
    public function simpanData($tgl, $ket, $jenis, $jml) {
        // Query tanpa kolom bukti_transaksi
        $sql = "INSERT INTO keuangan (tanggal, keterangan, jenis, jumlah) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            die("Error Prepare: " . $this->db->error);
        }

        // 'sssi' = String, String, String, Integer
        $stmt->bind_param("sssi", $tgl, $ket, $jenis, $jml);
        
        if ($stmt->execute()) {
            return true;
        } else {
            die("Error Execute: " . $stmt->error);
        }
    }

    // 2. Fungsi READ (Tampil Data)
    public function getAll() {
        return $this->db->query("SELECT * FROM keuangan ORDER BY id DESC");
    }

    // 3. Fungsi DELETE (Hapus Data Saja)
    public function hapus($id) {
        return $this->db->query("DELETE FROM keuangan WHERE id=$id");
    }
}
?>