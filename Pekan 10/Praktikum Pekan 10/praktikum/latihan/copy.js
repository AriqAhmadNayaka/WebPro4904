
const user = {
  name: "Alice",
  address: {
    city: "Bandung",
  },
};


const userCopy = { ...user };
userCopy.name = "Bob";


userCopy.address.city = "Jakarta";

console.log("Praktikum 30 - user.name:", user.name);
console.log("Praktikum 30 - userCopy.name:", userCopy.name);
console.log("Praktikum 30 - user.address.city:", user.address.city);
console.log("Praktikum 30 - userCopy.address.city:", userCopy.address.city);



const matrix = [[1, 2], [3, 4]];
const matrixCopy = [...matrix];

matrixCopy[0][0] = 999;

console.log("Praktikum 31 - matrix[0][0]:", matrix[0][0]);
console.log("Praktikum 31 - matrixCopy[0][0]:", matrixCopy[0][0]);


const profil = {
  nama: "Charlie",
  alamat: {
    kota: "Surabaya",
    kodePos: "60100",
  },
};


const profilCopy = JSON.parse(JSON.stringify(profil));
profilCopy.alamat.kota = "Malang";

console.log("Praktikum 32 - profil.alamat.kota:", profil.alamat.kota);
console.log("Praktikum 32 - profilCopy.alamat.kota:", profilCopy.alamat.kota); 
