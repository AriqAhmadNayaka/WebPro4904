// deepClone membuat salinan data secara rekursif agar object/array asli tetap aman.
export function deepClone(obj) {
  // Nilai primitive seperti string, number, boolean, dan null langsung dikembalikan.
  if (obj === null || typeof obj !== "object") {
    return obj;
  }

  // Jika data berupa Date, buat object Date baru dengan waktu yang sama.
  if (obj instanceof Date) {
    return new Date(obj.getTime());
  }

  // Jika data berupa array, clone setiap item di dalamnya.
  if (Array.isArray(obj)) {
    return obj.map((item) => deepClone(item));
  }

  // Jika data berupa object, clone setiap property miliknya.
  const clonedObj = {};
  for (const key in obj) {
    if (Object.prototype.hasOwnProperty.call(obj, key)) {
      clonedObj[key] = deepClone(obj[key]);
    }
  }

  return clonedObj;
}
