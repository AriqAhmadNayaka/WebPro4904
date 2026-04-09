<?php

class Segitiga {
    protected $a;
    protected $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
        echo "Objek Segitiga dibuat<br>";
    }

    public function __destruct() {
        echo "Objek Segitiga dihapus<br>";
    }
}

class Hitung extends Segitiga {

    public function luas() {
        return 0.5 * $this->a * $this->b;
    }

    public function keliling() {
        return 3 * $this->a;
    }
}

$segitiga = new Hitung(6, 4);
echo "Luas: " . $segitiga->luas() . "<br>";
echo "Keliling: " . $segitiga->keliling() . "<br>";

?>