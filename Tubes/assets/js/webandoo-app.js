(function () {
    const STORAGE_KEY = "webandooWishlist";
    const SETTINGS_KEY = "webandooSettings";

    function getWishlist() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        } catch (error) {
            return [];
        }
    }

    function setWishlist(items) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    }

    function getSettings() {
        try {
            return JSON.parse(localStorage.getItem(SETTINGS_KEY)) || {};
        } catch (error) {
            return {};
        }
    }

    function setSettings(settings) {
        localStorage.setItem(SETTINGS_KEY, JSON.stringify(settings));
    }

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
        node._timer = window.setTimeout(() => node.classList.remove("show"), 2600);
    }

    function toggleSetting(key, className, messageOn, messageOff) {
        const settings = getSettings();
        settings[key] = !settings[key];
        setSettings(settings);
        document.documentElement.classList.toggle(className, settings[key]);
        toast(settings[key] ? messageOn : messageOff);
    }

    function applySettings() {
        const settings = getSettings();
        document.documentElement.classList.toggle("wb-large-text", !!settings.largeText);
        document.documentElement.classList.toggle("wb-high-contrast", !!settings.highContrast);
    }

    function enhanceCards() {
        const selectors = [".card", ".event-card", ".heritage-card", ".mini-feature-card", ".info-card", ".learning-card"];
        document.querySelectorAll(selectors.join(",")).forEach((card, index) => {
            if (card.classList.contains("wb-card-enhanced")) return;
            const titleNode = card.querySelector("h1,h2,h3,h4,h5,strong");
            const title = titleNode ? titleNode.textContent.trim() : `Rekomendasi ${index + 1}`;
            card.classList.add("wb-card-enhanced");
            const button = document.createElement("button");
            button.className = "wb-save-btn";
            button.type = "button";
            button.innerHTML = `<i class="fas fa-heart"></i>`;
            button.setAttribute("aria-label", `Simpan ${title}`);

            const wishlist = getWishlist();
            if (wishlist.includes(title)) button.classList.add("saved");

            button.addEventListener("click", (event) => {
                event.preventDefault();
                event.stopPropagation();
                const items = getWishlist();
                const exists = items.includes(title);
                const next = exists ? items.filter(item => item !== title) : [...items, title];
                setWishlist(next);
                button.classList.toggle("saved", !exists);
                toast(exists ? `${title} dihapus dari wishlist` : `${title} disimpan ke wishlist`);
            });
            card.appendChild(button);
        });
    }

    function wireAccessibilitySettings() {
        document.querySelectorAll(".setting-row").forEach(row => {
            const text = row.textContent.toLowerCase();
            const toggle = row.querySelector(".toggle");
            if (!toggle) return;
            row.style.cursor = "pointer";
            row.addEventListener("click", () => {
                if (text.includes("teks besar")) {
                    toggleSetting("largeText", "wb-large-text", "Mode teks besar aktif", "Mode teks besar nonaktif");
                } else if (text.includes("kontras")) {
                    toggleSetting("highContrast", "wb-high-contrast", "Kontras tinggi aktif", "Kontras tinggi nonaktif");
                } else {
                    toast("Preferensi disimpan");
                }
            });
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        applySettings();
        const file = decodeURIComponent(window.location.pathname.split("/").pop() || "");
        if (document.body.classList.contains("login-page") || file === "keluar.php") return;
        enhanceCards();
        wireAccessibilitySettings();
    });
})();

