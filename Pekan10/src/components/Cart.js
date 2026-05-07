// Komponen Cart ini buat nampilin isi keranjang belanja.
export function Cart(cart, onCheckout) {
const container = document.getElementById("cart");
container.innerHTML = "<h2>Your Cart</h2>";

// Kalau belum ada barang, cukup tampilkan pesan kosong.
if (cart.length === 0) {
container.innerHTML += "<p class='empty-msg'>Cart is empty.</p>";
return;
}

// Bagian ini nyiapin tempat list barang yang sudah masuk cart.
const list = document.createElement("div");
list.className = "cart-list";

// Setiap item cart dibuat jadi baris kecil berisi gambar, nama, dan harga.
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

// Total harga dihitung dari semua item yang ada di cart.
const total = cart.reduce((acc, item) => acc + (item.price || 0), 0);

// Footer cart berisi total harga dan tombol checkout.
const footer = document.createElement("div");
footer.className = "cart-footer-summary";
footer.innerHTML = `
<div class="total-row">
<span>Total:</span>
<span class="total-price">$${total.toFixed(2)}</span>
</div>
<button class="btn-primary" id="checkout-btn">Buy Now</button>
`;

// Tombol checkout disambungkan ke fungsi dari app.js.
footer.querySelector("#checkout-btn").addEventListener("click", onCheckout);
container.appendChild(footer);
}
