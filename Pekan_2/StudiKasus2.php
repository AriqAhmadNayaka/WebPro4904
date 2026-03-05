<?php

function Konvert($decimal){
    $hex = dechex($decimal);
    return $hex;
}

$decimal = 255;
$hexa = Konvert($decimal);

echo "hasil dari konversi desimal adalah: " . strtoupper($hexa);

?>