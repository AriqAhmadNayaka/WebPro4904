<?php
    $n = $_GET["n"] ?? 18; //_GET berfungsi untuk memanggil n pada bagian url, n merupakan variabel.

    //dibawah ini merupakan variabel
    $min_pangkat = $n;
    $best_prima = $n; 
    $sisa_n = $n;

    for ($i = 2; $i * $i <= $sisa_n; $i++) { //i merupakan 2, dan i lebih kecil dari sisa_n, i++ untuk bertambah 1 
        if($sisa_n % $i == 0) { //sisa n modulus i
            $pangkat = 0;

            while ($sisa_n % $i == 0){ 
                $sisa_n = $sisa_n / $i; //lalu sisa n dibagi i 
                $pangkat++; //pangkat++ untuk bertambah 1
            }
            if ($pangkat < $min_pangkat) { //pangkat lebih kecil dari min pangkat
                $min_pangkat = $pangkat; //jika iya maka min pangkat = pangkat
                $best_prima = $i; //lalu best prisma = 1 
            }
        }
    }

    if ($sisa_n > 1) {
        $pangkat = 1;
        if ($pangkat < $min_pangkat) {
            $best_prima = $sisa_n;
        }
    }

    echo $best_prima; //untuk menampilkan hasil dari semua ini
?>