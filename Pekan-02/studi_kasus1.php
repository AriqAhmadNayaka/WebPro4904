<?php

$n = $_GET['n'];

$min_pangkat = $n;
$best_p = $n;
$sisa_n = $n;

for ($i =2; $i * $i <= $sisa_n; $i++) {
    if ($sisa_n % $i == 0) {
        $pangkat = 0;
        while ($sisa_n % $i == 0) {
            $sisa_n /= $i;
            $pangkat++;
        }

        if ($pangkat < $min_pangkat) {
            $min_pangkat = $pangkat;
            $best_p = $i;
        }
    }
}

if ($sisa_n > 1) {
    if ($pangkat < $min_pangkat) {
        $best_p = $sisa_n;
    }
}

echo $best_p;
?>

/*
Penjelasan Program:
- Mengambil input nilai N dari URL menggunakan $_GET['n']
- $min_pangkat menyimpan pangkat terkecil, $best_p menyimpan bilangan prima terbaik
- $sisa_n digunakan untuk menyimpan nilai N yang akan difaktorkan
- Perulangan for mencari faktor prima dari N
- Jika ditemukan faktor, while menghitung jumlah pangkat faktor tersebut
- Jika pangkat lebih kecil, maka $best_p diperbarui
- Jika masih ada sisa bilangan > 1, maka dianggap faktor prima terakhir
- Program menampilkan $best_p sebagai hasil akhir
*/