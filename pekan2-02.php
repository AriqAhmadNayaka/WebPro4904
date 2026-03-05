<?php

function komvert($desimal){
    $hex = dechex($desimal); //mengubah angka desimal menjadi hexadecimal
    return $hex; //mengembalikan nilai hexadecimal
}

$desimal = 255; //nilai desimal yang akan dikonversi
$hasil = komvert($desimal); //memanggil fungsi komvert untuk mengkonversi nilai desimal
echo $hasil; //menampilkan hasil konversi hexadecimal       
?>