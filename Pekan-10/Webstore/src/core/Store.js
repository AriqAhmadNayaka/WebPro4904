import { deepClone } from "./utils.js";

// Tempat nyimpan data utama aplikasi.
let state = {
    products: [],
    cart: [],
};

// Ambil state dalam bentuk salinan, jadi data asli tidak gampang berubah dari luar.
export const getState = () => deepClone(state);

// Pakai fungsi ini kalau mau mengubah isi state.
export const setState = (newState) => {
    // Gabungkan data lama dengan data baru yang dikirim.
    const nextState = { ...state, ...newState };

    // Setelah digabung, state utama diganti dengan versi terbaru.
    state = nextState;

    // Ini berguna buat cek perubahan state lewat console browser.
    console.log("[Store] State updated:", state);
};
