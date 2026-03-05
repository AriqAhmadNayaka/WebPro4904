<?php

function cariTipeTelur($N){
    $faktor = [];
    $n = $N;

    // faktorisasi prima
    for ($i = 2; $i * $i <= $n; $i++) {
        while ($n % $i == 0) {
            if (!isset($faktor[$i])) {
                $faktor[$i] = 0;
            }
            $faktor[$i]++;
            $n /= $i;
        }
    }

    if ($n > 1) {
        if (!isset($faktor[$n])) {
            $faktor[$n] = 0;
        }
        $faktor[$n]++;
    }

    // cari pangkat terkecil
    $minPangkat = min($faktor);

    // cari prima dengan pangkat terkecil
    $kandidat = [];
    foreach ($faktor as $prima => $pangkat) {
        if ($pangkat == $minPangkat) {
            $kandidat[] = $prima;
        }
    }

    // pilih yang paling kecil
    return min($kandidat);
}

if(isset($_POST['modal'])){
    $N = $_POST['modal'];
    $hasil = cariTipeTelur($N);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Logika Pak Dengklek</title>
</head>
<body>

<h2>Menentukan Tipe Telur Terbaik</h2>

<form method="post">
Masukkan Modal (N): 
<input type="number" name="modal" required>
<input type="submit" value="Hitung">
</form>

<?php
if(isset($hasil)){
    echo "<h3>Tipe Telur Terbaik (P): $hasil</h3>";
}
?>

</body>
</html>