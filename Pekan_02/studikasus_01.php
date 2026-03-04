<?php
// Mengambil nilai N dari URL (?n=x) menggunakan $_GET dan operator ??
// Jika di URL tidak ada input ?n=x, maka defaultnya adalah 18
$n = $_GET['n'] ?? 18;

// Membuat fungsi yang mengembalikan nilai integer
function cariTipeTelur(int $n)
{
    $min_pangkat = $n; // Nilai awal pembanding dibuat sebesar N
    $best_p = $n;      // Nilai awal kandidat P dibuat sebesar N
    $sisa_n = $n;

    // Di sini kita menerima uang modal dari Pak Dengklek melalui link browser ?n=x. 
    // Jika tidak ada input, kita anggap modal awalnya adalah 18. Lalu, kita siapkan tiga variabel. 
    // $min_pangkat adalah 'raja bertahan' untuk mencatat pangkat terkecil yang ditemukan. 
    // Kita set nilai awalnya sangat besar (sebesar $N$) agar nanti pasti tergantikan oleh 
    // nilai pangkat yang lebih kecil.

    // 1. Menggunakan perulangan for untuk mengecek angka dari 2 hingga batas akar kuadratnya
    for ($i = 2; $i * $i <= $sisa_n; $i++) {
        // 2. Menggunakan if dan operator modulus (%) untuk mengecek apakah habis dibagi
        if ($sisa_n % $i == 0) {
            // Kita mulai menjalankan looping dari angka 2, karena 2 adalah bilangan prima terkecil. 
            // Di dalam looping, kita menggunakan pengecekan if ($sisa_n % $i == 0). 
            // Ini adalah implementasi Langkah 1 dari teori kita: mengecek apakah angka tersebut 
            // bisa membagi habis modal uang N. Jika sisanya 0 (habis dibagi), berarti angka $i ini 
            // adalah sah sebagai Kandidat Tipe Telur (P).

            $pangkat = 0;
            // 3. Menggunakan perulangan while untuk membagi terus-menerus dan menghitung pangkat
            while ($sisa_n % $i == 0) {
                $pangkat++; // Operator increment untuk menambah nilai pangkat
                $sisa_n = $sisa_n / $i; // Operator pembagian
            }
            // Ini adalah mesin utama pemecah angka (Faktorisasi) atau Langkah 4 di teori kita. 
            // Selama angka modal ($sisa_n) masih bisa dibagi habis oleh kandidat tipe telur ($i), 
            // kita akan terus membaginya di dalam looping while. Setiap kali berhasil membagi, 
            // nilai $pangkat akan bertambah 1 ($pangkat++). Proses ini akan berhenti sendiri ketika 
            // angkanya sudah tidak bisa dibagi lagi oleh $i. Hasil akhirnya, kita tahu persis berapa 
            // jumlah pangkat (kemunculan) dari angka prima tersebut.

            // 4. Menggunakan if untuk menyimpan pangkat terkecil dan nilai P nya
            if ($pangkat < $min_pangkat) {
                $min_pangkat = $pangkat;
                $best_p = $i;
            }
            // Masuk ke Langkah 5. Setelah pangkatnya dihitung, kandidat ini 
            // menantang 'raja bertahan' ($min_pangkat). Ingat aturan rahasianya: cari pangkat yang paling kecil. 
            // Jika $pangkat yang baru dihitung ternyata lebih kecil dari $min_pangkat, maka ia menang! 
            // Kita catat pangkatnya sebagai $min_pangkat yang baru, dan kita catat kandidat angkanya ($i) 
            // sebagai $best_p (Tipe Telur Terbaik sementara). Lalu bagaimana jika pangkatnya sama (seri)? 
            // Karena looping kita berjalan berurutan dari angka kecil ke besar, jika ada kandidat baru 
            // yang pangkatnya sama persis dengan raja bertahan, syarat < (kurang dari) membuat kandidat 
            // baru itu tidak akan bisa menggantikan raja. Alhasil, pemenangnya otomatis adalah angka prima 
            // yang paling kecil sesuai aturan Pak Dengklek
        }
    }

    // 5. Cek sisa N. Jika sisa_n lebih besar dari 1, berarti ada sisa angka prima terakhir
    if ($sisa_n > 1) {
        $pangkat = 1;
        if ($pangkat < $min_pangkat) {
            $best_p = $sisa_n;
        }
    }
    // Terkadang, setelah modal uang N dibagi-bagi terus di dalam looping while tadi, ternyata masih ada sisa 
    // angka yang lebih besar dari 1. Jika ini terjadi, sisa angka tersebut pasti adalah sebuah bilangan prima 
    // raksasa yang pangkatnya tepat 1. Kita wajib mengeceknya: karena pangkatnya 1, apakah ia bisa mengalahkan 
    // $min_pangkat? Jika iya, maka sisa angka prima raksasa inilah yang keluar sebagai pemenang utamanya.

    // Mengembalikan nilai P terbaik
    return $best_p;
}

// Menampilkan output dengan memanggil fungsi

echo cariTipeTelur($n);
