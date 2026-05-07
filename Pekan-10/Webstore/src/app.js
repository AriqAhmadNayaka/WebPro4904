import { fetchProducts } from "./api/productApi.js";
import { getState, setState } from "./core/Store.js";
import { ProductList } from "./components/ProductList.js";
import { Cart } from "./components/Cart.js";
import { Newsletter } from "./components/Newsletter.js";

// Menambahkan produk yang dipilih ke dalam state cart.
function addToCart(product) {
    const state = getState();

    setState({
        cart: [...state.cart, product],
    });

    render();
}

// Mengosongkan cart setelah proses pembelian selesai.
function checkout() {
    setState({ cart: [] });
    alert("Thank you for your purchase!");
    render();
}

// Mengambil state terbaru lalu menampilkan ulang ProductList dan Cart.
function render() {
    const state = getState();

    ProductList(state.products, addToCart);
    Cart(state.cart, checkout);
}

// Fungsi awal untuk mengambil data produk dan menjalankan komponen utama.
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
