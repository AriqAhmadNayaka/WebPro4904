
export function Newsletter() {

  const $container = $('<div class="newsletter-section"></div>');

  const $content = $(`
    <div class="newsletter-content">
      <h3>Subscribe to Our Updates</h3>
      <p>Dapatkan berita teknologi dan penawaran terbaru langsung ke email Anda.</p>
      <form id="newsletter-form">
        <input type="email" placeholder="Masukkan email Anda" required />
        <button type="submit" class="btn-primary">Subscribe</button>
      </form>
      <div class="success-message" style="display:none; margin-top:16px; color:#0D9488; font-weight:600;">
        ✅ Terima kasih telah subscribe!
      </div>
    </div>
  `);

  $container.append($content);
  $("#app").append($container);


  $content.find("form").on("submit", function (e) {
    e.preventDefault();
    const $form = $(this);


    $form.slideUp(400, function () {
      $content.find(".success-message").fadeIn(300);
    });
  });
}
