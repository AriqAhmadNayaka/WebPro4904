<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model: Chatbot_model
// Menyediakan logika untuk fitur chatbot InkluBot:
//   - getBotReply()   : Menghasilkan respons bot berdasarkan kata kunci pesan
//   - simpanUpload()  : Menyimpan catatan file upload ke tabel 'chat_uploads'
//   - uploadFile()    : Memproses upload file (gambar/PDF) ke server
//
// Perbedaan dari versi native:
//   - simpanUpload() menggunakan $this->db->insert() bukan mysqli prepared statement
//   - Path upload menggunakan FCPATH . 'uploads/chatbot/' (konstanta CI3)

class Chatbot_model extends CI_Model {

    // Path direktori untuk menyimpan file yang di-upload melalui chatbot.
    // FCPATH menunjuk ke root project CI3 (tempat index.php berada).
    // Sehingga path lengkap: [root_project]/uploads/chatbot/
    private $uploadDir;

    public function __construct() {
        parent::__construct();
        // Inisialisasi path upload dengan konstanta FCPATH CI3
        $this->uploadDir = FCPATH . 'uploads/chatbot/';
    }

    // METHOD: getBotReply($pesan, $adaUpload)
    // Menghasilkan respons chatbot (dummy/statis) berdasarkan kata kunci
    // yang terdapat dalam pesan user.
    //
    // $pesan     : string pesan yang dikirim user
    // $adaUpload : bool, true jika user juga mengirim file bersamaan
    //
    // Return: string HTML respons bot
    //
    // Ini adalah logika pure PHP — tidak ada perubahan dari versi native
    public function getBotReply($pesan, $adaUpload = false) {
        // Ubah pesan ke huruf kecil agar pencocokan kata kunci tidak case-sensitive
        $msgLower = strtolower($pesan);

        if (strpos($msgLower, "status") !== false) {
            // Kata kunci "status" ditemukan dalam pesan
            return "Saat ini sebagian besar siswa berada pada tahap <em>pelatihan aktif</em> dengan progres positif.";
        } elseif (strpos($msgLower, "laporan") !== false) {
            // Kata kunci "laporan" ditemukan
            return "Laporan perkembangan dapat dilihat pada menu <em>Laporan Perkembangan</em> di dashboard sekolah.";
        } elseif (strpos($msgLower, "rekomendasi") !== false) {
            // Kata kunci "rekomendasi" ditemukan
            return "Berdasarkan data agregat, pelatihan vokasional dengan minat tertinggi adalah keterampilan praktis dan kewirausahaan.";
        } elseif ($adaUpload) {
            // Tidak ada kata kunci cocok, tapi ada file yang di-upload
            return "File berhasil diterima! Aku akan sampaikan ke tim terkait untuk ditindaklanjuti.";
        } else {
            // Tidak ada kata kunci yang cocok dan tidak ada upload
            return "Maaf, aku belum memahami pertanyaan itu.";
        }
    }

    // METHOD: simpanUpload($namaFile, $pesan)
    // Menyimpan catatan file yang berhasil di-upload ke tabel 'chat_uploads'.
    // Berguna untuk keperluan audit/tracking file yang dikirim via chatbot.
    //
    // $namaFile : nama file yang berhasil disimpan di server (misal: '2.pdf')
    // $pesan    : pesan teks yang dikirim bersamaan dengan file (bisa kosong)
    //
    // Dari: INSERT INTO chat_uploads dengan $this->conn->prepare + bind_param "ss"
    // Ke  : $this->db->insert() — lebih ringkas, tetap aman dari SQL Injection
    public function simpanUpload($namaFile, $pesan) {
        // Array asosiatif: key = nama kolom tabel, value = data yang diinsert
        $data = array(
            'nama_file' => $namaFile, // Nama file yang disimpan di server
            'pesan'     => $pesan,    // Pesan teks yang dikirim user bersama file
        );
        return $this->db->insert('chat_uploads', $data);
    }

    // METHOD: uploadFile($fileData)
    // Memproses upload file chatbot (gambar atau PDF) dari $_FILES ke server.
    // File yang diizinkan: jpg, jpeg, png, pdf.
    //
    // $fileData : elemen dari $_FILES['chatFile'] (array info file upload)
    //
    // Return:
    //   - null         : Tidak ada file yang dikirim (UPLOAD_ERR_NO_FILE)
    //   - 'ERROR:...' : Gagal upload (pesan error setelah prefix 'ERROR:')
    //   - string       : Nama file yang berhasil disimpan (contoh: '3.pdf')
    //
    // Logika penamaan file: numerik urut (1.jpg, 2.pdf, 3.png, dst.)
    // Logika uploadFile TIDAK BERUBAH dari versi native —
    // hanya $this->uploadDir yang menggunakan FCPATH CI3
    public function uploadFile($fileData) {
        // Tidak ada file yang di-upload - return null (bukan error)
        if (!isset($fileData) || $fileData['error'] === UPLOAD_ERR_NO_FILE) return null;

        // Ada error saat upload (selain "tidak ada file")
        if ($fileData['error'] !== UPLOAD_ERR_OK) return 'ERROR:Terjadi error saat upload file';

        // Ambil ekstensi file dan normalisasi ke huruf kecil
        $ext          = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'jpeg', 'png', 'pdf'); // Tipe file yang diperbolehkan

        // Untuk file gambar, validasi menggunakan getimagesize() (lebih aman dari cek ekstensi saja)
        // Untuk PDF, cukup cek ekstensinya saja (getimagesize() akan mengembalikan false pada PDF)
        $isImage = in_array($ext, array('jpg', 'jpeg', 'png'));
        $check   = $isImage ? getimagesize($fileData['tmp_name']) : true;

        if ($check === false || !in_array($ext, $allowedTypes)) {
            return 'ERROR:Format file tidak valid. Gunakan JPG, PNG, atau PDF';
        }

        // Buat direktori upload jika belum ada
        // 0755 = rwxr-xr-x, parameter true memungkinkan pembuatan folder bertingkat
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0755, true);

        // Tentukan nama file baru dengan nomor urut
        // Scan semua file yang sudah ada, ambil nomor terbesar, lalu gunakan +1
        $files  = glob($this->uploadDir . "*.*"); // Daftar semua file yang ada di folder
        $maxNum = 0;
        foreach ($files as $file) {
            $nama = pathinfo($file, PATHINFO_FILENAME); // Nama file tanpa ekstensi
            if (is_numeric($nama) && (int)$nama > $maxNum) $maxNum = (int)$nama;
        }

        // Nama file baru: angka urut + ekstensi, contoh: "4.pdf"
        $namaFile   = ($maxNum + 1) . '.' . $ext;
        $targetFile = $this->uploadDir . $namaFile;

        // Pindahkan file dari folder temporary PHP ke lokasi penyimpanan permanen
        if (!move_uploaded_file($fileData['tmp_name'], $targetFile)) {
            return 'ERROR:Gagal menyimpan file ke server';
        }

        // Kembalikan nama file yang berhasil disimpan
        return $namaFile;
    }
}
