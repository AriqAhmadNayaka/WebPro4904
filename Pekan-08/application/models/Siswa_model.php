<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model: Siswa_model
// Bertanggung jawab atas semua operasi data siswa (tabel 'siswa'):
//   - getAll()     : Ambil semua siswa dengan filter & pencarian
//   - getById()    : Ambil satu siswa berdasarkan ID
//   - tambah()     : Insert data siswa baru
//   - update()     : Update data siswa yang sudah ada
//   - hapus()      : Hapus data siswa beserta foto-nya
//   - uploadFoto() : Proses upload file foto siswa ke server
//   - testInput()  : Sanitasi input
//
// Perbedaan utama dari versi native:
//   - Menggunakan $this->db (CI3 Active Record) bukan $this->conn (mysqli)
//   - Path upload menggunakan FCPATH (konstanta CI3) bukan __DIR__

class Siswa_model extends CI_Model {

    // Path direktori tempat foto siswa disimpan di server.
    // FCPATH adalah konstanta CI3 yang menunjuk ke root folder project
    // (folder yang berisi index.php CI3, bukan folder application/).
    // Sehingga path lengkapnya: [root_project]/uploads/siswa/
    //
    // Dari: __DIR__ . '/uploads/' (relatif ke file SiswaModel.php di versi native)
    // Ke  : FCPATH . 'uploads/siswa/' (path absolut dari root project CI3)
    private $uploadDir;

    public function __construct() {
        parent::__construct();
        // Inisialisasi path upload menggunakan konstanta FCPATH CI3
        $this->uploadDir = FCPATH . 'uploads/siswa/';
    }

    // METHOD: testInput($data)
    // Sanitasi input: trim() - stripslashes() - htmlspecialchars()
    // Mencegah XSS dan karakter berbahaya dari input user.
    public function testInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // METHOD: getAll($filterKelas, $filterStatus, $keyword)
    // Mengambil semua data siswa dari tabel 'siswa' dengan
    // dukungan filter kelas, filter status, dan pencarian keyword.
    // Return: array of arrays (setiap elemen = satu baris data siswa)
    //
    // Dari: WHERE 1=1 dengan bind_param dinamis di versi native
    // Ke  : CI3 Active Record yang di-chain sebelum query dieksekusi
    public function getAll($filterKelas = '', $filterStatus = '', $keyword = '') {
        // Tambahkan kondisi WHERE hanya jika filter diisi (Active Record CI3 bersifat chainable)
        if (!empty($filterKelas))  $this->db->where('kelas', $filterKelas);
        if (!empty($filterStatus)) $this->db->where('status', $filterStatus);

        if (!empty($keyword)) {
            // Pencarian di beberapa kolom dengan OR LIKE
            // group_start() dan group_end() membuat kurung: (nama LIKE '%kw%' OR nisn LIKE '%kw%' OR kelas LIKE '%kw%')
            // Ini penting agar kondisi OR tidak mempengaruhi filter WHERE yang lain
            $this->db->group_start();
            $this->db->like('nama', $keyword);      // nama LIKE '%keyword%'
            $this->db->or_like('nisn', $keyword);   // OR nisn LIKE '%keyword%'
            $this->db->or_like('kelas', $keyword);  // OR kelas LIKE '%keyword%'
            $this->db->group_end();
        }

        // Urutkan berdasarkan ID ascending (data terlama di atas)
        $this->db->order_by('id', 'ASC');

        // Eksekusi query ke tabel 'siswa' dan kembalikan semua baris sebagai array of arrays
        return $this->db->get('siswa')->result_array();
    }

    // METHOD: getById($id)
    // Mengambil satu data siswa berdasarkan ID-nya.
    // Return: array asosiatif satu baris data siswa, atau null jika tidak ditemukan.
    //
    // Dari: SELECT * FROM siswa WHERE id = ? (bind_param "i")
    // Ke  : $this->db->get_where() dengan array kondisi yang otomatis menggunakan prepared statement
    public function getById($id) {
        // get_where() menghasilkan: SELECT * FROM siswa WHERE id = $id
        // row_array() mengambil satu baris pertama sebagai array asosiatif
        return $this->db->get_where('siswa', array('id' => $id))->row_array();
    }

    // METHOD: tambah(...)
    // Menyimpan data siswa baru ke tabel 'siswa'.
    // Return: true jika berhasil, false jika gagal.
    //
    // Dari: INSERT INTO siswa (...) VALUES (?, ...) dengan bind_param "sssssssss"
    // Ke  : $this->db->insert() — CI3 Active Record otomatis escape semua nilai
    public function tambah($nisn, $nama, $kelas, $jk, $email, $telp, $alamat, $status, $foto) {
        // Array asosiatif: key = nama kolom tabel 'siswa', value = data yang akan diinsert
        $data = array(
            'nisn'          => $nisn,
            'nama'          => $nama,
            'kelas'         => $kelas,
            'jenis_kelamin' => $jk,
            'email'         => $email,
            'no_telepon'    => $telp,
            'alamat'        => $alamat,
            'status'        => $status,
            'foto'          => $foto,   // Nama file foto (null jika tidak ada foto)
        );
        return $this->db->insert('siswa', $data);
    }

