<?php 

$n = 18; //inisiasi nilai n
$minPangkat = $n; //inisiasi nilai minPangkat dengan nilai n, karena pangkat terkecil tidak mungkin lebih besar dari n
$baseP = $n; //inisiasi nilai baseP dengan nilai n, karena basis terkecil tidak mungkin lebih besar dari n
$sisaN = $n; //sisa nilai n yang akan dibagi dengan faktor-faktor prima

for ($i=2; $i*$i <= $sisaN; $i++) { //variabel i dimulai dari 2, dan akan terus meningkat hingga i^2 lebih besar dari sisaN, karena faktor prima terbesar yang mungkin adalah sqrt(n)
    if ($sisaN % $i == 0) { //jika sisaN habis dibagi dengan i, maka i adalah faktor prima dari n
        $pangkat = 0;
        while ($sisaN % $i == 0) { //hitung pangkat dari faktor prima i
            $sisaN = $sisaN / $i;
            $pangkat++;
        }
        if ($pangkat < $minPangkat) { //jika pangkat dari faktor prima i lebih kecil dari minPangkat, maka update minPangkat dan baseP
            $minPangkat = $pangkat;
            $baseP = $i;
        }
    }
}

if ($sisaN > 1) { //jika sisaN masih lebih besar dari 1, maka sisaN adalah faktor prima terakhir yang harus diperiksa
    if (1 < $minPangkat) {
        $minPangkat = 1;
        $baseP = $sisaN;
    }
}

echo $baseP;

?>