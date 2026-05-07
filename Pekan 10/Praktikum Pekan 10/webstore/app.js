


import { fetchProducts } from "./src/api/productApi.js";
import { getState, setState } from "./src/core/Store.js";
import { ProductList } from "./src/components/ProductList.js";
import { Cart } from "./src/components/Cart.js";
import { Newsletter } from "./src/components/Newsletter.js";




function addToCart(product) {
  const state = getState();


  const exists = state.cart.find((item) => item.id === product.id);
  if (exists) {
    showToast(`"${product.title}" sudah ada di keranjang!`);
    return;
  }


  setState({
    cart: [...state.cart, product],
  });

  updateCartCount();
  render();
  showToast(`✅ "${product.title}" ditambahkan ke keranjang!`);
}


// Fungsi: Checkout

function checkout() {
  setState({ cart: [] });
  updateCartCount();
  closeSidebar();
  render();
  showToast("🎉 Terima kasih atas pembelian Anda!");
}


// Fungsi: Render ulang semua komponen

function render() {
  const state = getState();
  ProductList(state.products, addToCart);
  Cart(state.cart, checkout);
}


// Fungsi: Update badge jumlah item di cart

function updateCartCount() {
  const state = getState();
  $("#cart-count").text(state.cart.length);
}


// Fungsi: Tampilkan sidebar cart

function openSidebar() {
  $("#cart-sidebar").addClass("open");
  $("#overlay").addClass("show");
}

// Fungsi global agar bisa dipanggil dari HTML onclick
window.closeSidebar = function () {
  $("#cart-sidebar").removeClass("open");
  $("#overlay").removeClass("show");
};


// Fungsi: Toast notification (jQuery)

function showToast(message) {
  // Buat atau ambil elemen toast
  let $toast = $(".toast");
  if (!$toast.length) {
    $toast = $('<div class="toast"></div>');
    $("body").append($toast);
  }

  $toast.text(message).addClass("show");

  setTimeout(() => {
    $toast.removeClass("show");
  }, 2500);
}


// Fungsi: Inisialisasi aplikasi

async function init() {
  try {
    // Tampilkan skeleton loading
    $("#product-list").html(
      Array(6)
        .fill(
          `<div style="height:380px; border-radius:8px;" class="skeleton"></div>`
        )
        .join("")
    );

    // Fetch produk dari API (async/await)
    const data = await fetchProducts();
    setState({ products: data });

    render();
    Newsletter();


    $(document).on("click", "#btn-cart-toggle", openSidebar);
    $(document).on("click", "#btn-filter", () =>
      showToast("🔧 Fitur filter akan segera hadir!")
    );
    $(document).on("click", "#btn-sort", () =>
      showToast("🔧 Fitur sort akan segera hadir!")
    );
  } catch (error) {
    console.error("Gagal memuat produk:", error);
    $("#product-list").html(
      `<p style="color:red; font-family:sans-serif;">
        ⚠️ Gagal memuat produk. Silakan refresh halaman.
       </p>`
    );
  }
}


init();
