
const angka = [1, 2, 3, 4];
const hasilMap = angka.map((n) => n * 2);
console.log("Praktikum 10 - Map:", hasilMap);


const hasilFilter = angka.filter((n) => n > 2);
console.log("Praktikum 11 - Filter (> 2):", hasilFilter);


const hasilReduce = angka.reduce((acc, n) => acc + n, 0);
console.log("Praktikum 12 - Reduce (jumlah):", hasilReduce); // 10


function tambah(a, b) {
  return a + b;
}


let total = 0;
function tambahKeTotal(n) {
  total += n;
  return total;
}

console.log("Praktikum 13 - Pure Function:", tambah(3, 5));
console.log("Praktikum 13 - Pure Function (sama):", tambah(3, 5));
console.log("Praktikum 13 - Non-Pure (1):", tambahKeTotal(5));
console.log("Praktikum 13 - Non-Pure (2):", tambahKeTotal(5));     
