import { deepClone } from "./utils.js";

// State utama aplikasi untuk menyimpan daftar produk dan isi cart.
let state = {
    products: [],
    cart: [],
};

// Mengembalikan salinan state agar data asli tidak berubah langsung.
export const getState = () => deepClone(state);

// Menggabungkan state lama dengan data baru menggunakan spread operator.
export const setState = (newState) => {

    const nextState = { ...state, ...newState };

    state = nextState;

    console.log("[Store] State updated:", state);
};
