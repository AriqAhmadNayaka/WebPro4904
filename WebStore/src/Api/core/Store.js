// Store sederhana untuk menyimpan dan memperbarui data utama aplikasi.
import { deepClone } from "./Utils.js";

let state = {
    products: [],
    cart: [],
};

// Mengembalikan salinan state agar data asli tidak berubah langsung dari luar.
export const getState = () => deepClone(state);

// Menggabungkan state lama dengan data baru, lalu menyimpan hasilnya.
export const setState = (newState) => {
    const nextState = { ...state, ...newState };

    state = nextState;

    console.log("[Store] State updated:", state);
};
