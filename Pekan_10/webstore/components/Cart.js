// Komponen Cart bertugas menampilkan isi keranjang dan tombol checkout.
export function Cart(cart, onCheckout) {
  const container = document.getElementById("cart");
  container.innerHTML = "<h2>Your Cart</h2>";

  // Jika cart kosong, tampilkan pesan kosong dan hentikan proses render.
  if (cart.length === 0) {
    container.innerHTML += "<p class='empty-msg'>Cart is empty.</p>";
    return;
  }

  // Membuat wrapper untuk daftar produk yang masuk ke keranjang.
  const list = document.createElement("div");
  list.className = "cart-list";

  // Loop setiap item cart dan ubah menjadi elemen HTML.
  cart.map((item) => {
    const div = document.createElement("div");
    div.className = "cart-item";
    div.innerHTML = `
      <img src="${item.image}" alt="${item.title}" />
      <div class="cart-details">
        <h4>${item.title}</h4>
        <span>$${item.price}</span>
      </div>
    `;
    list.appendChild(div);
  });

  container.appendChild(list);

  // reduce digunakan untuk menghitung total harga seluruh item di cart.
  const total = cart.reduce((acc, item) => acc + (item.price || 0), 0);

  // Footer berisi total harga dan tombol checkout.
  const footer = document.createElement("div");
  footer.className = "cart-footer-summary";
  footer.innerHTML = `
    <div class="total-row">
      <span>Total:</span>
      <span class="total-price">$${total.toFixed(2)}</span>
    </div>
    <button class="btn-primary" id="checkout-btn">Buy Now</button>
  `;

  // Event click tombol Buy Now akan menjalankan fungsi checkout dari app.js.
  footer.querySelector("#checkout-btn").addEventListener("click", onCheckout);
  container.appendChild(footer);
}
