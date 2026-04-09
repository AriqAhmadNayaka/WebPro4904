<?php
// File Laporan.php berisi class Laporan yang mengelola proses CRUD (Create, Read, Update, Delete) untuk laporan keuangan pengguna.
class Laporan {
    // Properti untuk menyimpan koneksi database yang akan digunakan dalam semua method CRUD
    private $db;

    // Konstruktor untuk menerima koneksi database dari luar class saat objek dibuat
    public function __construct($dbConnection) {
        // Menyimpan koneksi database yang diterima ke properti $db untuk digunakan dalam method lain
        $this->db = $dbConnection;
    }

    // Proses CREATE dan UPDATE
    public function simpan($postData, $fileData, $userId) {
        $id = $postData['id'] ?? null; // Jika id ada, berarti ini update, jika tidak ada berarti create
        $tanggal = $postData['tanggal'];
        $keterangan = $postData['keterangan'];
        $jenis = $postData['jenis'];
        $jumlah = $postData['jumlah'];
        $foto = "";

        // Logika Upload File (Foto)
        if (!empty($fileData["foto"]["name"])) {
            $target_dir = "uploads/";
            // Memastikan folder uploads ada
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // Mengambil nama file dan menentukan target path untuk menyimpan foto
            $foto = basename($fileData["foto"]["name"]);
            $target_file = $target_dir . $foto;
            // Validasi tipe file untuk memastikan hanya gambar yang diupload
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];

            // Jika tipe file valid, pindahkan file dari lokasi sementara ke folder uploads
            if (in_array($imageFileType, $allowedTypes)) {
                move_uploaded_file($fileData["foto"]["tmp_name"], $target_file);
            }
        }

        if (!empty($id)) {
            // Proses update
            if (!empty($foto)) {
                // Update menggunakan prepared statement untuk mencegah SQL Injection (mengganti foto)
                $stmt = $this->db->prepare("UPDATE laporan SET tanggal=?, keterangan=?, jenis=?, jumlah=?, foto=? WHERE id=? AND user_id=?");
                // Bind parameter dengan tipe data yang sesuai (s untuk string, i untuk integer)
                $stmt->bind_param("sssisii", $tanggal, $keterangan, $jenis, $jumlah, $foto, $id, $userId);
            } else {
                // Update data tanpa mengubah foto yang sudah ada sebelumnya
                $stmt = $this->db->prepare("UPDATE laporan SET tanggal=?, keterangan=?, jenis=?, jumlah=? WHERE id=? AND user_id=?");
                $stmt->bind_param("sssiii", $tanggal, $keterangan, $jenis, $jumlah, $id, $userId);
            }
        } else {
            // Proses CREATE
            $stmt = $this->db->prepare("INSERT INTO laporan (user_id, tanggal, keterangan, jenis, jumlah, foto) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssis", $userId, $tanggal, $keterangan, $jenis, $jumlah, $foto);
        }

        // Eksekusi query dan simpan hasilnya untuk menentukan apakah operasi berhasil atau tidak 
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Proses READ untuk menampilkan semua laporan milik pengguna tertentu, diurutkan berdasarkan tanggal terbaru
    public function tampilkanSemua($userId) {
        $stmt = $this->db->prepare("SELECT * FROM laporan WHERE user_id = ? ORDER BY tanggal DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        // Mengambil semua baris hasil query dan menyimpannya dalam array $data untuk dikembalikan ke pemanggil
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data; // Mengembalikan array yang berisi semua laporan milik pengguna tertentu
    }

    // Proses READ untuk menampilkan satu laporan berdasarkan ID dan user_id untuk memastikan keamanan data
    public function tampilkanSatu($id, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM laporan WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    // Proses DELETE untuk menghapus laporan berdasarkan ID dan user_id untuk memastikan hanya laporan milik pengguna yang bersangkutan yang bisa dihapus
    public function hapus($id, $userId) {
        $stmt = $this->db->prepare("DELETE FROM laporan WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $id, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>