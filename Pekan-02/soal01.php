<?php 

$n = 18; //menentukan nilai awal N
$minPangkat = $n; //inisiasi nilai minPangkat dengan nilai n, karena pangkat terkecil tidak mungkin lebih besar dari n
$baseP = $n; //inisiasi nilai baseP dengan nilai n, karena basis terkecil tidak mungkin lebih besar dari n
$sisaN = $n; //sisa nilai n yang akan dibagi bagi dengan faktor-faktor prima

for ($i=2; $i*$i <= $sisaN; $i++) { //ngecek faktor dari 2 sampai akar dari sisaN
    if ($sisaN % $i == 0) { //kalau sisaN bisa dibagi i, berarti i faktor dari n
        $pangkat = 0;
        while ($sisaN % $i == 0) { //selama sisaN masih habis dibagi dengan i, maka pangkatnya akan terus bertambah
            $sisaN = $sisaN / $i;//bagi sisaN dengan i untuk mendapatkan faktor prima berikutnya
            $pangkat++;//untukk tambah pangkatnya
        }
        if ($pangkat < $minPangkat) {//jika pangkat yang didapat lebih kecil dari sebelumnya
            $minPangkat = $pangkat;//memperbarui nilai pangkat terkecil
            $baseP = $i;//faktor prima yang ditemukan
        }
    }
}

if ($sisaN > 1) {//jika masih ada sisa lebih dari 1, berarti itu faktor prima terakhir
    if (1 < $minPangkat) {//mengecek apakah pangkat 1 lebih kecil dari pangkat sebelumnya
        $minPangkat = 1;//mengubah pangkat terkecil menjadi 1
        $baseP = $sisaN;// faktor prima terakhir yang ditemukan
    }
}

echo $baseP;//output basis terkecil yang memiliki pangkat terkecil

?>