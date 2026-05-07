

function tampilkanData(data) {
  console.log("Praktikum 14 - Data dari callback:", data);
}

function prosesData(nilai, callback) {
  // callback dipanggil di dalam fungsi lain
  callback(nilai);
}

prosesData("Hello dari callback!", tampilkanData);

function prosesArrow(nilai, callback) {
  callback(nilai);
}

prosesArrow("Arrow callback berhasil!", (data) => {
  console.log("Praktikum 15 - Arrow Callback:", data);
});
