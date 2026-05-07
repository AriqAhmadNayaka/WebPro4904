import { getState } from "./Store.js";

// Fungsi untuk menyalin object/array secara mendalam.
export function deepClone(obj) {
    // Nilai primitif langsung dikembalikan karena tidak perlu dicloning.
    if (obj === null || typeof obj !== "object") {
        return obj;
    }

    // Date perlu dibuat ulang agar tidak memakai referensi yang sama.
    if (obj instanceof Date) {
        return new Date(obj.getTime());
    }

    // Jika data berupa array, clone setiap item di dalamnya.
    if (Array.isArray(obj)) {
        return obj.map((item) => deepClone(item));
    }

    const clonedObj = {};
    // Clone setiap property object secara rekursif.
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            clonedObj[key] = deepClone(obj[key]);
        }
    }

    return clonedObj;
}
