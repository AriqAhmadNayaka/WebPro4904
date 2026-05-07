import { deepClone } from "./utils.js";

// State global menyimpan data utama aplikasi.
let state = {
  products: [],
  cart: [],
};

// getState mengembalikan salinan state agar data asli tidak berubah langsung.
export const getState = () => deepClone(state);

// setState memperbarui state dengan menggabungkan state lama dan data baru.
export const setState = (newState) => {
  // Spread operator membuat object baru sebagai penerapan immutability.
  const nextState = { ...state, ...newState };

  state = nextState;

  // Log digunakan untuk melihat perubahan state pada console browser.
  console.log("[Store] State updated:", state);
};
