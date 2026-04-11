<?php
class BangunRuang {
    public $a;
    public $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
        echo "objek dengan a=$this->a dan b=$this->b  telah dibuat";
    }

    public function __destruct() {
        echo "<br>";
        echo "objek a=$this->a dan b=$this->b telah dihapus<br>";
    }
}

class segitiga extends BangunRuang {
    public function luas() {
        return 0.5 * $this->a * $this->b;
    }
    public function keliling() {
        return $this->a *3;
    }
}

class persegiPanjang extends BangunRuang {
    public function luas() {
        return $this->a * $this->b;
    }
    public function keliling() {
        return 2 * ($this->a + $this->b);
    }
}

$segitiga = new segitiga(2, 15);
echo "<br>";
echo "Luas segitiga: " . $segitiga->luas() . "<br>";
echo "Keliling segitiga: " . $segitiga->keliling() . "<br>";

$persegiPanjang = new persegiPanjang(4, 2);
echo "<br>";
echo "Luas persegi panjang: " . $persegiPanjang->luas() . "<br>";
echo "Keliling persegi panjang: " . $persegiPanjang->keliling() . "<br>";
?>