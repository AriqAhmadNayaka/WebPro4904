export function Newsletter() {
  // Container utama untuk bagian newsletter.
  const $container = $('<div class="newsletter-section"></div>');
  // Isi newsletter ditulis dalam template supaya lebih ringkas.
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
  // Setelah dibuat, newsletter ditempel ke bagian utama aplikasi.
  $container.append($content);
  $("#app").append($container);
  // Saat user submit email, jangan reload halaman.
  $content.find("form").on("submit", function (e) {
    e.preventDefault();
    const $form = $(this);
    // Form disembunyikan pelan-pelan, lalu pesan sukses muncul.
    $form.slideUp(400, function () {
      $content.find(".success-message").fadeIn();
    });
  });
}
