<?php 

$n = 18; //inisiasi nilai n
$minPangkat = $n; //inisiasi nilai minPangkat dengan nilai n, karena pangkat terkecil tidak mungkin lebih besar dari n
$baseP = $n; //inisiasi nilai baseP dengan nilai n, karena basis terkecil tidak mungkin lebih besar dari n
$sisaN = $n; //sisa nilai n yang akan dibagi dengan faktor-faktor prima

for ($i=2; $i*$i <= $sisaN; $i++) { //validasi duitnya masih cukup untuk beli telur pxp
    if ($sisaN % $i == 0) { //jika sisaN habis dibagi dengan i, maka i adalah faktor prima dari n
        $pangkat = 0;
        while ($sisaN % $i == 0) { 
            $sisaN = $sisaN / $i;
            $pangkat++;
        }
        if ($pangkat < $minPangkat) {
            $minPangkat = $pangkat;
            $baseP = $i;
        }
    }
}

if ($sisaN > 1) {
    if (1 < $minPangkat) {
        $minPangkat = 1;
        $baseP = $sisaN;
    }
}

echo $baseP;

?>