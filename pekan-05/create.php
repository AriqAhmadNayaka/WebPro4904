<?php
include 'koneksi.php'; //menghubungkan koneksi ke database

if (isset($_POST['submit'])) { //pengecekan apakah sudah di submit atau belum
    $isi_artikel = $_POST['isi_artikel']; //mengambil isi_artikel dari form yang dikirim dari file artikel_psikolog.php
    
    $uploadDir = 'uploads/'; //folder untuk menyimpan gambar
    $imageFileType = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION)); //mengambil ektensi dari file gambar
    $allowedTypes = ['jpg', 'jpeg', 'png']; //ekstensi yang diperbolehkan

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) { //mengecek apakah file sudah di upload atau belum
        $check = getimagesize($_FILES['foto']['tmp_name']); //mengecek apakah file yang diupload adalah gambar atau bukan dengan menggunakan fungsi getimagesize
        
        if ($check !== false && in_array($imageFileType, $allowedTypes)) { //mengecek apakah file yang diupload adalah gambar dan juga memiliki ekstensi yang diperbolehkan

            $files = glob($uploadDir . "*.*"); //mengambil file yang ada di folder untuk mencari nama file dengan nomor tertinggi
            $highestNumber = 0; //variabel untuk menyimpan nomor tertinggi dari nama file yang ada di folder uploads
            foreach ($files as $file) { //melakukan perulangan untuk mencari nama file dengan nomor tertinggi di folder uploads
                $filename = pathinfo($file, PATHINFO_FILENAME); //mengambil nama file tanpa ekstensi
                if (is_numeric($filename) && $filename > $highestNumber) { //mengecek apakah nama file adalah angka dan juga lebih besar dari nomor tertinggi
                    $highestNumber = (int) $filename; //jika nama file adalah angka dan juga lebih besar dari nomor tertinggi maka nomor tersebut akan disimpan ke dalam variabel nomor tertinggi
                }
            }

            $newFileName = ($highestNumber + 1) . "." . $imageFileType; //membuat nama file baru dan juga menambahkan ekstensi dari file gambar yang diupload
            $targetFile = $uploadDir . $newFileName; //membuat target file untuk menyimpan gambar yang diupload dengan nama file baru yang sudah dibuat dan juga folder uploads

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) { //memindahkan file gambar ke folder uploads

                $foto = $newFileName; //menyimpan nama file gambar yang diupload ke dalam variabel foto untuk disimpan ke database

                $sql = "INSERT INTO artikel (isi_artikel, foto) VALUES ('$isi_artikel', '$foto')";  //membuat query untuk menyimpan data artikel ke database
                
                if (mysqli_query($koneksi, $sql)) {  //menjalankan koneksi dan query
                    header("location:artikel_psikolog.php?status=sukses"); //jika berhasil maka akan langsung di arahkan ke file artikel_psiskolog.php
                } else {
                    echo "Gagal menyimpan ke database: " . mysqli_error($koneksi); //kalau gagal, akan menampilkan pesan gagal menyimpan ke database
                }
                
            } else {
                echo "Gagal memindahkan file ke folder uploads."; //kalau gagal memindahkan file upload ke folder uploads maka akan memunculkan pesan gagal
            }
        } else {
            echo "Format file tidak valid atau bukan gambar."; //kalau ekstensi dari file yang diupload salah, maka akan memunculkan pesaan tidak valid
        }
    } else {
        echo "Silakan pilih foto terlebih dahulu."; //kalau file belum di pilih, maka akan memunculkan pesan silahkan pilih foto terlebih dahulu
    }
}
?>