<?php
    $n = $_GET["n"] ?? 18; //fungsi dari gate untuk memanggil n dibagian URL. N merupakan variabelnya, lalu 18 nilainya 

    $min_pangkat = $n;
    $best_prima = $n; //min_pangkat, best_prima, dan sisa_n adalah variabel
    $sisa_n = $n;

    for ($i = 2; $i * $i <= $sisa_n; $i++) { //disini i ditentukan sebagi 2, lalu i dikalikan dengan i lalu i kurang sama dengan sisa_n, lalu i++ agar bertambah satu
        if ($sisa_n % $i == 0) { //lanjut sisa_n modulus i lalu membanding dengan 0
            $pangkat = 0; 
            
            while ($sisa_n % $i == 0) { 
                $sisa_n = $sisa_n / $i; //sisa_n dibagi dengan i atau 2
                $pangkat++; //pangkat++ untuk bertambah satu
            }
            if ($pangkat < $min_pangkat) {
                $min_pangkat = $pangkat;
                $best_prima = $i;
            }
        }
    }

    if ($sisa_n > 1) {
        $pangkat = 1;
        if ($pangkat < $min_pangkat) {
            $best_prima = $sisa_n;
        }
    }

    echo $best_prima; //ini untuk menampilkan hasilnya
?>