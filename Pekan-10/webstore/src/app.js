import { fetchProducts } from "./api/productApi.js"; // impor fungsi untuk mengambil produk
import { getState, setState } from "./core/Store.js"; // impor getter dan setter state
import { ProductList } from "./components/ProductList.js"; // impor komponen daftar produk
import { Cart } from "./components/Cart.js"; // impor komponen keranjang
import { Newsletter } from "./components/Newsletter.js"; // impor komponen newsletter

function addToCart(product) { // fungsi untuk menambahkan produk ke keranjang
  const state = getState(); // ambil state saat ini

  setState({ // update state dengan item baru
    cart: [...state.cart, product], // gabungkan item lama dan produk baru
  });

  render(); // render ulang tampilan
}

function checkout() { // fungsi untuk transaksi checkout
  setState({ cart: [] }); // kosongkan keranjang
  alert("Thank you for your purchase!"); // tampilkan pesan terima kasih
  render(); // render ulang tampilan
}

function render() { // fungsi render utama halaman
  const state = getState(); // ambil state terbaru

  ProductList(state.products, addToCart); // render daftar produk
  Cart(state.cart, checkout); // render keranjang
}

async function init() { // inisialisasi aplikasi
  try {
    const data = await fetchProducts(); // ambil data produk dari API
    setState({ products: data }); // simpan produk ke state
    render(); // render halaman awal
    Newsletter(); // render komponen newsletter
  } catch (error) { // jika terjadi error
    console.error("Failed to load products:", error); // tampilkan log error
  }
}

init(); // mulai aplikasi
