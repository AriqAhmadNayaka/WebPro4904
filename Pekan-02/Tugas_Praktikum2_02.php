<?php

function decimaltohexadecimal($decimal) {
    $hexadecimal = dechex($decimal);
    return $hexadecimal;
}

$decimal = 49;
$hexadecimal = decimaltohexadecimal($decimal);
echo "Decimal: " . $decimal . "<br>";
echo "Hexadecimal: " . $hexadecimal;

?>