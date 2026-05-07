export function ProductList(products, onAdd) { // ekspor fungsi untuk menampilkan produk
  const container = document.getElementById("product-list"); // ambil elemen daftar produk
  container.innerHTML = ""; // kosongkan kontainer sebelum render produk

  products.map((product) => { // iterasi setiap produk dalam array
    const card = document.createElement("div"); // buat elemen kartu produk
    card.className = "product-card"; // set kelas CSS kartu produk

    const discount = Math.floor(Math.random() * 20) + 5; // hitung diskon acak
    const rating = product.rating || { rate: 4.5, count: 100 }; // ambil rating atau default

    card.innerHTML = `
      <div class="card-image"> <!-- pembungkus gambar -->
        <img src="${product.image}" alt="${product.title}" /> <!-- gambar produk -->
      </div>
      <div class="card-content"> <!-- isi kartu produk -->
        <span class="badge">Up to ${discount}% off</span> <!-- label diskon -->
        <a href="#" class="card-title" title="${product.title}">${product.title}</a> <!-- judul produk -->

        <div class="rating-row"> <!-- baris rating -->
          <div class="stars"> <!-- ikon bintang -->
            ${getStarSVG().repeat(5)} <!-- ulasan bintang -->
          </div>
          <span class="rating-score">${rating.rate}</span> <!-- skor rating -->
          <span class="rating-count">(${rating.count})</span> <!-- jumlah ulasan -->
        </div>

        <ul class="features-list"> <!-- daftar fitur -->
          <li class="feature"> <!-- fitur produk -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <!-- ikon fast delivery -->
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
            </svg>
            Fast Delivery <!-- teks fitur cepat -->
          </li>
        </ul>

        <div class="card-footer"> <!-- footer kartu -->
          <span class="price">$${product.price}</span> <!-- harga produk -->
          <button class="add-btn btn-primary" type="button"> <!-- tombol tambah ke keranjang -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <!-- ikon add -->
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
            </svg>
            Add <!-- teks tombol add -->
          </button>
        </div>
      </div>
    `; // set HTML isi kartu produk

    card.querySelector(".add-btn").addEventListener("click", function () { // pasang event klik tombol add
      onAdd(product); // panggil callback untuk menambahkan produk
    });

    container.appendChild(card); // tambahkan kartu produk ke kontainer
    return card; // kembalikan elemen kartu
  });
}

function getStarSVG() { // fungsi pembantu untuk membuat ikon bintang
  return `
    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"> <!-- markup SVG bintang -->
      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292z"></path> <!-- bentuk bintang -->
    </svg>
  `; // kembalikan string SVG bintang
}