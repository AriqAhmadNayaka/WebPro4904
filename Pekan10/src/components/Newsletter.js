// Komponen ini buat nampilin form subscribe di bagian bawah halaman.
export function Newsletter() {
const $container = $('<div class="newsletter-section"></div>');

// Isi newsletter dibuat pakai jQuery supaya sesuai materi yang dipakai.
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

// Setelah kontennya jadi, bagian newsletter ditempel ke #app.
$container.append($content);
$("#app").append($container);

// Saat form dikirim, halaman tidak reload dan pesan sukses ditampilkan.
$content.find("form").on("submit", function (e) {
e.preventDefault();
const $form = $(this);

$form.slideUp(400, function() {
// Pesan sukses dimunculkan pelan-pelan biar tampilannya lebih halus.
$content.find(".success-message").fadeIn();
});
});
}
