// Komponen untuk menampilkan isi keranjang belanja.
export function Cart(cart, onCheckout) {
    const container = document.getElementById("cart");
    container.innerHTML = "<h2>Your Cart</h2>";

    // Jika belum ada produk, tampilkan pesan cart kosong.
    if (cart.length === 0) {
        container.innerHTML += "<p class='empty-msg'>Cart is empty.</p>";
        return;
    }

    const list = document.createElement("div");
    list.className = "cart-list";

    // Membuat elemen cart item untuk setiap produk yang dipilih.
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

    // Menghitung total harga semua produk di cart.
    const total = cart.reduce((acc, item) => acc + (item.price || 0), 0);

    const footer = document.createElement("div");
    footer.className = "cart-footer-summary";
    footer.innerHTML = `
<div class="total-row">
<span>Total:</span>
<span class="total-price">$${total.toFixed(2)}</span>
</div>
<button class="btn-primary" id="checkout-btn">Buy Now</button>
`;

    // Tombol Buy Now menjalankan fungsi checkout dari app.js.
    footer.querySelector("#checkout-btn").addEventListener("click", onCheckout);
    container.appendChild(footer);
}
