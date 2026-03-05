<?php

$desimal = 25; //nilai ditentukan 25
$hexa = ""; //"" karena emang belum ada hasilnya

for ($n = $desimal; $n > 0; $n = intval($n / 16)) { //intval berguna untuk mengubah menjadi integer

    $sisa = $n % 16; 

    if ($sisa < 10) {
        $hexa = $sisa . $hexa; 
    } else {
        switch ($sisa) {
            case 10: $hexa = "A" . $hexa; break;
            case 11: $hexa = "B" . $hexa; break;
            case 12: $hexa = "C" . $hexa; break;
            case 13: $hexa = "D" . $hexa; break;
            case 14: $hexa = "E" . $hexa; break;
            case 15: $hexa = "F" . $hexa; break;
        }
    }
}

echo "Bilangan Desimal : " . $desimal . "<br>";
echo "Bilangan Hexadecimal : " . $hexa;

?>