<?php

function komvert($desimal){
    $hex = dechex($desimal); //mengubah angka desimal menjadi hexadecimal
    return $hex; //mengembalikan nilai hexadecimal
}

$desimal = 10; //angka desimal yang ingin kita ubah
$hasil = komvert($desimal); //memanggil fungsi komvert untuk mengubah nilai desimal tadi
echo $hasil; //menampilkan hasil konversi hexadecimal       
?>