import { fetchProducts } from "./src/api/productApi.js";
import { ProductList } from "./src/components/ProductList.js";
import { Cart } from "./src/components/Cart.js";
import { Newsletter } from "./src/components/Newsletter.js";
import { getState, setState } from "./src/core/Store.js";

let filterActive = false;
let sortDirection = "default";

function addToCart(product) {
  const state = getState();

  setState({
    cart: [...state.cart, product],
  });

  render();
}

function checkout() {
  setState({ cart: [] });
  alert("Thank you for your purchase!");
  render();
}

function removeFromCart(productId) {
  const state = getState();

  const index = state.cart.findIndex((item) => item.id === productId);
  if (index === -1) {
    return;
  }

  const updatedCart = [...state.cart];
  updatedCart.splice(index, 1);

  setState({ cart: updatedCart });
  render();
}

function getVisibleProducts(products) {
  let result = [...products];

  if (filterActive) {
    result = result.filter((product) => product.price <= 100);
  }

  if (sortDirection === "asc") {
    result.sort((a, b) => a.price - b.price);
  } else if (sortDirection === "desc") {
    result.sort((a, b) => b.price - a.price);
  }

  return result;
}

function updateToolbarLabels() {
  const filterButton = document.getElementById("filter-btn");
  const sortButton = document.getElementById("sort-btn");

  if (filterButton) {
    const filterLabel = filterButton.querySelector(".btn-label");
    filterLabel.textContent = filterActive ? "Filter: Under $100" : "Filters";
  }

  if (sortButton) {
    const sortLabel =
      sortDirection === "asc"
        ? "Sort: Lowest"
        : sortDirection === "desc"
          ? "Sort: Highest"
          : "Sort";

    sortButton.querySelector(".btn-label").textContent = sortLabel;
  }
}

function render() {
  const state = getState();
  const visibleProducts = getVisibleProducts(state.products);

  ProductList(visibleProducts, addToCart);
  Cart(state.cart, checkout, removeFromCart);
  updateToolbarLabels();
}

function handleFilterToggle() {
  filterActive = !filterActive;
  render();
}

function handleSortToggle() {
  if (sortDirection === "default") {
    sortDirection = "asc";
  } else if (sortDirection === "asc") {
    sortDirection = "desc";
  } else {
    sortDirection = "default";
  }

  render();
}

function setupInteractions() {
  document.getElementById("home-link")?.addEventListener("click", (event) => {
    event.preventDefault();
    filterActive = false;
    sortDirection = "default";
    window.scrollTo({ top: 0, behavior: "smooth" });
    render();
  });

  document.getElementById("products-link")?.addEventListener("click", (event) => {
    event.preventDefault();
    document.getElementById("product-list")?.scrollIntoView({ behavior: "smooth" });
  });

  document.getElementById("filter-btn")?.addEventListener("click", handleFilterToggle);
  document.getElementById("sort-btn")?.addEventListener("click", handleSortToggle);
}

async function init() {
  try {
    const data = await fetchProducts();
    setState({ products: data });
    setupInteractions();
    render();
    Newsletter();
  } catch (error) {
    console.error("Failed to load products:", error);
  }
}

init();
