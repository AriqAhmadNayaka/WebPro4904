<?php
class Pasien {
    private $conn; //Variabel untuk menyimpan koneksi database (bersifat private)

    public function __construct($db){ //Constructor, otomatis dijalankan saat objek dibuat
        $this->conn = $db; //Menyimpan koneksi database ke dalam $conn
    }

    public function simpan($data, $file){ //Method untuk menyimpan data pasien + file
        $nama = $data['nama']; //Ambil input nama
        $tanggal = $data['tanggal']; //Ambil tanggal
        $jk = $data['jk']; //Ambil jenis kelamin
        $berat = $data['berat']; //Ambil berat
        $tinggi = $data['tinggi']; //Ambil tinggi
        $lingkar = $data['lingkar']; //Ambil lingkar kepala

        $namaFile = $file['name']; //Ambil nama file yang diupload
        $tmp = $file['tmp_name']; //Ambil lokasi sementara file
        move_uploaded_file($tmp, "upload/".$namaFile); //Memindahkan file ke folder upload

        $query = "INSERT INTO pasien  
        (nama, tanggal, jk, berat, tinggi, lingkar, file)
        VALUES ('$nama','$tanggal','$jk','$berat','$tinggi','$lingkar','$namaFile')"; //Query untuk menyimpan data ke database

        return $this->conn->query($query); //Menjalankan query dan mengembalikan hasilnya
    }

    public function tampil(){ //Method untuk mengambil semua data
        return $this->conn->query("SELECT * FROM pasien"); //Query ambil semua data dari tabel pasien
    }

    public function hapus($id){ //Method untuk menghapus data berdasarkan ID
        return $this->conn->query("DELETE FROM pasien WHERE id=$id"); //Query hapus data
    }

    public function getById($id){ //Method untuk ambil 1 data berdasarkan ID
    $result = $this->conn->query("SELECT * FROM pasien WHERE id=$id"); //Query ambil data
    return $result->fetch_assoc(); //Mengubah hasil jadi array
}
}

 function update($id, $data, $file){  //Function untuk update data
    $nama = $data['nama']; 
    $tanggal = $data['tanggal'];
    $jk = $data['jk'];
    $berat = $data['berat'];
    $tinggi = $data['tinggi'];
    $lingkar = $data['lingkar'];

    if($file['name'] != ""){ //Jika ada file baru yang diupload
        $namaFile = $file['name']; 
        $tmp = $file['tmp_name'];
        move_uploaded_file($tmp, "upload/".$namaFile); //Upload file baru

        $query = "UPDATE pasien SET
            nama='$nama',
            tanggal='$tanggal',
            jk='$jk',
            berat='$berat',
            tinggi='$tinggi',
            lingkar='$lingkar',
            file='$namaFile'
            WHERE id=$id"; //Update data + file baru
    } else {
        $query = "UPDATE pasien SET 
            nama='$nama',
            tanggal='$tanggal',
            jk='$jk',
            berat='$berat',
            tinggi='$tinggi',
            lingkar='$lingkar'
            WHERE id=$id"; //Update data tanpa mengubah file
    }

    return $this->conn->query($query);
}

function getById($id){
    $result = $this->conn->query("SELECT * FROM pasien WHERE id=$id");
    return $result->fetch_assoc();
}
?>