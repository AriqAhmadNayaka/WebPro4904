

$(document).ready(function () {

  $("#judul").css("color", "blue");


  $("#judul").text("Judul Berubah via jQuery!");


  $("#tombol").click(function () {
    $("#judul").text("Diklik! Teks dan Warna Berubah.");
    $("#judul").css("color", "red");
  });
});
