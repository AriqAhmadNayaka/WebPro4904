import { fetchProducts } from "./api/productApi.js";
import { getState, setState } from "./core/store.js";
import { ProductList } from "./components/ProductList.js";
import { Cart } from "./components/Cart.js";
import { Newsletter } from "./components/Newsletter.js";

// Fungsi untuk menambahkan produk ke keranjang dengan konsep immutability.
function addToCart(product) {
  const state = getState();

  // Spread operator membuat array cart baru tanpa mengubah state lama secara langsung.
  setState({
    cart: [...state.cart, product],
  });

  // Render ulang agar tampilan produk dan cart mengikuti state terbaru.
  render();
}

// Fungsi checkout mengosongkan keranjang setelah transaksi selesai.
function checkout() {
  setState({ cart: [] });
  alert("Thank you for your purchase!");
  render();
}

// Fungsi pusat untuk menggambar ulang komponen berdasarkan state aplikasi.
function render() {
  const state = getState();

  ProductList(state.products, addToCart);
  Cart(state.cart, checkout);
}

// Fungsi awal aplikasi: mengambil data produk lalu menampilkan UI.
async function init() {
  try {
    const data = await fetchProducts();

    // Simpan data produk ke state global sebelum komponen dirender.
    setState({ products: data });
    render();
    Newsletter();
  } catch (error) {
    // Menangani error jika proses pengambilan produk gagal.
    console.error("Failed to load products:", error);
  }
}

// Menjalankan aplikasi saat file app.js dimuat oleh browser.
init();
