import { fetchProducts } from "./api/productApi.js";
import { getState, setState } from "./core/Store.js";
import { ProductList } from "./Component/productList.js";
import { Cart } from "./Component/Cart.js";
import { Newsletter } from "./Component/Newsletter.js";

// Fungsi ini jalan waktu tombol Add di card produk ditekan.
function addToCart(product) {
    // Ambil data terbaru dulu, biar isi cart yang lama tetap kebawa.
    const state = getState();

    // Produk baru ditambahkan ke cart dengan cara membuat array baru.
    setState({
        cart: [...state.cart, product],
    });

    // Setelah cart berubah, tampilan perlu digambar ulang.
    render();
}

// Fungsi untuk tombol Buy Now.
function checkout() {
    setState({ cart: [] });
    alert("Thank you for your purchase!");
    render();
}

// Semua bagian yang tampil di halaman diatur dari sini.
function render() {
    // Ambil state terbaru setiap kali render dipanggil.
    const state = getState();

    // Kirim data produk ke komponen daftar produk.
    ProductList(state.products, addToCart);

    // Kirim isi cart ke komponen keranjang.
    Cart(state.cart, checkout);
}

// Bagian awal yang dijalankan saat web pertama kali dibuka.
async function init() {
    try {
        // Ambil data produk dari file API buatan.
        const data = await fetchProducts();

        // Simpan produk ke state, lalu tampilkan ke halaman.
        setState({ products: data });
        render();

        // Newsletter cukup dibuat sekali di awal.
        Newsletter();
    } catch (error) {
        console.error("Failed to load products:", error);
    }
}

init();
