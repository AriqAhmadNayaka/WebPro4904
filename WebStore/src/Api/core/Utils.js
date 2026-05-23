// Mengimpor getState sesuai struktur awal file.
import { getState } from "./Store.js";

// Utility deepClone: membuat salinan mendalam untuk object, array, dan Date.
export function deepClone(obj) {
    if (obj === null || typeof obj !== "object") {
        return obj;
    }

    if (obj instanceof Date) {
        return new Date(obj.getTime());
    }

    if (Array.isArray(obj)) {
        return obj.map((item) => deepClone(item));
    }

    // Menyalin setiap properti milik object secara rekursif.
    const clonedObj = {};
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            clonedObj[key] = deepClone(obj[key]);
        }
    }

    return clonedObj;
}
