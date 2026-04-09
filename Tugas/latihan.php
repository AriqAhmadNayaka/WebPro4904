<?php
class BangunRuang {
    public $a;
    public $b;

    public function __construct ($a, $b) {
        $this->a = $a;       
        $this->b = $b;
    }

    public function luasPersegiPanjang() {
        return $this->a * $this->b;
    }
    public function kelilingPersegiPanjang() {
        return 2 * ($this->a + $this->b);
    }

    public function luasSegitiga() {
        return 0.5 * ($this->a * $this->b);
    }
    public function kelilingSegitiga() {
        return $this->a * 3;
    }

    public function __destruct() {
        echo "Bangun ruang dihapus" . "<br>" . "<br>";
    }
}

class PersegiPanjang extends BangunRuang {
    public function luasPersegiPanjang() {
        return parent::luasPersegiPanjang();
    }
    public function kelilingPersegiPanjang() {
        return parent::kelilingPersegiPanjang();
    }
}

class Segitiga extends BangunRuang {
    public function luasSegitiga() {
        return parent::luasSegitiga();
    }
    public function kelilingSegitiga() {
        return parent::kelilingSegitiga();
    }
}

$BangunRuang1 = new PersegiPanjang(4, 12);
echo "Luas persegi panjang: " . $BangunRuang1->luasPersegiPanjang() . "cm" . "<br>";
echo "Keliling persegi panjang: " . $BangunRuang1->kelilingPersegiPanjang() . "cm" . "<br><br>";
$BangunRuang2 = new Segitiga(4, 16);
echo "Luas segitiga: " . $BangunRuang2->luasSegitiga() . "cm" . "<br>";
echo "Keliling segitiga: " . $BangunRuang2->kelilingSegitiga() . "cm" . "<br><br>";
?>