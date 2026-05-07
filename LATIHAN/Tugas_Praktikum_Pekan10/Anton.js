const formatRupiah = (angka) => "Rp " + angka.toLocaleString('id-ID');

const daftarProduk = [
    { id: 1, nama: "Laptop", harga: 7000000, stok: 5 },
    { id: 2, nama: "Mouse", harga: 150000, stok: 0 },
    { id: 3, nama: "Keyboard", harga: 300000, stok: 12 },
    { id: 4, nama: "Monitor", harga: 1200000, stok: 3 }
];

const htmlSemuaProduk = daftarProduk.map(produk =>
    `<tr>
        <td>${produk.id}</td>
        <td>${produk.nama}</td>
        <td>${formatRupiah(produk.harga)}</td>
        <td>${produk.stok}</td>
    </tr>`
).join("");
document.getElementById("tabel-semua-produk").innerHTML = htmlSemuaProduk;

const produkTersedia = daftarProduk.filter(produk => produk.stok > 0);

const htmlProdukTersedia = produkTersedia.map(produk =>
    `<tr>
        <td>${produk.id}</td>
        <td>${produk.nama}</td>
        <td>${formatRupiah(produk.harga)}</td>
        <td>${produk.stok}</td>
    </tr>`
).join("");
document.getElementById("tabel-produk-tersedia").innerHTML = htmlProdukTersedia;

const namaProduk = produkTersedia.map(produk => produk.nama);
const htmlNamaProduk = namaProduk.map(nama => `<li>${nama}</li>`).join("");
document.getElementById("list-nama-produk").innerHTML = htmlNamaProduk;

const totalAset = daftarProduk.reduce((akumulator, produk) => {
    return akumulator + (produk.harga * produk.stok);
}, 0);

document.getElementById("total-aset").innerText = formatRupiah(totalAset);

const [laptop, mouse, keyboard, monitor] = daftarProduk;

const laptopBaru = {
    ...laptop,
    harga: 7500000
};

const htmlImmutability = `
    <p><b>Produk Lama:</b> ${laptop.nama} - ${formatRupiah(laptop.harga)}</p>
    <p><b>Produk Baru:</b> ${laptopBaru.nama} - ${formatRupiah(laptopBaru.harga)}</p>
`;
document.getElementById("demo-immutability").innerHTML = htmlImmutability;