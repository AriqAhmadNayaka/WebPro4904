<?php
class BangunRuang {
    public $a;
    public $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }

    public function __destruct() {
        echo "Objek telah dihapus<br>";
    }
} 

class Segitiga extends BangunRuang {
   public function luasSegitiga() {
        return 0.5 * $this->a * $this->b;
    }

    public function kelilingSegitiga() {
        return $this->a * 3;
    }
}

class PersegiPanjang extends BangunRuang {
   public function luasPersegiPanjang() {
        return $this->a * $this->b;
    }

    public function kelilingPersegiPanjang() {
        return 2 * $this->a + $this->b;
    }
} 

$segiTiga = new SegiTiga(4, 5);
echo "Luas segitiga adalah " . $segiTiga->luasSegitiga() . " dan keliling adalah " . $segiTiga->kelilingSegitiga();
echo "<br><br>";

$PersegiPanjang = new PersegiPanjang(11, 5);
echo "Luas persegi panjang adalah " . $PersegiPanjang->luasPersegiPanjang() . " dan keliling adalah " . $PersegiPanjang->kelilingPersegiPanjang();
echo "<br>";

?>