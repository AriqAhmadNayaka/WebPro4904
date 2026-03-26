<?php

if(isset($_POST['angka'])){
    $desimal = $_POST['angka'];
    $hex = dechex($desimal);

    echo "Desimal: ".$desimal."<br>";
    echo "Hexadecimal: ".strtoupper($hex)."<br><br>";
}

?>

<form method="POST">
Masukkan Bilangan Desimal:
<br><br>
<input type="number" name="angka">
<br>
<button type="submit">Konversi</button>
</form>