export function Newsletter() { // ekspor fungsi Newsletter untuk menampilkan form langganan
  const $container = $('<div class="newsletter-section"></div>'); // buat kontainer newsletter
  const $content = $(`
    <div class="newsletter-content">
      <h3>Subscribe to our updates</h3>
      <p>Get the latest tech news and offers.</p>
      <form id="newsletter-form">
        <input type="email" placeholder="Enter your email" required />
        <button type="submit" class="btn-primary">Subscribe</button>
      </form>
      <div class="success-message" style="display:none; margin-top: 10px; color: green;">
        Thanks for subscribing!
      </div>
    </div>
  `); // buat HTML form newsletter

  $container.append($content); // tambahkan konten ke kontainer
  $("#app").append($container); // tambahkan kontainer ke elemen app

  $content.find("form").on("submit", function (e) { // tangani event submit form
    E.preventDefault(); // hentikan aksi default form
    const $form = $(this); // ambil elemen form yang dikirim

    $form.slideUp(400, function() { // sembunyikan form dengan animasi
      $content.find(".success-message").fadeIn(); // tampilkan pesan sukses
    });
  });
}