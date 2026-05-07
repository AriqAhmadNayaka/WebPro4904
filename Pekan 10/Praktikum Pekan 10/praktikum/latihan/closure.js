
function buatSalam(nama) {
  // fungsi dalam bisa mengakses 'nama' dari scope luar
  return function () {
    console.log("Praktikum 16 - Halo, " + nama + "!");
  };
}

const salamBudi = buatSalam("Budi");
salamBudi(); //

const salamAni = buatSalam("Ani");
salamAni();  // 


