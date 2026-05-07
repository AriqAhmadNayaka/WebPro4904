// Komponen Newsletter dibuat dengan jQuery untuk form subscribe.
export function Newsletter() {
  // Cegah newsletter dibuat dua kali saat render dipanggil berulang.
  if (document.querySelector(".newsletter-section")) return;

  // Membuat container dan isi newsletter menggunakan sintaks jQuery.
  const $container = $('<div class="newsletter-section"></div>');
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
  `);

  $container.append($content);
  $("#app").append($container);

  // Saat form dikirim, halaman tidak refresh dan form diganti pesan sukses.
  $content.find("form").on("submit", function (event) {
    event.preventDefault();

    const $form = $(this);

    // Efek slideUp dan fadeIn merupakan contoh penggunaan animasi jQuery.
    $form.slideUp(400, function () {
      $content.find(".success-message").fadeIn();
    });
  });
}
