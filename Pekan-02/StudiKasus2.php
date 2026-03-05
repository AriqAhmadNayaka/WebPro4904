<!-- Studi Kasus 2 membuat program mengubah bilangan desimal ke hexadecimal-->

<?php
function decimalToHexadecimal($decimal) { 
    $hexadecimal = dechex($decimal);
    return strtoupper($hexadecimal);
}

$decimalNumber = 12;
$hexadecimalNumber = decimalToHexadecimal($decimalNumber);  
echo "Decimal: " . $decimalNumber . "<br>";
echo "Hexadecimal: " . $hexadecimalNumber;
?>