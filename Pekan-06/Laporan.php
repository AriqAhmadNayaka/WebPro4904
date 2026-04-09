<?php
class Laporan { // class untuk mengelola data laporan user
    private $conn; // properti untuk menyimpan koneksi database aktif

    public function __construct($db){ // constructor menerima koneksi dari luar
        $this->conn = $db; // simpan koneksi ke dalam properti class
    }

    // ambil semua data
    public function getAll($username){ // fungsi mengambil semua data berdasarkan user
        return mysqli_query($this->conn, // jalankan query ke database MySQL
            "SELECT * FROM laporan WHERE username='$username' ORDER BY id DESC" // ambil data urut terbaru
        );
    }

    // ambil 1 data
    public function getById($id){ // fungsi mengambil satu data berdasarkan id
        $result = mysqli_query($this->conn, // jalankan query select satu data
            "SELECT * FROM laporan WHERE id=$id" // ambil data sesuai id tertentu
        );
        return mysqli_fetch_assoc($result); // ubah hasil query jadi array
    }

    // hapus data
    public function delete($id, $username){ // fungsi untuk menghapus data laporan
        return mysqli_query($this->conn, // jalankan query delete database
            "DELETE FROM laporan WHERE id=$id AND username='$username'" // hapus data milik user tertentu
        );
    }

    // upload file
    public function uploadFile($file, $oldFile = ""){ // fungsi upload file ke folder server
        if($file['name'] != ""){ // cek apakah file baru diupload user
            $namaFile = $file['name']; // ambil nama file yang diupload
            move_uploaded_file($file['tmp_name'], "uploads/" . $namaFile); // pindahkan file ke folder uploads
            return $namaFile; // kembalikan nama file baru disimpan
        }
        return $oldFile; // jika tidak upload gunakan file lama
    }

    // insert data
    public function insert($data, $file, $username){ // fungsi untuk menambah data baru laporan
        $fileName = $this->uploadFile($file); // proses upload file terlebih dahulu

        return mysqli_query($this->conn, // jalankan query insert ke database
        "INSERT INTO laporan (tanggal,keterangan,jenis,jumlah,file,username) 
         VALUES (
            '{$data['tanggal']}', 
            '{$data['keterangan']}',
            '{$data['jenis']}', 
            '{$data['jumlah']}', 
            '$fileName', 
            '$username' 
         )");
    }

    // update data
    public function update($id, $data, $file, $username, $oldFile){ // fungsi update data laporan berdasarkan id
        $fileName = $this->uploadFile($file, $oldFile); // upload file baru atau gunakan lama

        return mysqli_query($this->conn, // jalankan query update ke database
        "UPDATE laporan SET 
            tanggal='{$data['tanggal']}', 
            keterangan='{$data['keterangan']}', 
            jenis='{$data['jenis']}', 
            jumlah='{$data['jumlah']}', 
            file='$fileName' 
         WHERE id=$id AND username='$username'"); // update hanya milik user tersebut
    }
}
?>