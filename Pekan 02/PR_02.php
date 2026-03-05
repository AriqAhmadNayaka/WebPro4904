<?php

$desimal = 25;//inisiasi nilai desimal yang akan dikonversi ke hexadecimal

$hex = "";//inisiasi string kosong untuk menyimpan hasil konversi hexadecimal
$digitHex = "0123456789ABCDEF";//   string yang berisi karakter-karakter hexadecimal untuk memudahkan konversi dari sisa pembagian ke karakter hexadecimal

while($desimal > 0){

    $sisa = $desimal % 16;//hitung sisa pembagian desimal dengan 16 untuk mendapatkan digit hexadecimal yang sesuai

    $hex = $digitHex[$sisa] . $hex;//ambil karakter hexadecimal yang sesuai dengan sisa pembagian dan tambahkan ke hasil konversi hexadecimal

    $desimal = floor($desimal / 16);//bagi desimal dengan 16 dan bulatkan ke bawah untuk mendapatkan nilai desimal berikutnya yang akan dikonversi pada iterasi selanjutnya
}

echo "Bilangan Desimal: 25 <br>";//menampilkan nilai desimal yang akan dikonversi
echo "Hasil Hexadecimal: $hex";//menampilkan hasil konversi hexadecimal

?>