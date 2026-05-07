

const app = document.getElementById("app");


const produk = ["Laptop", "Mouse", "Keyboard", "Monitor", "Headphone"];
app.innerHTML = `
  <h3 style="color:#38bdf8; font-family:sans-serif;">Praktikum 24 - Daftar Produk</h3>
  <ul style="font-family:sans-serif; color:#e2e8f0;">
    ${produk.map((item) => `<li>${item}</li>`).join("")}
  </ul>
`;


const produkDetail = [
  { id: 1, nama: "Laptop Pro", harga: 15000000 },
  { id: 2, nama: "Gaming Mouse X", harga: 350000 },
  { id: 3, nama: "Mechanical Keyboard", harga: 800000 },
  { id: 4, nama: "4K Monitor 27\"", harga: 5500000 },
];


app.innerHTML += `
  <h3 style="color:#38bdf8; font-family:sans-serif; margin-top:24px;">
    Praktikum 25 - Produk dengan Harga
  </h3>
  <ul style="font-family:sans-serif; color:#e2e8f0; list-style:none; padding:0;">
    ${produkDetail
    .map(
      (p) =>
        `<li style="padding:8px 0; border-bottom:1px solid #334155;">
            <strong>${p.nama}</strong> — 
            Rp ${p.harga.toLocaleString("id-ID")}
          </li>`
    )
    .join("")}
  </ul>
`;
