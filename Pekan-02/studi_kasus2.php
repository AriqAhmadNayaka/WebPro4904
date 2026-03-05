<?php 
    function decimalToHexa($desimal) {
        $hexadesimal = dechex($desimal);
        return strtoupper ($hexadesimal);
    }

    $desimal = 18;
    $hexadesimal = decimalToHexa($desimal);
    echo "Aplikasi Konverter Bilangan Desimal ke Hexadesimal <br><br>";
    echo "Bilangan desimal: " . $desimal . "<br>";
    echo "Bilangan hexadesimal: " . $hexadesimal;
?>

/*
Penjelasan Program:
- Program membuat fungsi bernama decimalToHexa() untuk mengubah bilangan desimal menjadi hexadecimal
- Di dalam fungsi, digunakan fungsi bawaan PHP dechex() untuk melakukan konversi desimal ke hexadecimal
- Fungsi strtoupper() digunakan untuk mengubah huruf pada hasil hexadecimal menjadi huruf kapital
- Variabel $desimal digunakan untuk menyimpan nilai bilangan desimal yang akan dikonversi
- Fungsi decimalToHexa() dipanggil untuk mengonversi nilai desimal dan hasilnya disimpan pada variabel $hexadesimal
- Program menampilkan judul aplikasi konverter menggunakan perintah echo
- Terakhir, program menampilkan nilai bilangan desimal dan hasil konversinya ke hexadecimal
*/