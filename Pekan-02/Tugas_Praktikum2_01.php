<?php

// Mengambil input 'n' dari URL (metode GET)
$n = $_GET['n'];

// Inisialisasi variabel awal
$min_pangkat = $n; // Menyimpan nilai pangkat terkecil yang ditemukan
$max_pangkat = $n; // Menyimpan faktor prima yang memiliki pangkat terkecil tersebut
$sisapangkat = $n; // Sisa angka yang akan terus dibagi oleh faktor primanya

// Loop untuk mencari faktor pembagi mulai dari 2 hingga akar dari n
for ($i = 2; $i <= sqrt($n); $i++) {
    // Memeriksa apakah $i adalah faktor dari $n
    if ($n % $i == 0) {
        $pangkat = 0;

        // Menghitung berapa kali $n bisa habis dibagi oleh $i (mencari pangkat)
        while ($sisapangkat % $i == 0) {
            $pangkat++;
            $sisapangkat = $sisapangkat / $i;
        }
        // Membandingkan pangkat. Jika lebih kecil, update min_pangkat dan simpan faktornya
        if ($pangkat < $min_pangkat) {
            $min_pangkat = $pangkat;
            $max_pangkat = $i;
        }
    }
}
    // Jika masih ada sisa angka > 1, berarti sisa tersebut adalah faktor prima terakhir
    if ($sisapangkat > 1) {
        $pangkat = 1;
        if ($pangkat < $min_pangkat) {
            $min_pangkat = $pangkat;
            $max_pangkat = $sisapangkat;
        }
    }

// Menampilkan hasil input dan faktor prima terpilih
echo "Input: " . $n . "<br>";
echo "Output: " . $max_pangkat;

?>