import { deepClone } from "./utils.js"; // impor fungsi deepClone dari utils

let state = { // state awal aplikasi
  products: [], // daftar produk
  cart: [], // keranjang belanja
};

export const getState = () => deepClone(state); // getter state yang mengembalikan salinan

export const setState = (newState) => { // setter state dengan merge nilai baru
  const nextState = { ...state, ...newState }; // gabungkan state lama dan baru
  state = nextState; // update state global
  console.log("[Store] State updated:", state); // log state untuk debugging
};