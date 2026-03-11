<?php
// Mengambil angka desimal dari URL (contoh: ?angka=255)
// Defaultnya kita isi 255 jika tidak ada input 
$desimal_input = $_GET['angka'] ?? 40;

// Membuat fungsi konverter
function desimalKeHeksa(int $desimal)
{
    // Jika angkanya 0, langsung kembalikan "0"
    if ($desimal == 0) {
        return "0";
    }

    // 1. Array Asosiatif untuk pemetaan huruf
    // Karena heksadesimal menggunakan huruf untuk angka 10-15
    $huruf_heksa = [
        10 => "A",
        11 => "B",
        12 => "C",
        13 => "D",
        14 => "E",
        15 => "F"
    ];

    $hasil_heksa = ""; // Variabel string kosong untuk menampung hasil

    // 2. Perulangan While: Berjalan selama angka desimal masih lebih dari 0
    while ($desimal > 0) {

        // 3. Mencari sisa bagi (modulus) dengan 16
        $sisa = $desimal % 16;

        // 4. Percabangan: Cek apakah sisa baginya butuh diubah ke huruf (10-15)
        if ($sisa >= 10) {
            // Ambil huruf dari array berdasarkan kuncinya
            $karakter = $huruf_heksa[$sisa];
        } else {
            // Jika di bawah 10, tetap gunakan angka aslinya
            $karakter = $sisa;
        }

        // 5. Menyusun hasil (Ditambahkan di DEPAN string, bukan di belakang)
        // Karena kita membaca hasil dari bawah ke atas
        $hasil_heksa = $karakter . $hasil_heksa;

        // 6. Memperbarui angka desimal untuk putaran selanjutnya
        // Kita kurangi sisanya dulu agar pembagiannya bulat tanpa koma
        $desimal = ($desimal - $sisa) / 16;
    }

    return $hasil_heksa;
}

// Menampilkan Hasil
echo "Angka Desimal: " . $desimal_input . "<br>";
echo "Hasil Heksadesimal: " . desimalKeHeksa($desimal_input);
