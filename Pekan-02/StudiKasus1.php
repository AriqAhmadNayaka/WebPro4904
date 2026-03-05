<?php
$n = $_GET['n']; // input dari browser ?n=angka

function prima($x){
    if($x < 2) return false;
    for($i=2; $i*$i <= $x; $i++){
        if($x % $i == 0) return false;
    }
    return true;
}

function jumlahFaktor($x){
    $count = 0;
    for($i=1; $i*$i <= $x; $i++){
        if($x % $i == 0){
            $count += ($i*$i == $x) ? 1 : 2;
        }
    }
    return $count;
}

$terbaik = 0;
$maxKemasan = 0;

for($p=2; $p<=$n; $p++){
    if(prima($p) && $n % $p == 0){
        $telur = $n * $p; 
        $kemasan = jumlahFaktor($telur);

        if($kemasan > $maxKemasan){
            $maxKemasan = $kemasan;
            $terbaik = $p;
        }
    }
}

echo $terbaik;
?>