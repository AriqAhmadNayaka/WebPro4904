<?php

function desimaltoHexadecimal($desimal) {
    $hexadecimal = dechex($desimal);
    return strtoupper($hexadecimal);
}

$desimal = 14;
$hexadecimal = desimaltoHexadecimal($desimal);
echo "==== APLIKASI KONVERTER BILANGAN DESIMAL KE HEKSA DESIMAL ==== <br><br>";
echo "Bilangan desimal: " . $desimal . "<br>";
echo "Bilangan hexadecimal: " . $hexadecimal . "<br>";

?>

<!-- PENJELASAN:
1. program ini berfungsi untuk mengkonversi bilangan desimal ke bilangan hexadecimal
2. fungsi desimaltoHexadecimal menerima parameter $desimal yang merupakan bilangan desimal yang akan dikonversi
3. di dalam fungsi, program menggunakan fungsi built-in yaitu dechex() untuk mengkonversi bilangan desimal ke hexadecimal
4. hasil konversi kemudian dikembalikan dalam bentuk huruf kapital menggunakan strtoupper()
5. program kemudian memanggil fungsi desimaltoHexadecimal dengan nilai desimal 14 dan menyimpan hasilnya dalam variabel $hexadecimal
6. terakhir, program mencetak hasil konversi ke layar dengan format yang jelas
7. contoh output yang dihasilkan adalah:
==== APLIKASI KONVERTER BILANGAN DESIMAL KE HEKSA DESIMAL ====
Bilangan desimal: 14
Bilangan hexadecimal: E
-->