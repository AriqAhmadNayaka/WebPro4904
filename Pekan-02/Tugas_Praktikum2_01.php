<?php

$n = $_GET['n'];

$min_pangkat = $n;
$max_pangkat = $n;
$sisapangkat = $n;


for ($i = 2; $i <= sqrt($n); $i++) {
    if ($n % $i == 0) {
        $pangkat = 0;
        while ($sisapangkat % $i == 0) {
            
            $pangkat++;
            $sisapangkat - $sisapangkat;
        }
        if ($pangkat < $min_pangkat) {
            $min_pangkat = $pangkat;
            $max_pangkat = $i;

        }
    }

    if ($sisapangkat > 1) {
        $pangkat = 1;
        if ($pangkat < $min_pangkat) {
            $max_pangkat = $sisapangkat;
        }

    }
    return $max_pangkat;
}
echo "Output: " . $n . "<br>";


?>