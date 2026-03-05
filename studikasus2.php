<?php

$desimal = 25; //nilai desimal untuk di konvert ke hexa
$hexa = ""; //"" hasil dari desimal

for ($n = $desimal; $n > 0; $n = intval($n / 16)) { //intval berfungsi untuk mengubah suatu nilai menjadi integer

    $sisa = $n % 16;

    if ($sisa < 10) { 
        $hexa = $sisa . $hexa; //hexa= sisa dikali hexa
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
echo "Bilangan Hexadecimal : " . $hexa; //menunjukan hasil hexa

?>