export const fetchProducts = async () => { // ekspor fungsi async untuk mengambil daftar produk
  return new Promise((resolve) => { // buat promise baru sebagai simulasi panggilan API
    setTimeout(() => { // tunda eksekusi selama 800ms
      resolve([ // selesaikan promise dengan data produk
        { // data produk pertama
          id: 1, // id unik produk
          title: "Mechanical Keyboard", // judul produk
          price: 150, // harga produk
          rating: { rate: 4.8, count: 230 }, // rating produk
          image: "https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?auto=format&fit=crop&w=800&q=80", // URL gambar
        },
        { // data produk kedua
          id: 2, // id unik produk
          title: "Gaming Mouse", // judul produk
          price: 80, // harga produk
          rating: { rate: 4.6, count: 150 }, // rating produk
          image: "https://images.unsplash.com/photo-1527814050087-3793815479db?auto=format&fit=crop&w=800&q=80", // URL gambar
        },
        { // data produk ketiga
          id: 3, // id unik produk
          title: '4K Monitor 24"', // judul produk dengan tanda petik ganda di string
          price: 300, // harga produk
          rating: { rate: 4.9, count: 85 }, // rating produk
          image: "https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=800&q=80", // URL gambar
        },
        { // data produk keempat
          id: 4, // id unik produk
          title: "Premium Coffee Beans", // judul produk
          price: 25, // harga produk
          rating: { rate: 4.7, count: 500 }, // rating produk
          image: "https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=800&q=80", // URL gambar
        },
        { // data produk kelima
          id: 5, // id unik produk
          title: "Travel Tumbler", // judul produk
          price: 35, // harga produk
          rating: { rate: 4.5, count: 120 }, // rating produk
          image: "https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&w=800&q=80", // URL gambar
        },
        { // data produk keenam
          id: 6, // id unik produk
          title: "Noise Cancelling Headphones", // judul produk
          price: 200, // harga produk
          rating: { rate: 4.8, count: 320 }, // rating produk
          image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80", // URL gambar
        },
      ]); // akhir array produk
    }, 800); // delay 800ms
  }); // akhir promise
}; // akhir fungsi fetchProducts