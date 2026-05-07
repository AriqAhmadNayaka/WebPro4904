import { deepClone } from "./utils.js";

// State ini tempat nyimpen data utama aplikasi, seperti daftar produk dan isi cart.
let state = {
products: [],
cart: [],
};

// getState dipakai kalau komponen butuh baca data tanpa mengubah data aslinya.
export const getState = () => deepClone(state);

// setState dipakai buat update state, misalnya saat produk masuk ke keranjang.
export const setState = (newState) => {
    const nextState = { ...state, ...newState };

state = nextState;

// Console ini membantu ngecek apakah state sudah berubah atau belum.
console.log("[Store] State updated:", state);
};
