<?php
session_start();

$nama = "";//variabel untuk menyimpan nama anak
$gender = "";//variabel untuk menyimpan jenis kelamin anak
$tanggalLahir = "";//variabel untuk menyimpan tanggal lahir anak
$kelas = "";//variabel untuk menyimpan kelas atau program yang diikuti anak
$alamat = "";//variabel untuk menyimpan alamat anak
$catatan = "";//variabel untuk menyimpan catatan khusus tentang ana

//jika data anak sudah di isikan sebelumnya, maka data tersebut akan ditampilkan di form input data anak
if(isset($_SESSION['dataAnak'])){
    $data = $_SESSION['dataAnak'];

    $nama = $data['nama'];
    $gender = $data['gender'];
    $tanggalLahir = $data['tanggalLahir'];
    $kelas = $data['kelas'];
    $alamat = $data['alamat'];
    $catatan = $data['catatan'];
}

//simpan data ketika sudah di inputkan di form
if($_SERVER["REQUEST_METHOD"] == "POST"){//cek apakah form disubmit

    $nama = $_POST['nama'];//nama anak yang diinputkan
    $gender = $_POST['gender'];//jenis kelamin anak yang diinputkan
    $tanggalLahir = $_POST['tanggalLahir'];//tanggal lahir anak yang diinputkan
    $kelas = $_POST['kelas'];//kelas atau program yang diikuti anak yang diinputkan
    $alamat = $_POST['alamat'];//alamat anak yang diinputkan
    $catatan = $_POST['catatan'];//catatan khusus tentang anak yang diinputkan

    $_SESSION['dataAnak'] = [//menyimpan data anak di session dan seterusnya baik dari gender,nama dan seterusnya
        "nama" => $nama,    
        "gender" => $gender,
        "tanggalLahir" => $tanggalLahir,
        "kelas" => $kelas,
        "alamat" => $alamat,
        "catatan" => $catatan
    ];
}
?>