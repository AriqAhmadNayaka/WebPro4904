<?php

function Konvert($desimal){
    $hex = dechex($desimal); //untuk membuat fungsi konversi ke heksadesimal
    return $hex; //membalikan nilai hasil konversi
}

$desimal = 225; //inisiasi nilai desimal yang mau dikonversi
$hexa = Konvert($desimal); //memanggil funsi konvert dengan parameter desimal

echo "hasil dari konversi desimal adalah: " . strtoupper($hexa); //menampilkan hasil konversi

?>