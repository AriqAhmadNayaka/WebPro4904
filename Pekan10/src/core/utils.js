// deepClone ini buat nyalin data sampai bagian dalamnya, bukan cuma luarnya aja.
export function deepClone(obj) {
// Kalau datanya kosong atau bukan object, langsung balikin aja.
if (obj === null || typeof obj !== "object") {
return obj;
}

// Kalau datanya tanggal, bikin object Date baru supaya tidak nyambung ke data lama.
if (obj instanceof Date) {
return new Date(obj.getTime());
}

// Kalau datanya array, setiap item ikut dicopy satu-satu.
if (Array.isArray(obj)) {
return obj.map((item) => deepClone(item));
}

// Kalau datanya object biasa, semua property dicopy pakai perulangan.
const clonedObj = {};
for (const key in obj) {
if (Object.prototype.hasOwnProperty.call(obj, key)) {
clonedObj[key] = deepClone(obj[key]);
}
}

// Setelah semua selesai dicopy, hasil copy-an dikirim balik.
return clonedObj;
}
