export function Cart(cart, onCheckout) {
  const container = document.getElementById("cart");
  container.innerHTML = "<h2>Your Cart</h2>";

  if (cart.length === 0) {
    container.innerHTML += "<p>Cart is empty.</p>";
    return;
  }

  const list = document.createElement("div");
  list.className = "cart-list";

  cart.map((item) => {
    const div = document.createElement("div");

    div.className = "cart-item";

    div.innerHTML = `
      <img src="${item.image}" alt="${item.title}" />
      <div>
        <h4>${item.title}</h4>
        <span>$${item.price}</span>
      </div>
    `;

    list.appendChild(div);
  });

  container.appendChild(list);

  const total = cart.reduce((acc, item) => acc + item.price, 0);

  const footer = document.createElement("div");

  footer.innerHTML = `
    <div class="total-row">
      <span>Total:</span>
      <span>$${total.toFixed(2)}</span>
    </div>

    <button class="btn-primary" id="checkout-btn">
      Buy Now
    </button>
  `;

  footer.querySelector("#checkout-btn")
    .addEventListener("click", onCheckout);

  container.appendChild(footer);
}
