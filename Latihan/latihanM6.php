<?php
class BangunRuang {
    public $a;
    public $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
        echo "Obyek dengan a=$a dan b=$b telah dibuat<br>";
    }

public function __destruct() {
    echo "Obyek $this->a dan $this->b dihapus<br>";
}
}

class SegiTiga extends BangunRuang {
    public function keliling() {
        return $this->a * 3;
    }

    public function luas() {
        return 0.5 * $this->a * $this->b;
    }
}

class PersegiPanjang extends BangunRuang {
    public function keliling() {
        return 2 * ($this->a + $this->b);
    }

    public function luas() {
        return $this->a * $this->b;
    }
}

$segiTiga = new SegiTiga(4, 5);
echo "Luas segitiga adalah " . $segiTiga->luas() . " dan keliling adalah " . $segiTiga->keliling();
echo "<br><br>";

$pp = new PersegiPanjang(11, 5);
echo "Luas persegi panjang adalah " . $pp->luas() . " dan keliling adalah " . $pp->keliling();
echo "<br>";

?>