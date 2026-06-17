<?php
if(isset($_POST['desimal'])){
    $desimal = $_POST['desimal'];
    $hexa = dechex($desimal);
}
?>

<h2>Konverter Desimal ke Hexadecimal</h2>

<form method="post">
    Masukkan Bilangan Desimal:
    <input type="number" name="desimal">
    <input type="submit" value="Konversi">
</form>

<?php
if(isset($hexa)){
    echo "Hasil Hexadecimal: " . $hexa;
}
?>