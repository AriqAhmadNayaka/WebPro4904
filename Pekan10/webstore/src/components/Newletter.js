export function Newsletter() {
  const $container = $('<div class="newsletter-section"></div>'); //

  // Konten newsletter dengan form dan pesan sukses
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

  // Tambahkan konten ke container dan render di halaman
  $container.append($content);
  $("#app").append($container);

  // Event handler untuk form submission
  $content.find("form").on("submit", function (e) {
    e.preventDefault();

    const $form = $(this);
    $form.slideUp(400, function () {
      // Tampilkan pesan sukses dengan efek fade in
      $content.find(".success-message").fadeIn();
    });
  });
}
