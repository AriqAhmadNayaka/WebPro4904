import { getState } from "./Store.js"; // impor getState dari Store

export function deepClone(obj) { // fungsi untuk menyalin objek secara mendalam
  if (obj === null || typeof obj !== "object") { // jika bukan objek
    return obj; // kembalikan nilai primitif
  }

  if (obj instanceof Date) { // jika objek Date
    return new Date(obj.getTime()); // kloning Date
  }

  if (Array.isArray(obj)) { // jika array
    return obj.map((item) => deepClone(item)); // kloning rekursif elemen array
  }

  const clonedObj = {}; // buat objek kosong untuk hasil kloning
  for (const key in obj) { // iterasi properti objek
    if (Object.prototype.hasOwnProperty.call(obj, key)) { // pastikan properti milik objek
      clonedObj[key] = deepClone(obj[key]); // kloning nilai properti
    }
  }

  return clonedObj; // kembalikan objek kloning
}