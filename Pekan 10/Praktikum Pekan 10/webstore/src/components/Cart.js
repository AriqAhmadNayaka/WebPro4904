

/**
 * 
 * @param {Array} cart       
 * @param {Function} onCheckout 
 */
export function Cart(cart, onCheckout) {

  const sidebar = document.getElementById("cart-sidebar");


  const listEl = document.getElementById("sidebar-cart-list");
  const footerEl = document.getElementById("sidebar-cart-footer");

  if (!listEl || !footerEl) return;


  listEl.innerHTML = "";
  footerEl.innerHTML = "";

  if (cart.length === 0) {
    listEl.innerHTML = "<p class='empty-msg'>Keranjang masih kosong 🛒</p>";
    return;
  }


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
    listEl.appendChild(div);
  });


  const total = cart.reduce((acc, item) => acc + (item.price || 0), 0);

  footerEl.innerHTML = `
    <div class="total-row">
      <span>Total:</span>
      <span class="total-price">$${total.toFixed(2)}</span>
    </div>
    <button class="btn-primary" id="checkout-btn" style="width:100%;">
      Buy Now 🛍️
    </button>
  `;


  footerEl.querySelector("#checkout-btn").addEventListener("click", onCheckout);


  const inlineCart = document.getElementById("cart");
  if (inlineCart) {
    inlineCart.innerHTML = `
      <h2>Your Cart (${cart.length} item${cart.length > 1 ? "s" : ""})</h2>
      ${cart.length === 0
        ? "<p class='empty-msg'>Cart is empty.</p>"
        : `<ul style="list-style:none; padding:0;">
              ${cart
          .map(
            (item) =>
              `<li style="display:flex; align-items:center; gap:12px; padding:8px 0; border-bottom:1px solid var(--border-color);">
                      <img src="${item.image}" alt="${item.title}" style="width:40px; height:40px; object-fit:cover; border-radius:4px;" />
                      <span>${item.title}</span>
                      <span style="margin-left:auto; font-weight:700; color:var(--primary);">$${item.price}</span>
                    </li>`
          )
          .join("")}
            </ul>
            <p style="margin-top:12px; font-weight:700;">
              Total: <span style="color:var(--primary);">$${total.toFixed(2)}</span>
            </p>`
      }
    `;
  }
}
