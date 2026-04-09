<?php

class PersegiPanjang {
    protected $a;
    protected $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
        echo "Objek BangunDatar dibuat<br>";
    }

    public function __destruct() {
        echo "Objek BangunDatar dihapus<br>";
    }
}

class Hitung extends PersegiPanjang {

    public function luas() {
        return $this->a * $this->b;
    }

    public function keliling() {
        return 2 * ($this->a + $this->b);
    }
}

$persegiPanjang = new Hitung(10, 5);
echo "Luas: " . $persegiPanjang->luas() . "<br>";
echo "Keliling: " . $persegiPanjang->keliling() . "<br>";

?>