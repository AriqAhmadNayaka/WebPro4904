(function () {
    const API_URL = "api/reviews.php";
    let reviewsCache = [];
    let places = [];
    let currentUser = null;

    const categoryLabels = {
        sejarah: "Warisan",
        publik: "Ruang Publik",
        ruangpublik: "Ruang Publik",
        event: "Event",
        hiddengem: "Hidden Gem",
        spotfoto: "Spot Foto",
        kafe: "Kafe",
        kuliner: "Kuliner"
    };

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

    async function requestApi(action, options = {}) {
        const response = await fetch(`${API_URL}?action=${action}`, {
            headers: {
                "Content-Type": "application/json"
            },
            ...options
        });
        const payload = await response.json();

        if (!response.ok || !payload.success) {
            throw new Error(payload.message || "Request review gagal.");
        }

        return payload;
    }

    async function prefillReviewerName() {
        const form = document.querySelector("[data-review-form]");

        try {
            const response = await fetch("api/auth.php?action=me");
            const payload = await response.json();
            currentUser = payload.success && payload.authenticated ? payload.user : null;
            if (form && currentUser && !form.elements.name.value) {
                form.elements.name.value = payload.user.nama_lengkap || payload.user.username || "";
            }
        } catch (error) {
            currentUser = null;
        }
    }

    function canManageReview(review) {
        if (!currentUser) return false;
        if (currentUser.role === "admin") return true;
        return Number(review.userId || 0) === Number(currentUser.id);
    }

    function syncPlacesFromMap() {
        const source = Array.isArray(window.webandooMapLocations) ? window.webandooMapLocations : [];
        const seen = new Set();

        places = source
            .filter(place => place && place.name && !seen.has(place.name) && seen.add(place.name))
            .map(place => ({
                name: place.name,
                category: categoryLabels[place.category] || place.category || "Lokasi",
                image: place.image || "bdg1.jpg"
            }));
    }

    function getAverage(placeName) {
        const reviews = reviewsCache.filter(review => review.place === placeName);
        if (!reviews.length) return { count: 0, average: 0 };

        const total = reviews.reduce((sum, review) => sum + Number(review.rating || 0), 0);
        return { count: reviews.length, average: total / reviews.length };
    }

    function setRatingLabel(value) {
        const label = document.querySelector("[data-rating-label]");
        if (label) label.textContent = `${value}/5`;
    }

    function resetForm() {
        const form = document.querySelector("[data-review-form]");
        if (!form) return;

        form.reset();
        form.elements.reviewId.value = "";
        form.elements.rating.value = "5";
        setRatingLabel("5");

        const submit = form.querySelector("[data-submit-label]");
        if (submit) submit.textContent = "Simpan Review";

        const cancel = form.querySelector("[data-cancel-edit]");
        if (cancel) cancel.hidden = true;
    }

    function selectReviewPlace(placeName) {
        const form = document.querySelector("[data-review-form]");
        if (!form) return;

        form.elements.place.value = placeName;
        form.scrollIntoView({ behavior: "smooth", block: "center" });

        const nameInput = form.elements.name;
        if (nameInput && !nameInput.value) {
            window.setTimeout(() => nameInput.focus(), 350);
        }
    }

    function renderPlaceOptions() {
        const select = document.querySelector("[data-place-select]");
        const filter = document.querySelector("[data-place-filter]");
        if (!select || !filter) return;

        const optionHtml = places
            .map(place => `<option value="${place.name}">${place.name} - ${place.category}</option>`)
            .join("");

        select.innerHTML = `<option value="">Pilih tempat</option>${optionHtml}`;
        filter.innerHTML = `<option value="all">Semua tempat</option>${optionHtml}`;
    }

    function renderReviewList() {
        const list = document.querySelector("[data-review-list]");
        const empty = document.querySelector("[data-empty-state]");
        const search = (document.querySelector("[data-review-search]")?.value || "").toLowerCase();
        const filter = document.querySelector("[data-place-filter]")?.value || "all";
        if (!list || !empty) return;

        const reviews = reviewsCache.filter(review => {
            const matchesPlace = filter === "all" || review.place === filter;
            const haystack = `${review.place} ${review.name} ${review.comment}`.toLowerCase();
            return matchesPlace && haystack.includes(search);
        });

        empty.hidden = reviews.length > 0;
        list.innerHTML = reviews.map(review => {
            const actions = canManageReview(review)
                ? `
                    <div>
                        <button type="button" data-edit-review="${review.id}"><i class="fas fa-edit"></i> Edit</button>
                        <button type="button" data-delete-review="${review.id}"><i class="fas fa-trash"></i> Hapus</button>
                    </div>
                `
                : `<span class="review-owner-note"><i class="fas fa-lock"></i> Review pengguna lain</span>`;

            return `
            <article class="review-item-card">
                <div class="review-item-head">
                    <div>
                        <span>${review.place}</span>
                        <h3>${review.name}</h3>
                    </div>
                    <strong><i class="fas fa-star"></i> ${review.rating}/5</strong>
                </div>
                <p>${review.comment}</p>
                <div class="review-item-foot">
                    <span><i class="fas fa-calendar"></i> ${review.visitDate || "Tanggal tidak diisi"}</span>
                    ${actions}
                </div>
            </article>
        `;
        }).join("");

        list.querySelectorAll("[data-edit-review]").forEach(button => {
            button.addEventListener("click", () => editReview(button.dataset.editReview));
        });
        list.querySelectorAll("[data-delete-review]").forEach(button => {
            button.addEventListener("click", () => deleteReview(button.dataset.deleteReview));
        });
    }

    function renderStats() {
        const totalNode = document.querySelector("[data-total-reviews]");
        const avgNode = document.querySelector("[data-average-rating]");
        const bestNode = document.querySelector("[data-best-place]");

        if (totalNode) totalNode.textContent = reviewsCache.length;

        if (avgNode) {
            const total = reviewsCache.reduce((sum, review) => sum + Number(review.rating || 0), 0);
            avgNode.textContent = reviewsCache.length ? (total / reviewsCache.length).toFixed(1) : "-";
        }

        if (bestNode) {
            const sorted = places
                .map(place => ({ ...place, ...getAverage(place.name) }))
                .filter(place => place.count > 0)
                .sort((a, b) => b.average - a.average || b.count - a.count);
            bestNode.textContent = sorted[0]?.name || "-";
        }
    }

    function renderAll() {
        renderReviewList();
        renderStats();
    }

    async function loadReviews() {
        const list = document.querySelector("[data-review-list]");
        if (list) {
            list.innerHTML = `<div class="review-empty"><p>Memuat review dari database...</p></div>`;
        }

        try {
            const payload = await requestApi("list");
            reviewsCache = Array.isArray(payload.data) ? payload.data : [];
            renderAll();
        } catch (error) {
            reviewsCache = [];
            renderAll();
            toast(error.message);
        }
    }

    function editReview(id) {
        const review = reviewsCache.find(item => String(item.id) === String(id));
        const form = document.querySelector("[data-review-form]");
        if (!review || !form) return;
        if (!canManageReview(review)) {
            toast("Kamu hanya bisa mengubah review milik akunmu sendiri.");
            return;
        }

        form.elements.reviewId.value = review.id;
        form.elements.place.value = review.place;
        form.elements.name.value = review.name;
        form.elements.rating.value = review.rating;
        form.elements.visitDate.value = review.visitDate || "";
        form.elements.comment.value = review.comment;
        setRatingLabel(review.rating);

        const submit = form.querySelector("[data-submit-label]");
        if (submit) submit.textContent = "Update Review";

        const cancel = form.querySelector("[data-cancel-edit]");
        if (cancel) cancel.hidden = false;

        form.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    async function deleteReview(id) {
        const review = reviewsCache.find(item => String(item.id) === String(id));
        if (!review) return;
        if (!canManageReview(review)) {
            toast("Kamu hanya bisa menghapus review milik akunmu sendiri.");
            return;
        }

        const confirmed = window.confirm(`Hapus review untuk ${review.place}?`);
        if (!confirmed) return;

        try {
            await requestApi("delete", {
                method: "POST",
                body: JSON.stringify({ id })
            });
            resetForm();
            await loadReviews();
            toast("Review berhasil dihapus dari database");
        } catch (error) {
            toast(error.message);
        }
    }

    function wireForm() {
        const form = document.querySelector("[data-review-form]");
        if (!form) return;

        const ratingInput = form.elements.rating;
        ratingInput.addEventListener("input", () => setRatingLabel(ratingInput.value));

        form.addEventListener("submit", async event => {
            event.preventDefault();
            const data = new FormData(form);
            const id = data.get("reviewId");
            const review = {
                id,
                place: data.get("place"),
                name: data.get("name").trim(),
                rating: Number(data.get("rating")),
                visitDate: data.get("visitDate"),
                comment: data.get("comment").trim()
            };

            if (!review.place || !review.name || !review.comment) {
                toast("Lengkapi tempat, nama, dan komentar dulu");
                return;
            }

            try {
                await requestApi(id ? "update" : "create", {
                    method: "POST",
                    body: JSON.stringify(review)
                });
                resetForm();
                await loadReviews();
                toast(id ? "Review berhasil diperbarui di database" : "Review berhasil masuk database");
            } catch (error) {
                toast(error.message);
            }
        });

        form.querySelector("[data-cancel-edit]")?.addEventListener("click", resetForm);
    }

    function wireFilters() {
        document.querySelector("[data-review-search]")?.addEventListener("input", renderReviewList);
        document.querySelector("[data-place-filter]")?.addEventListener("change", renderReviewList);
    }

    document.addEventListener("DOMContentLoaded", () => {
        syncPlacesFromMap();
        renderPlaceOptions();
        wireForm();
        wireFilters();
        prefillReviewerName().finally(loadReviews);
    });

    window.webandooSelectReviewPlace = selectReviewPlace;
    window.webandooGetReviewSummary = getAverage;
})();

