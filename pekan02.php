<?php 

$n = 18; // nilai n
$minPangkat = $n; //inisiasi nilai minPangkat dengan nilai n, karena pangkat terkecil tidak mungkin lebih besar dari nilai n
$baseP = $n; //inisiasi nilai baseP dengan nilai n, karena basis terkecil tidak mungkin lebih besar dari n
$sisaN = $n; //sisa nilai n yang akan dibagi faktor-faktor prima

for ($i=2; $i*$i <= $sisaN; $i++) { //validasi uangnya masih cukup untuk beli telur pxp
    if ($sisaN % $i == 0) { //jika sisa N habis dibagi dengan i, maka i adalah faktor prima dari n
        $pangkat = 0;
        while ($sisaN % $i == 0) { //jika sisa N masih habis dibagi dengan i, jadi i adalah faktor prima dari n
            $sisaN = $sisaN / $i; //bagi sisa N dengan i untuk mendapatkan faktor prima selanjutnya
            $pangkat++; //tambah pangkat buat faktor prima i
        }
        if ($pangkat < $minPangkat) { //kalau pangkat faktor prima i lebih kecil dari minPangkat, maka  harus update minPangkat dan baseP
            $minPangkat = $pangkat; //update minPangkat dengan pangkat faktor prima i
            $baseP = $i;//update baseP dengan faktor prima i
        }
    }
}

if ($sisaN > 1) {//jika sisa N masih lebih besar dari 1, berarti sisa N adalah faktor prima yang belum diperiksa
    if (1 < $minPangkat) {//jika pangkat faktor prima sisa N lebih kecil dari minPangkat, maka  harus update minPangkat dan baseP
        $minPangkat = 1;//update minPangkat dengan 1, karena faktor prima sisa N hanya muncul sekali aja
        $baseP = $sisaN;
    }
}

echo $baseP;

?>