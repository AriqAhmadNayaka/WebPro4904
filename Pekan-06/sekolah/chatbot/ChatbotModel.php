<?php
require_once __DIR__ . '/../../Database.php';

// Class ChatbotModel Child Class dari Database
// Sesuai modul 6.4.8: mewarisi $conn dari Database via extends
class ChatbotModel extends Database {

    // Folder penyimpanan file upload chatbot, private karena hanya dipakai di dalam class ini
    private $uploadDir;

    public function __construct($uploadDir) {
        parent::__construct();
        $this->uploadDir = $uploadDir;
    }

    // Method untuk menentukan balasan bot berdasarkan keyword pada pesan
    // Sesuai modul 6.4.4: method adalah fungsi yang ada di dalam class
    public function getBotReply($pesan, $adaUpload = false) {
        $msgLower = strtolower($pesan);

        if (strpos($msgLower, "status") !== false) {
            return "Saat ini sebagian besar siswa berada pada tahap <em>pelatihan aktif</em> dengan progres positif.";
        } elseif (strpos($msgLower, "laporan") !== false) {
            return "Laporan perkembangan dapat dilihat pada menu <em>Laporan Perkembangan</em> di dashboard sekolah.";
        } elseif (strpos($msgLower, "rekomendasi") !== false) {
            return "Berdasarkan data agregat, pelatihan vokasional dengan minat tertinggi adalah keterampilan praktis dan kewirausahaan.";
        } elseif ($adaUpload) {
            return "File berhasil diterima! Aku akan sampaikan ke tim terkait untuk ditindaklanjuti.";
        } else {
            return "Maaf, aku belum memahami pertanyaan itu.";
        }
    }

    // Method untuk menyimpan riwayat file yang diunggah ke tabel chat_uploads
    // INSERT INTO sesuai materi CRUD modul 4.4.2
    public function simpanUpload($namaFile, $pesan) {
        $sql  = "INSERT INTO chat_uploads (nama_file, pesan) VALUES (?, ?)";
        $stmt = $this->conn->prepare(query: $sql);
        // "ss" = 2 parameter bertipe string
        $stmt->bind_param("ss", $namaFile, $pesan);
        $hasil = $stmt->execute();
        $stmt->close();
        return $hasil;
    }

    // Method untuk upload file dari chatbot sesuai modul 5.4.9
    // Mengembalikan nama file jika berhasil, string error jika gagal, null jika tidak ada file
    public function uploadFile($fileData) {
        if (!isset($fileData) || $fileData['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return 'ERROR:Terjadi error saat upload file';
        }

        // Dapatkan ekstensi file dan ubah ke huruf kecil
        $ext = strtolower(pathinfo(path: $fileData['name'], flags: PATHINFO_EXTENSION));
        // Format yang diizinkan di chatbot: gambar dan PDF dokumen laporan
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

        // getimagesize() hanya untuk verifikasi gambar, PDF tidak perlu
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
        $check = $isImage ? getimagesize(filename: $fileData['tmp_name']) : true;

        if ($check === false || !in_array(needle: $ext, haystack: $allowedTypes)) {
            return 'ERROR:Format file tidak valid. Gunakan JPG, PNG, atau PDF';
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

        $namaFile   = ($maxNum + 1) . '.' . $ext;
        $targetFile = $this->uploadDir . $namaFile;

        // move_uploaded_file() memindahkan file dari folder sementara ke folder uploads
        if (!move_uploaded_file(from: $fileData['tmp_name'], to: $targetFile)) {
            return 'ERROR:Gagal menyimpan file ke server';
        }

        return $namaFile;
    }
}