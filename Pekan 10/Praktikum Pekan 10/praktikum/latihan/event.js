

const app = document.getElementById("app");



// Langkah 2: Membuat Tombol
app.innerHTML = `
  <div style="font-family:sans-serif; padding: 20px;">
    <h3 style="color:#38bdf8; margin-bottom:16px;">Praktikum 26-29 - Event Handling</h3>
    <button id="btnKlik" style="
      background:#0ea5e9; color:white; border:none;
      padding:10px 24px; border-radius:6px; cursor:pointer;
      font-size:1rem; margin-right:12px;">
      Klik Saya
    </button>
    <button id="btnThis" style="
      background:#6366f1; color:white; border:none;
      padding:10px 24px; border-radius:6px; cursor:pointer;
      font-size:1rem; margin-right:12px;">
      Uji this (function biasa)
    </button>
    <button id="btnArrow" style="
      background:#f59e0b; color:white; border:none;
      padding:10px 24px; border-radius:6px; cursor:pointer;
      font-size:1rem;">
      Uji this (arrow)
    </button>
  </div>
`;


const button = document.getElementById("btnKlik");
button.addEventListener("click", function () {
  alert("Tombol berhasil diklik!");
});


function handleKlik() {
  alert("Tombol diklik via function terpisah!");
}

const btnThis = document.getElementById("btnThis");
btnThis.addEventListener("click", function () {

  this.textContent = "Sudah Diklik!";
  this.style.background = "#059669";
  console.log("Praktikum 28 - this:", this);
});


const btnArrow = document.getElementById("btnArrow");
btnArrow.addEventListener("click", () => {

  console.log("Praktikum 29 - this (arrow):", this);
  alert("Arrow function: 'this' bukan button! Cek console.");
});
