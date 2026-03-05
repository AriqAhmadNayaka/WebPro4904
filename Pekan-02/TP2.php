<?php

function Konvert($desimal){
    $hex = dechex($desimal);
    return $hex;
}

$desimal = 255;
$hexa = Konvert($desimal);

echo "hasil dari konversi desimal adalah: " . strtoupper($hexa);

?>