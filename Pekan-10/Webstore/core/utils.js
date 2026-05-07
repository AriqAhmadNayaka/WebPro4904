import { getState } from "./Store.js";

// Fungsi rekursif untuk menyalin object/array secara mendalam.
export function deepClone(obj) {
    // Nilai primitif dan null langsung dikembalikan karena tidak perlu disalin.
    if (obj === null || typeof obj !== "object") {
        return obj;
    }

    // Object Date perlu dibuat ulang agar referensinya berbeda dari data asli.
    if (obj instanceof Date) {
        return new Date(obj.getTime());
    }

    // Setiap item array disalin kembali menggunakan deepClone.
    if (Array.isArray(obj)) {
        return obj.map((item) => deepClone(item));
    }

    // Untuk object biasa, salin setiap properti miliknya sendiri.
    const clonedObj = {};
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            clonedObj[key] = deepClone(obj[key]);
        }
    }

    return clonedObj;
}
