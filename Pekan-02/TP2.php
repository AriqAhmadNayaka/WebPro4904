<?php

function Konvert($desimal){ 
    $hex = dechex($desimal); //untuk membuat fungsi konversi desimal ke heksadesimal
    return $hex; //membalikan nilai hasil konversi
}

$desimal = 255; //inisiasi nilai desimal yang mmau dikonversi
$hexa = Konvert($desimal); //manggil fungsi konvert dengan parameter desimal

echo "hasil dari konversi desimal adalah: " . strtoupper($hexa); //menampilkan hasil konversi

?>
