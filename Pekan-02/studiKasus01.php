<?php

$N = isset($_GET['n']) ? $_GET['n'] : 0;

$maxFaktor = 0;
$jawaban = 0;

for ($p = 2; $p <= $N; $p++) {

    $prima = true;
    for ($i = 2; $i < $p; $i++) {
        if ($p % $i == 0) {
            $prima = false;
            break;
        }
    }

    if ($prima && $N % $p == 0) {

        $totalTelur = $N * $p;

        $jumlahFaktor = 0;
        for ($j = 1; $j <= $totalTelur; $j++) {
            if ($totalTelur % $j == 0) {
                $jumlahFaktor++;
            }
        }
        if ($jumlahFaktor > $maxFaktor) {
            $maxFaktor = $jumlahFaktor;
            $jawaban = $p;
        }
    }
}

echo $jawaban;

?>