// Import modul utama aplikasi: API produk, store, dan komponen UI
import { fetchProducts } from "./Api/ProductApi.js";
import { getState, setState } from "./Api/core/Store.js";
import { ProductList } from "./Api/components/Productlist.js";
import { Cart } from "./Api/components/cart.js";
import { Newsletter } from "./Api/components/Newsletter.js";

// Menambahkan produk ke cart, memperbarui state, lalu render ulang tampilan.
function addToCart(product) {
    const state = getState();

    setState({
        cart: [...state.cart, product],
    });

    render();
}

// Mengosongkan cart setelah checkout dan menampilkan pesan sukses.
function checkout() {
    setState({ cart: [] });
    alert("Thank you for your purchase!");
    render();
}

// Merender daftar produk dan cart berdasarkan state terbaru.
function render() {
    const state = getState();

    ProductList(state.products, addToCart);
    Cart(state.cart, checkout);
}

// Inisialisasi aplikasi: mengambil data produk, menyimpan ke state, lalu menampilkan UI.
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

// Menjalankan aplikasi.
init();
