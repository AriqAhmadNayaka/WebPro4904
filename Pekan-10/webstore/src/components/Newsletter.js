export function Newsletter() {
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

  $content.find("form").on("submit", function (e) {
    E.preventDefault();
    const $form = $(this);

    $form.slideUp(400, function () {
      $content.find(".success-message").fadeIn();
    });
  });
}