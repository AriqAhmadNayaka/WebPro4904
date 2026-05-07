// Komponen Newsletter: membuat form langganan dan pesan sukses menggunakan jQuery.
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

    // Menambahkan section newsletter ke halaman utama.
    $container.append($content);
    $("#app").append($container);

    // Saat form disubmit, cegah reload lalu tampilkan pesan sukses dengan animasi.
    $content.find("form").on("submit", function (e) {
        e.preventDefault();
        const $form = $(this);

        $form.slideUp(400, function () {
            $content.find(".success-message").fadeIn();
        });
    });
}
