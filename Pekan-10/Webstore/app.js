import { fetchProducts } from "./api/productApi.js";
import { getState, setState } from "./core/Store.js";
import { ProductList } from "./components/ProductList.js";
import { Cart } from "./components/Cart.js";

import { Newsletter } from "./components/Newsletter.js";

// Menambahkan produk ke keranjang dengan mengambil state lama,
// lalu membuat array cart baru agar state tidak diubah langsung.
function addToCart(product) {
    const state = getState();

    setState({
        cart: [...state.cart, product],
    });

    render();
}

// Mengosongkan keranjang setelah proses checkout selesai.
function checkout() {
    setState({ cart: [] });
    alert("Thank you for your purchase!");
    render();
}

// Merender ulang daftar produk dan keranjang berdasarkan state terbaru.
function render() {
    const state = getState();

    ProductList(state.products, addToCart);
    Cart(state.cart, checkout);
}

// Inisialisasi aplikasi: ambil data produk, simpan ke state, lalu tampilkan UI.
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
