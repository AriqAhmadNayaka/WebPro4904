import { fetchProducts } from "./webstore/src/api/productApi.js";
import { getState, setState } from "./webstore/src/core/Store.js";
import { ProductList } from "./webstore/src/components/ProductList.js";
import { Cart } from "./webstore/src/components/Cart.js";
import { Newsletter } from "./webstore/src/components/Newsletter.js";
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
function render() {
const state = getState();
 ProductList(state.products, addToCart);
 Cart(state.cart, checkout);
}
async function init() {
 try {
const data = await fetchProducts();
 setState({ products: data });
 render();
 Newsletter();
 } catch (error) {
 console.error("Failed to load products:", error);
 }
}
init();
