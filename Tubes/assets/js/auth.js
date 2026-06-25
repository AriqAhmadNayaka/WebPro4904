(function () {
    const AUTH_URL = "api/auth.php";

    function toast(message) {
        let node = document.querySelector(".wb-toast");
        if (!node) {
            node = document.createElement("div");
            node.className = "wb-toast";
            document.body.appendChild(node);
        }
        node.textContent = message;
        node.classList.add("show");
        window.clearTimeout(node._timer);
        node._timer = window.setTimeout(() => node.classList.remove("show"), 2800);
    }

    async function requestAuth(action, data) {
        const response = await fetch(`${AUTH_URL}?action=${action}`, {
            method: data ? "POST" : "GET",
            headers: { "Content-Type": "application/json" },
            body: data ? JSON.stringify(data) : undefined
        });
        const payload = await response.json();
        if (!response.ok || !payload.success) {
            throw new Error(payload.message || "Proses auth gagal.");
        }
        return payload;
    }

    function wireRegister() {
        const form = document.querySelector("[data-register-form]");
        if (!form) return;

        form.addEventListener("submit", async event => {
            event.preventDefault();
            const formData = new FormData(form);
            const minat = formData.getAll("minat");

            try {
                await requestAuth("register", {
                    nama_lengkap: formData.get("nama_lengkap"),
                    kota: formData.get("kota"),
                    email: formData.get("email"),
                    password: formData.get("password"),
                    confirm_password: formData.get("confirm_password"),
                    kebutuhan_utama: formData.get("kebutuhan_utama"),
                    minat
                });
                toast("Registrasi berhasil");
                window.location.href = "home.php";
            } catch (error) {
                toast(error.message);
            }
        });
    }

    function wireLogin() {
        const form = document.querySelector("[data-login-form]");
        if (!form) return;

        form.addEventListener("submit", async event => {
            event.preventDefault();
            const formData = new FormData(form);

            try {
                await requestAuth("login", {
                    email: formData.get("email"),
                    password: formData.get("password"),
                    remember: formData.get("remember") === "on"
                });
                toast("Login berhasil");
                window.location.href = "home.php";
            } catch (error) {
                toast(error.message);
            }
        });
    }

    async function loadProfile() {
        const form = document.querySelector("[data-profile-form]");
        const reviewList = document.querySelector("[data-my-review-list]");
        if (!form && !reviewList) return;

        try {
            const payload = await requestAuth("me");
            if (!payload.authenticated) {
                toast("Silakan login dulu");
                window.location.href = "login.php";
                return;
            }

            if (form) {
                form.elements.nama_lengkap.value = payload.user.nama_lengkap || "";
                form.elements.email.value = payload.user.email || "";
                form.elements.kota.value = payload.user.kota || "";
            }

            if (reviewList) loadMyReviews(reviewList);
        } catch (error) {
            toast(error.message);
        }
    }

    async function loadMyReviews(reviewList) {
        try {
            const response = await fetch("api/reviews.php?action=my_reviews");
            const payload = await response.json();
            const reviews = Array.isArray(payload.data) ? payload.data : [];

            if (!reviews.length) {
                reviewList.innerHTML = `
                    <p class="subtitle">Belum ada review dari akun ini.</p>
                    <a href="peta.php#reviewLokasi" style="display:inline-flex;margin-top:12px;padding:10px 14px;border-radius:10px;background:#4A8645;color:white;text-decoration:none;font-weight:700;font-size:13px;">
                        <i class="fas fa-map-pin" style="margin-right:8px;"></i> Tulis Review di Peta
                    </a>
                `;
                return;
            }

            reviewList.innerHTML = reviews.map(review => `
                <div class="review-item">
                    <div class="rating"><i class="fas fa-star"></i> ${review.rating}/5</div>
                    <h3>${review.place}</h3>
                    <p>${review.comment}</p>
                </div>
            `).join("") + `
                <a href="peta.php#reviewLokasi" style="display:inline-flex;margin-top:14px;padding:10px 14px;border-radius:10px;background:#4A8645;color:white;text-decoration:none;font-weight:700;font-size:13px;">
                    <i class="fas fa-edit" style="margin-right:8px;"></i> Kelola Review di Peta
                </a>
            `;
        } catch (error) {
            reviewList.innerHTML = `<p class="subtitle">Review belum bisa dimuat.</p>`;
        }
    }

    function wireProfileUpdate() {
        const form = document.querySelector("[data-profile-form]");
        if (!form) return;

        form.addEventListener("submit", async event => {
            event.preventDefault();
            const formData = new FormData(form);

            try {
                await requestAuth("update_profile", {
                    nama_lengkap: formData.get("nama_lengkap"),
                    email: formData.get("email"),
                    kota: formData.get("kota")
                });
                toast("Profil berhasil diperbarui");
            } catch (error) {
                toast(error.message);
            }
        });
    }

    function wireLogout() {
        const button = document.querySelector("[data-logout-button]");
        if (!button) return;

        button.addEventListener("click", async event => {
            event.preventDefault();
            try {
                await requestAuth("logout", {});
                window.location.href = "login.php";
            } catch (error) {
                toast(error.message);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        wireRegister();
        wireLogin();
        loadProfile();
        wireProfileUpdate();
        wireLogout();
    });
})();

