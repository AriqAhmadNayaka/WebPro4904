export function deepClone(obj) {
  if (obj === null || typeof obj !== "object") {
    return obj;
  }

  // Array
  if (Array.isArray(obj)) {
    return obj.map((item) => deepClone(item));
  }

  // Object
  const clonedObj = {};
  for (const key in obj) {
    if (Object.prototype.hasOwnProperty.call(obj, key)) {
      clonedObj[key] = deepClone(obj[key]);
    }
  }

  return clonedObj;
}
