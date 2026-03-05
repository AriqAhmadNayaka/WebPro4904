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

// PENJELASAN:
// 1. program menerima input bilangan n berupa angka atau modal melalui parameter GET yang dikirimkan melalui URL
// parameter GET berfungsi untuk mengambil nilai yang diinput dan disimpan dalam variabel $n

// 2. kemudian, inisialisasi variabel yang akan digunakan yaitu:
// - untuk menyimpan faktor prima dengan pangkat terkecil yang ditemukan, yaitu $min_pangkat dan $best_p
// - variabel $sisa_n digunakan untuk menyimpan nilai n yang akan dipecah menjadi faktor prima

// 3. lalu, proses for loop digunakan untuk mencari faktor prima dari n
// dimulai dari angka 2 ($i = 2) hingga akar kuadrat dari sisa_n ($i * $i <= $sisa_n)

// 4. di dalam loop, program memeriksa di if statement apakah sisa_n dapat dibagi oleh i tanpa sisa (sisa_n % $i == 0)
// jika iya, maka program akan menghitung pangkat dari faktor prima tersebut dengan menggunakan while loop
// untuk terus membagi sisa_n dengan i hingga tidak bisa dibagi lagi

// 5. kemudian, program membandingkan pangkat yang ditemukan dengan pangkat terkecil yang sudah disimpan di $min_pangkat
// jika pangkat yang ditemukan lebih kecil, maka program akan memperbarui nilai $min_pangkat dan menyimpan faktor prima di $best_p

// 6. Terakhir, output yang dikembalikan adalah faktor prima dengan pangkat terkecil yang ditemukan.

// Contoh: Jika n = 60, faktor prima dari 60 adalah 2^2, 3^1, dan 5^1.
// Faktor prima dengan pangkat terkecil adalah 3 dan 5, sehingga output yang dikembalikan adalah 3. 