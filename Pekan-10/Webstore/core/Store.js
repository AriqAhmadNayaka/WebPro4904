import { deepClone } from "./utils.js";

// State global sederhana untuk menyimpan daftar produk dan isi keranjang.
let state = {
products: [],
cart: [],
};

// Mengembalikan salinan state supaya data asli tidak berubah dari luar store.
export const getState = () => deepClone(state);

// Menggabungkan state lama dengan perubahan baru, lalu menyimpannya kembali.
export const setState = (newState) => {

const nextState = { ...state, ...newState };

state = nextState;

console.log("[Store] State updated:", state);
};
