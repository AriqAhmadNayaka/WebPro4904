<?php 

$n = 18;
$minPangkat = $n; 
$baseP = $n; 
$sisaN = $n; 

for ($i=2; $i*$i <= $sisaN; $i++) { 
    if ($sisaN % $i == 0) {  
        $pangkat = 0;
        while ($sisaN % $i == 0) { 
            $sisaN = $sisaN / $i;
            $pangkat++;
        }
        if ($pangkat < $minPangkat) {
            $minPangkat = $pangkat;
            $baseP = $i;
        }
    }
}

if ($sisaN > 1) {
    if (1 < $minPangkat) {
        $minPangkat = 1;
        $baseP = $sisaN;
    }
}

echo $baseP;

?>