<?php
require_once 'Database.php';

/**
 * Kelas Proyek
 * 
 * Menangani semua operasi CRUD (Create, Read, Update, Delete)
 * untuk data proyek, termasuk proses upload file.
 * 
 * Setiap method mewakili satu aksi — sesuai prinsip Single
 * Responsibility (SRP) pada OOP: satu kelas punya satu tanggung jawab.
 */
class Proyek {
    // Koneksi database
    private mysqli $conn;

    // Folder tujuan upload file
    private string $uploadDir = "uploads/";

    /**
     * Constructor menerima koneksi dari luar (Dependency Injection).
     */
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /**
     * Mengambil semua data proyek dari database.
     * 
     * @return mysqli_result — hasil query yang bisa di-loop
     */
    public function getAll(): mysqli_result {
        return mysqli_query($this->conn, "SELECT * FROM proyek");
    }

    /**
     * Mengambil satu data proyek berdasarkan ID (untuk mode Edit).
     * 
     * @param int $id — ID proyek yang ingin diedit
     * @return array — data proyek sebagai associative array
     */
    public function getById(int $id): array {
        $result = mysqli_query(
            $this->conn,
            "SELECT * FROM proyek WHERE id = $id"
        );
        return mysqli_fetch_assoc($result);
    }

    /**
     * Menambahkan proyek baru ke database (Create).
     * Juga menangani upload file jika ada.
     * 
     * @param string $nama      — Nama proyek
     * @param string $deskripsi — Deskripsi proyek
     * @param array  $file      — Data file dari $_FILES
     * @return void
     */
    public function create(string $nama, string $deskripsi, array $file): void {
        // Proses upload dan dapatkan nama file
        $namaFile = $this->uploadFile($file);

        mysqli_query(
            $this->conn,
            "INSERT INTO proyek VALUES ('', '$nama', '$deskripsi', '$namaFile')"
        );
    }

    /**
     * Memperbarui data proyek yang sudah ada (Update).
     * Jika ada file baru di-upload, gantikan file lama.
     * 
     * @param int    $id        — ID proyek yang diperbarui
     * @param string $nama      — Nama proyek baru
     * @param string $deskripsi — Deskripsi proyek baru
     * @param array  $file      — Data file dari $_FILES
     * @param string $fileLama  — Nama file lama (jika tidak ada upload baru)
     * @return void
     */
    public function update(int $id, string $nama, string $deskripsi, array $file, string $fileLama): void {
        // Jika ada file baru, upload; jika tidak, pakai file lama
        $namaFile = $file['name'] ? $this->uploadFile($file) : $fileLama;

        mysqli_query(
            $this->conn,
            "UPDATE proyek SET nama_proyek='$nama', deskripsi='$deskripsi', nama_file='$namaFile' WHERE id=$id"
        );
    }

    /**
     * Menghapus proyek berdasarkan ID (Delete).
     * 
     * @param int $id — ID proyek yang akan dihapus
     * @return void
     */
    public function delete(int $id): void {
        mysqli_query($this->conn, "DELETE FROM proyek WHERE id = $id");
    }

    /**
     * Method private untuk mengurus proses upload file.
     * Dipanggil oleh create() dan update() — tidak perlu diakses dari luar.
     * 
     * @param array $file — Data file dari $_FILES['berkas']
     * @return string     — Nama file yang berhasil di-upload
     */
    private function uploadFile(array $file): string {
        $namaFile = $file['name'];
        $tmpName  = $file['tmp_name'];

        if ($namaFile) {
            // Pindahkan file dari folder sementara ke folder uploads/
            move_uploaded_file($tmpName, $this->uploadDir . $namaFile);
        }

        return $namaFile;
    }
}
?>
