// Fungsi bantu untuk menyalin data sampai ke bagian dalamnya.
export function deepClone(obj) {
    // Kalau datanya bukan object, langsung balikin saja.
    if (obj === null || typeof obj !== "object") {
        return obj;
    }
    // Date dibuat baru supaya tidak masih terhubung ke data lama.
    if (obj instanceof Date) {
        return new Date(obj.getTime());
    }
    // Kalau bentuknya array, isi array ikut disalin satu per satu.
    if (Array.isArray(obj)) {
        return obj.map((item) => deepClone(item));
    }
    const clonedObj = {};
    // Kalau bentuknya object biasa, salin semua property-nya.
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            clonedObj[key] = deepClone(obj[key]);
        }
    }
    return clonedObj;
}
