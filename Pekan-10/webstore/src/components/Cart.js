export function Cart(cart, onCheckout, onRemove) {
  const container = document.getElementById("cart");

  container.innerHTML = `
    <aside id="cart-sidebar">
      <h2>Your Cart</h2>
    </aside>
  `;

  const sidebar = container.querySelector("#cart-sidebar");

  if (cart.length === 0) {
    sidebar.innerHTML += "<p class='empty-msg'>Cart is empty.</p>";
    return;
  }

  const list = document.createElement("div");
  list.className = "cart-list";

  cart.forEach((item) => {
    const div = document.createElement("div");
    div.className = "cart-item";
    div.innerHTML = `
      <div class="cart-item-content">
        <img src="${item.image}" alt="${item.title}">
        <div class="cart-details">
          <h4>${item.title}</h4>
          <span>$${item.price}</span>
        </div>
      </div>
      <button class="remove-btn" type="button" data-id="${item.id}">Remove</button>
    `;

    list.appendChild(div);
  });

  sidebar.appendChild(list);

  list.querySelectorAll(".remove-btn").forEach((button) => {
    button.addEventListener("click", () => {
      onRemove(Number(button.dataset.id));
    });
  });

  const total = cart.reduce((acc, item) => acc + (item.price || 0), 0);
  const footer = document.createElement("div");
  footer.className = "cart-footer-summary";
  footer.innerHTML = `
    <div class="total-row">
      <span>Total:</span>
      <span class="total-price">$${total.toFixed(2)}</span>
    </div>
    <button class="btn-primary" id="checkout-btn" type="button">Buy Now</button>
  `;

  footer
    .querySelector("#checkout-btn")
    .addEventListener("click", onCheckout);

  sidebar.appendChild(footer);
}