    // METHOD: update(...)
    // Memperbarui data siswa yang sudah ada di tabel 'siswa'.
    // Jika ada foto baru yang di-upload ($fotoBaru !== null):
    //   - Hapus file foto lama dari server (unlink)
    //   - Simpan nama foto baru ke DB
    // Jika tidak ada foto baru, kolom 'foto' tidak diubah.
    //
    // Logika hapus foto lama TIDAK BERUBAH — tetap pakai file_exists + unlink
    // Hanya cara update DB yang berubah ke CI3 Active Record
    public function update($id, $nisn, $nama, $kelas, $jk, $email, $telp, $alamat, $status, $fotoLama, $fotoBaru) {
        if ($fotoBaru !== null) {
            // Ada foto baru - hapus foto lama dari filesystem server (jika ada)
            if ($fotoLama && file_exists($this->uploadDir . $fotoLama)) {
                unlink($this->uploadDir . $fotoLama); // Hapus file foto lama
            }
            // Data update termasuk kolom 'foto' dengan nama file baru
            $data = array(
                'nisn' => $nisn, 'nama' => $nama, 'kelas' => $kelas,
                'jenis_kelamin' => $jk, 'email' => $email,
                'no_telepon' => $telp, 'alamat' => $alamat,
                'status' => $status, 'foto' => $fotoBaru,
            );
        } else {
            // Tidak ada foto baru - kolom 'foto' tidak disertakan dalam update
            // sehingga foto lama tetap dipertahankan di DB
            $data = array(
                'nisn' => $nisn, 'nama' => $nama, 'kelas' => $kelas,
                'jenis_kelamin' => $jk, 'email' => $email,
                'no_telepon' => $telp, 'alamat' => $alamat,
                'status' => $status,
            );
        }

        // Tentukan baris yang akan diupdate berdasarkan ID
        $this->db->where('id', $id);
        // Eksekusi query UPDATE
        return $this->db->update('siswa', $data);
    }

    // METHOD: hapus($id)
    // Menghapus data siswa dari tabel 'siswa' sekaligus menghapus
    // file foto-nya dari server (jika ada).
    //
    // Dari: DELETE FROM siswa WHERE id=? — logika hapus foto TIDAK BERUBAH
    public function hapus($id) {
        // Ambil dulu data siswa untuk mendapatkan nama file foto-nya
        $foto = ($this->getById($id))['foto'] ?? null;

        // Hapus file foto dari filesystem jika ada
        if ($foto && file_exists($this->uploadDir . $foto)) {
            unlink($this->uploadDir . $foto); // Hapus file secara permanen dari server
        }

        // Hapus baris data siswa dari tabel
        $this->db->where('id', $id);
        return $this->db->delete('siswa');
    }

    // METHOD: uploadFoto($fileData)
    // Memproses upload foto siswa dari $_FILES ke server.
    // Return:
    //   - null          : Tidak ada file yang di-upload (field foto kosong)
    //   - 'ERROR:...'  : Gagal upload (error ditulis setelah prefix 'ERROR:')
    //   - string        : Nama file yang berhasil disimpan (contoh: '3.jpg')
    //
    // Logika penamaan file: urutan numerik (1.jpg, 2.jpg, 3.jpg, dst.)
    // berdasarkan file terbesar yang sudah ada di folder upload.
    public function uploadFoto($fileData) {
        // Jika tidak ada file yang dikirim atau field tidak di-upload (UPLOAD_ERR_NO_FILE)
        if (!isset($fileData) || $fileData['error'] === UPLOAD_ERR_NO_FILE) return null;

        // Jika ada error upload selain "tidak ada file" (misal: ukuran terlalu besar)
        if ($fileData['error'] !== UPLOAD_ERR_OK) return 'ERROR:Terjadi error saat upload foto';

        // Ambil ekstensi file dari nama aslinya dan ubah ke huruf kecil
        $ext          = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'jpeg', 'png'); // Tipe file yang diizinkan

        // Validasi file menggunakan getimagesize() (lebih aman dari cek ekstensi saja)
        // getimagesize() mengembalikan false jika file bukan gambar valid
        $check = getimagesize($fileData['tmp_name']);

        if ($check === false || !in_array($ext, $allowedTypes)) {
            return 'ERROR:Format foto tidak valid. Gunakan JPG, JPEG, atau PNG';
        }

        // Buat direktori upload jika belum ada
        // 0755 = permission rwxr-xr-x, true = buat folder induk jika perlu
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0755, true);

        // Tentukan nomor urut nama file baru:
        // Ambil semua file yang ada, cari nomor terbesar, lalu +1
        $files  = glob($this->uploadDir . "*.*"); // Daftar semua file di folder upload
        $maxNum = 0;
        foreach ($files as $file) {
            $nama = pathinfo($file, PATHINFO_FILENAME); // Ambil nama file tanpa ekstensi
            if (is_numeric($nama) && (int)$nama > $maxNum) $maxNum = (int)$nama;
        }

        // Nama file baru: (nomor terakhir + 1) + ekstensi, contoh: "4.png"
        $namaFile   = ($maxNum + 1) . '.' . $ext;
        $targetFile = $this->uploadDir . $namaFile;

        // Pindahkan file dari folder sementara (tmp) ke folder upload permanen
        if (!move_uploaded_file($fileData['tmp_name'], $targetFile)) {
            return 'ERROR:Gagal menyimpan foto ke server';
        }

        // Kembalikan nama file yang berhasil disimpan
        return $namaFile;
    }
}
