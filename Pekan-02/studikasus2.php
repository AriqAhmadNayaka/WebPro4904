<?php

function Konvert($desimal){
    $hex = dechex($desimal);
    return $hex;
}

$desimal = 225;
$hexa = Konvert($desimal);

echo "hasil dari konversi desimal adalah: " . strtoupper($hexa);

?>