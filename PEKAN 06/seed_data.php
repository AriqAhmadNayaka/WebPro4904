<?php
/**
 * One-time SEED SCRIPT: Tambah 8 Demo Produk Warisan Budaya
 * Run: php seed_data.php atau http://localhost/LATIHAN/seed_data.php
 * Safe: Skip jika sudah ada data.
 */

require_once 'config/database.php';
require_once 'classes/Produk.php';

$produkObj = new Produk();

$demoProduk = [
    [
        'nama' => 'Keris Pusaka Kuno',
        'harga' => 15000000,
        'deskripsi' => 'Keris pusaka Jawa abad ke-18 dari besi pamor, simbol keberanian raja-raja Mataram.',
        'gambar' => '69d6a93e2f0ef.jpg'
    ],
    [
        'nama' => 'Batik Parang Jati',
        'harga' => 8500000,
        'deskripsi' => 'Batik motif parang jati asli Yogyakarta, filosofi keberanian dan keteguhan hati.',
        'gambar' => '69d6a9683cda3.jpg'
    ],
    [
        'nama' => 'Wayang Kulit Semar',
        'harga' => 12000000,
        'deskripsi' => 'Wayang kulit karakter Semar, dalang kebijaksanaan dalam cerita Mahabharata.',
        'gambar' => '69d6ab227bb5c.jpg'
    ],
    [
        'nama' => 'Gamelan Gong Ageng',
        'harga' => 25000000,
        'deskripsi' => 'Set gamelan gong ageng lengkap dari Yogyakarta, alat musik keraton.',
        'gambar' => '69d6abe373d23.jpg'
    ],
    [
        'nama' => 'Patung Buddha Amitabha',
        'harga' => 18000000,
        'deskripsi' => 'Patung Buddha Amitabha emas dari Borobudur era Sailendra.',
        'gambar' => '69d6ac9d522d9.jpg'
    ],
    [
        'nama' => 'Songket Palembang',
        'harga' => 9000000,
        'deskripsi' => 'Kain songket emas asli Palembang, motif pucuk rebung.',
        'gambar' => '69d6aca6b63bf.jpg'
    ],
    [
        'nama' => 'Topeng Dayak',
        'harga' => 7500000,
        'deskripsi' => 'Topeng ritual suku Dayak Kalimantan, simbol roh leluhur.',
        'gambar' => '69d6ad2bcee58.jpg'
    ],
    [
        'nama' => 'Gamelan Suling Bambu',
        'harga' => 22000000,
        'deskripsi' => 'Suling bambu angklung dari Bali, harmoni alam pegunungan.',
        'gambar' => '69d6adc8d7190.jpg'
    ]
];

$count = 0;
foreach ($demoProduk as $produk) {
    // Skip jika nama sudah ada
    if ($produkObj->getByNama($produk['nama'])) {
        echo "⏭️ Skip: {$produk['nama']} sudah ada\n";
        continue;
    }
    
    try {
        // Buat data dummy tanpa upload file (reuse existing images)
        $produkObj->save($produk, null);
        echo "✅ {$produk['nama']} (Rp " . number_format($produk['harga']) . ")\n";
        $count++;
    } catch (Exception $e) {
        echo "❌ Error {$produk['nama']}: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 SEEDING SELESAI: $count/8 produk ditambahkan!\n";
echo "Buka: http://localhost/.../dashboard.php untuk lihat hasil.\n";
echo "Hapus file ini setelah run.\n";

?>

