<?php

$desimal = $_GET['angka'];

$hex = dechex($desimal);

echo "Bilangan Desimal: " . $desimal;
echo "<br>";
echo "Bilangan Hexadecimal: " . strtoupper($hex);

?>