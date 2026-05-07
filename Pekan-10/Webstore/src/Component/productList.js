export function ProductList(products, onAdd) {
    // Tempat card produk nanti dimasukkan.
    const container = document.getElementById("product-list");
    // Kosongkan dulu supaya card tidak numpuk saat render ulang.
    container.innerHTML = "";
    // Buat satu card untuk setiap produk.
    products.map((product) => {
        const card = document.createElement("div");
        // Angka diskon dibuat acak biar badge promo tidak sama semua.
        const discount = Math.floor(Math.random() * 20) + 5;
        // Kalau suatu produk belum punya rating, pakai nilai cadangan ini.
        const rating = product.rating || { rate: 4.5, count: 100 };
        card.className = "product-card";
        // Isi HTML untuk satu card produk.
        card.innerHTML = `
            <div class="card-image">
                <img src="${product.image}" alt="${product.title}">
            </div>
            <div class="card-content">
                <span class="badge">Up to ${discount}% off</span>
                <a href="#" class="card-title" title="${product.title}">${product.title}</a>

                <div class="rating-row">
                    <div class="stars">
                        ${getStarSVG().repeat(5)}
                    </div>
                    <span class="rating-score">${rating.rate}</span>
                    <span class="rating-count">(${rating.count})</span>
                </div>

                <ul class="features-list">
                    <li class="feature">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"></path>
                        </svg>
                        Fast Delivery
                    </li>
                </ul>

                <div class="card-footer">
                    <span class="price">$${product.price}</span>
                    <button class="add-btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6"></path>
                        </svg>
                        Add
                    </button>
                </div>
            </div>
        `;
        // Saat tombol Add diklik, produk ini dikirim ke cart.
        card.querySelector(".add-btn").addEventListener("click", function () {
            onAdd(product);
        });
        // Masukkan card yang sudah jadi ke grid produk.
        container.appendChild(card);
    });
}
// Bintang rating dipisah di fungsi sendiri supaya template card tidak terlalu panjang.
function getStarSVG() {
    return `
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" width="20" height="20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
        </svg>
    `;
}
