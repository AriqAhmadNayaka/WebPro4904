export function Cart(cart, onCheckout) { // ekspor fungsi Cart untuk menampilkan keranjang
  const container = document.getElementById("cart"); // ambil elemen cart di HTML
  container.innerHTML = "<h2>Your Cart</h2>"; // set judul keranjang

  if (cart.length === 0) { // jika keranjang kosong
    container.innerHTML += "<p class='empty-msg'>Cart is empty.</p>"; // tambahkan pesan kosong
    return; // hentikan render selanjutnya
  }

  const list = document.createElement("div"); // buat elemen daftar item
  list.className = "cart-list"; // set kelas CSS daftar

  cart.map((item) => { // iterasi setiap item di keranjang
    const div = document.createElement("div"); // buat elemen item
    div.className = "cart-item"; // set kelas CSS item
    div.innerHTML = `
      <img src="${item.image}" alt="${item.title}" />
      <div class="cart-details">
        <h4>${item.title}</h4>
        <span>$${item.price}</span>
      </div>
    `; // isi HTML item
    list.appendChild(div); // tambahkan item ke daftar
  });

  container.appendChild(list); // tambahkan daftar item ke container

  const total = cart.reduce((acc, item) => acc + (item.price || 0), 0); // hitung total harga

  const footer = document.createElement("div"); // buat elemen footer ringkasan
  footer.className = "cart-footer-summary"; // set kelas CSS footer
  footer.innerHTML = `
    <div class="total-row">
      <span>Total:</span>
      <span class="total-price">$${total.toFixed(2)}</span>
    </div>
    <button class="btn-primary" id="checkout-btn">Buy Now</button>
  `; // isi HTML footer

  footer.querySelector("#checkout-btn").addEventListener("click", onCheckout); // pasang event checkout
  container.appendChild(footer); // tambahkan footer ke container
}