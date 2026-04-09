<?php
class BangunRuang
{
    public $a;
    public $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}

class PersegiPanjang extends BangunRuang
{
    public function hitungLuas()
    {
        return $this->a * $this->b;
    }

    public function hitungKeliling()
    {
        return 2 * ($this->a + $this->b);
    }
}

class Segitiga extends BangunRuang
{
    public $sisi1;
    public $sisi2;
    public $sisi3;

    public function __construct($a, $b, $sisi1, $sisi2, $sisi3)
    {
        parent::__construct($a, $a);
        $this->sisi1 = $sisi1;
        $this->sisi2 = $sisi2;
        $this->sisi3 = $sisi3;
    }

    public function hitungLuas()
    {
        return 0.5 * $this->a * $this->b;
    }

    public function hitungKeliling()
    {
        return $this->sisi1 + $this->sisi2 + $this->sisi3;
    }
}

$persegiPanjang = new PersegiPanjang(5, 5);
$segitiga = new Segitiga(4, 8, 6, 5 ,3);
echo "Davin Tugas Pekan 06<br>";
echo "Persegi Panjang<br>";
echo "Panjang: " . $persegiPanjang->a . "<br>";
echo "Lebar: " . $persegiPanjang->b . "<br>";
echo "Luas: " . $persegiPanjang->hitungLuas() . "<br>";
echo "Keliling: " . $persegiPanjang->hitungKeliling() . "<br><br>";

echo "Segitiga<br>";
echo "Alas: " . $segitiga->a . "<br>";
echo "Tinggi: " . $segitiga->b . "<br>";
echo "Luas: " . $segitiga->hitungLuas() . "<br>";
echo "Keliling: " . $segitiga->hitungKeliling();
?>