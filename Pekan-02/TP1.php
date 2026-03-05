<?php 

$n = 24; //inisiasi nilai n
$minPangkat = $n; //inisiasi nilai minPangkat dengan nilai n, karena pangkat terkecil tidak mungkin lebih besar dari n
$baseP = $n; //inisiasi nilai baseP dengan nilai n, karena basis terkecil tidak mungkin lebih besar dari n
$sisaN = $n; //sisa nilai n yang akan dibagi dengan faktor-faktor prima

for ($i=2; $i*$i <= $sisaN; $i++) { //validasi duitnya masih cukup untuk beli telur pxp
    if ($sisaN % $i == 0) { //jika sisaN habis dibagi dengan i, maka i adalah faktor prima dari n
        $pangkat = 0; //inisiasi nilai pangkat untuk menghitung berapa kali i dapat membagi sisaN
        while ($sisaN % $i == 0) { 
            $sisaN = $sisaN / $i; //bagi sisaN dengan i untuk mengurangi sisaN dan menghitung pangkatnya
            $pangkat++; //tambah pangkat setiap kali i dapat membagi sisaN
        }
        if ($pangkat < $minPangkat) {
            $minPangkat = $pangkat; //update nilai minPangkat jika pangkat yang ditemukan lebih kecil dari minPangkat sebelumnya
            $baseP = $i; //update nilai baseP dengan faktor prima yang memiliki pangkat terkecil
        }
    }
}

if ($sisaN > 1) { //jika sisaN masih lebih besar dari 1, maka sisaN itu sendiri adalah faktor prima yang tersisa
    if (1 < $minPangkat) { //jika pangkat 1 lebih kecil dari minPangkat, maka update minPangkat dan baseP dengan faktor prima sisaN
        $minPangkat = 1;
        $baseP = $sisaN;
    }
}

echo $baseP; //menampilkan hasil baseP yang merupakan faktor prima dengan pangkat terkecil

?>