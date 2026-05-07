

export function CounterComponent({ title = "Counter" } = {}) {

  let count = 0;


  const wrapper = document.createElement("div");
  wrapper.style.cssText = `
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 24px;
    margin: 16px 0;
    max-width: 300px;
    font-family: 'Segoe UI', sans-serif;
  `;


  const judul = document.createElement("h3");
  judul.textContent = title;
  judul.style.cssText = "color: #38bdf8; margin-bottom: 16px;";

  const display = document.createElement("p");
  display.textContent = `Count: ${count}`;
  display.style.cssText = "color: #e2e8f0; font-size: 1.5rem; margin-bottom: 12px;";

  const tombol = document.createElement("button");
  tombol.textContent = "Tambah";
  tombol.style.cssText = `
    background: #0ea5e9;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
  `;


  tombol.addEventListener("click", function () {
    count++;
    display.textContent = `Count: ${count}`;
  });

  wrapper.appendChild(judul);
  wrapper.appendChild(display);
  wrapper.appendChild(tombol);

  return wrapper;
}
