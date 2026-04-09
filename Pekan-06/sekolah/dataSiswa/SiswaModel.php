<?php
require_once __DIR__ . '/../../Database.php';

// Class SiswaModel, Child Class dari Database
// Sesuai modul 6.4.8: SiswaModel mewarisi koneksi $conn dari Database
// sehingga semua method di sini langsung bisa pakai $this->conn
class SiswaModel extends Database {

    // Folder penyimpanan foto siswa — private karena hanya dipakai di dalam class ini
    // Sesuai modul 6.4.7: private hanya bisa diakses dari dalam class itu sendiri
    private $uploadDir;

    // Konstruktor SiswaModel: panggil konstruktor parent (Database) terlebih dahulu
    // lalu inisialisasi properti khusus class ini
    // Sesuai modul 6.4.8: parent::__construct() memanggil konstruktor kelas induk
    public function __construct($uploadDir) {
        parent::__construct();
        $this->uploadDir = $uploadDir;
    }

    // Membersihkan input dari spasi berlebih, backslash, dan karakter berbahaya (XSS)
    public function testInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // READ: Ambil semua siswa dengan filter dinamis
    // Sesuai konsep CRUD modul 4.4.4: READ menampilkan data dari database
    public function getAll($filterKelas = '', $filterStatus = '', $keyword = '') {
        // WHERE 1=1 sebagai base agar kondisi AND bisa ditambahkan secara fleksibel
        $sql = "SELECT * FROM siswa WHERE 1=1";
        $params = [];
        $types = "";

        if (!empty($filterKelas)) {
            $sql .= " AND kelas = ?";
            $params[] = $filterKelas;
            $types .= "s";
        }
        if (!empty($filterStatus)) {
            $sql .= " AND status = ?";
            $params[] = $filterStatus;
            $types .= "s";
        }
        if (!empty($keyword)) {
            // LIKE untuk pencarian sebagian teks
            $like = "%$keyword%";
            $sql .= " AND (nama LIKE ? OR nisn LIKE ? OR kelas LIKE ?)";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= "sss";
        }

        $sql .= " ORDER BY id ASC";
        $stmt = $this->conn->prepare(query: $sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        // fetch_all() mengambil semua baris sekaligus sebagai array asosiatif
        $result = $stmt->get_result();
        $data   = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    // READ: Ambil satu siswa berdasarkan id (untuk modal edit)
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM siswa WHERE id = ?");
        // "i" = parameter bertipe integer karena id bertipe INT di database
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $data;
    }

    // CREATE: INSERT siswa baru ke database sesuai modul 4.4.2
    public function tambah($nisn, $nama, $kelas, $jk, $email, $telp, $alamat, $status, $foto) {
        $sql = "INSERT INTO siswa (nisn, nama, kelas, jenis_kelamin, email, no_telepon, alamat, status, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare(query: $sql);
        // "sssssssss" = 9 parameter bertipe string
        $stmt->bind_param("sssssssss", $nisn, $nama, $kelas, $jk, $email, $telp, $alamat, $status, $foto);
        $hasil = $stmt->execute();
        $stmt->close();
        return $hasil;
    }

    // UPDATE: perbarui data siswa, dengan atau tanpa mengganti foto
    // Sesuai modul 4.4.5: UPDATE menggunakan WHERE id=? sebagai kunci
    public function update($id, $nisn, $nama, $kelas, $jk, $email, $telp, $alamat, $status, $fotoLama, $fotoBaru) {
        if ($fotoBaru !== null) {
            // Hapus foto lama dari folder jika ada, sebelum menyimpan yang baru
            if ($fotoLama && file_exists($this->uploadDir . $fotoLama)) {
                unlink($this->uploadDir . $fotoLama);
            }
            // UPDATE termasuk kolom foto — "sssssssssi" = 9 string + 1 integer
            $sql  = "UPDATE siswa SET nisn=?, nama=?, kelas=?, jenis_kelamin=?, email=?, no_telepon=?, alamat=?, status=?, foto=? WHERE id=?";
            $stmt = $this->conn->prepare(query: $sql);
            $stmt->bind_param("sssssssssi", $nisn, $nama, $kelas, $jk, $email, $telp, $alamat, $status, $fotoBaru, $id);
        } else {
            // UPDATE tanpa mengubah foto — "ssssssssi" = 8 string + 1 integer
            $sql  = "UPDATE siswa SET nisn=?, nama=?, kelas=?, jenis_kelamin=?, email=?, no_telepon=?, alamat=?, status=? WHERE id=?";
            $stmt = $this->conn->prepare(query: $sql);
            $stmt->bind_param("ssssssssi", $nisn, $nama, $kelas, $jk, $email, $telp, $alamat, $status, $id);
        }
        $hasil = $stmt->execute();
        $stmt->close();
        return $hasil;
    }

    // DELETE: hapus siswa berdasarkan id, termasuk foto dari folder
    // Sesuai modul 4.4.5: DELETE menggunakan WHERE id=?
    public function hapus($id) {
        // Ambil nama foto siswa sebelum dihapus agar file-nya juga ikut terhapus
        $foto = $this->getById($id)['foto'] ?? null;
        if ($foto && file_exists($this->uploadDir . $foto)) {
            unlink($this->uploadDir . $foto);
        }

        $sql  = "DELETE FROM siswa WHERE id=?";
        $stmt = $this->conn->prepare(query: $sql);
        // "i" = parameter bertipe integer
        $stmt->bind_param("i", $id);
        $hasil = $stmt->execute();
        $stmt->close();
        return $hasil;
    }

    // Upload foto siswa sesuai modul 5.4.9
    // Mengembalikan nama file baru jika berhasil, null jika tidak ada file, string error jika gagal
    public function uploadFoto($fileData) {
        // Cek apakah file dikirim dan tidak error
        if (!isset($fileData) || $fileData['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return 'ERROR:Terjadi error saat upload foto';
        }

        // Dapatkan ekstensi dan ubah ke huruf kecil agar perbandingan tidak case-sensitive
        $ext          = strtolower(pathinfo(path: $fileData['name'], flags: PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png'];

        // getimagesize() memverifikasi file benar-benar gambar, bukan file lain yang disamarkan
        $check = getimagesize(filename: $fileData['tmp_name']);
        if ($check === false || !in_array(needle: $ext, haystack: $allowedTypes)) {
            return 'ERROR:Format foto tidak valid. Gunakan JPG, JPEG, atau PNG';
        }

        // Buat folder uploads jika belum ada
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0755, true);

        // Penomoran otomatis untuk menghindari duplikasi nama file sesuai modul 5.4.9
        $files  = glob(pattern: $this->uploadDir . "*.*");
        $maxNum = 0;
        foreach ($files as $file) {
            $nama = pathinfo(path: $file, flags: PATHINFO_FILENAME);
            if (is_numeric(value: $nama) && (int)$nama > $maxNum) {
                $maxNum = (int)$nama;
            }
        }

        // Nama file baru = angka tertinggi + 1
        $namaFile   = ($maxNum + 1) . '.' . $ext;
        $targetFile = $this->uploadDir . $namaFile;

        // move_uploaded_file() memindahkan file dari folder sementara ke folder uploads
        if (!move_uploaded_file(from: $fileData['tmp_name'], to: $targetFile)) {
            return 'ERROR:Gagal menyimpan foto ke server';
        }

        return $namaFile;
    }
}