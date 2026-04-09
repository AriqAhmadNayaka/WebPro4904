<?php

class BangunRuang
{    public $a;
    public $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function luas()
    {
        return 0;
    }

    public function keliling()
    {
        return 0;
    }
}

class PersegiPanjang extends BangunRuang
{    public function luas()
    {
        return $this->a * $this->b;
    }

    public function keliling()
    {
        return 2 * ($this->a + $this->b);
    }
}


class Segitiga extends BangunRuang
{
    
    public function luas()
    {
        return 0.5 * $this->a * $this->b;
    }

    
    public function keliling()
    {
        return $this->a + $this->b + sqrt(($this->a * $this->a) + ($this->b * $this->b));
    }
}

$pp = new PersegiPanjang(10, 5);
echo "Persegi Panjang<br>";
echo "Luas = " . $pp->luas() . "<br>";
echo "Keliling = " . $pp->keliling() . "<br><br>";

$sg = new Segitiga(6, 8);
echo "Segitiga<br>";
echo "Luas = " . $sg->luas() . "<br>";
echo "Keliling = " . $sg->keliling();

?>