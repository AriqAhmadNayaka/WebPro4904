import { fetchProducts } from "./api/productApi.js";
import { getState, setState } from "./core/Store.js";
import { ProductList } from "./components/ProductList.js";
import { Cart } from "./components/Cart.js";
import { Newsletter } from "./components/Newsletter.js";

// Fungsi ini jalan saat tombol Add diklik, jadi produk masuk ke cart.
function addToCart(product) {
const state = getState();

setState({
cart: [...state.cart, product],
});

render();
}

// Checkout dipakai buat kosongin cart setelah user beli barang.
function checkout() {
setState({ cart: [] });
alert("Thank you for your purchase!");
render();
}

// Render ini buat gambar ulang daftar produk dan cart sesuai data terbaru.
function render() {
const state = getState();
ProductList(state.products, addToCart);
Cart(state.cart, checkout);
}

// Init ini proses awal web: ambil data produk, simpan, lalu tampilkan ke halaman.
async function init() {
try {
const data = await fetchProducts();
setState({ products: data });
render();
Newsletter();
} catch (error) {
console.error("Failed to load products:", error);
}
}

init();
