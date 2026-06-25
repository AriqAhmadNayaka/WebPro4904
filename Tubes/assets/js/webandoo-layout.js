(function () {
    const appPages = [
        "home.php",
        "warisan.php",
        "peta.php",
        "kuliner.php",
        "kafe.php",
        "event.php",
        "eduction.php",
        "profil.php",
        "pengaturan.php",
        "keluar.php"
    ];

    const navItems = [
        { href: "home.php", label: "Dashboard", icon: "fa-chart-line" },
        { href: "warisan.php", label: "Warisan", icon: "fa-landmark" },
        { href: "peta.php", label: "Peta", icon: "fa-map-pin" },
        { href: "kuliner.php", label: "Kuliner", icon: "fa-utensils" },
        { href: "kafe.php", label: "Kafe", icon: "fa-coffee" },
        { href: "event.php", label: "Event", icon: "fa-calendar-day" },
        { href: "eduction.php", label: "Belajar", icon: "fa-book" }
    ];

    function currentFile() {
        const file = decodeURIComponent(window.location.pathname.split("/").pop() || "home.php");
        return file || "home.php";
    }

    function isAppPage() {
        return appPages.includes(currentFile());
    }

    function pageKey() {
        return currentFile();
    }

    function removeOldLayout() {
        document.querySelectorAll("nav.navbar, footer").forEach(node => node.remove());
    }

    function buildNavbar() {
        const active = currentFile();
        const nav = document.createElement("nav");
        nav.className = "wb-navbar";
        nav.innerHTML = `
            <a href="home.php" class="wb-logo">WeBandoo+</a>
            <ul class="wb-nav-links">
                ${navItems.map(item => `
                    <li>
                        <a href="${item.href}" class="wb-nav-link ${active === item.href ? "active" : ""}">
                            <i class="fas ${item.icon}"></i> ${item.label}
                        </a>
                    </li>
                `).join("")}
            </ul>
            <div class="wb-navbar-right">
                <span class="wb-role-badge" data-role-badge>user</span>
                <a class="wb-avatar" href="profil.php" title="Profil">
                    <img src="assets/images/Raihan.jpg" alt="Avatar">
                </a>
                <a href="pengaturan.php" class="wb-icon-action" title="Pengaturan"><i class="fas fa-cog"></i></a>
                <a href="keluar.php" class="wb-logout-action"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        `;
        document.body.prepend(nav);
    }

    async function hydrateUserRole() {
        try {
            const response = await fetch("api/auth.php?action=me");
            const payload = await response.json();
            const role = payload?.user?.role || "guest";
            window.webandooCurrentUser = payload?.user || null;
            window.webandooIsAdmin = role === "admin";
            document.body.dataset.role = role;

            const badge = document.querySelector("[data-role-badge]");
            if (badge) {
                badge.textContent = role === "admin" ? "admin" : "user";
                badge.classList.toggle("is-admin", role === "admin");
            }

            document.querySelectorAll("[data-admin-only], #adminTrigger").forEach(node => {
                node.hidden = role !== "admin";
            });
            if (role === "admin") buildAdminContentButton();
        } catch (error) {
            window.webandooCurrentUser = null;
            window.webandooIsAdmin = false;
        }
    }

    async function requestContent(options = {}) {
        const response = await fetch(`api/content.php?page=${encodeURIComponent(pageKey())}`, {
            headers: { "Content-Type": "application/json" },
            ...options
        });
        const payload = await response.json();
        if (!response.ok || !payload.success) throw new Error(payload.message || "Konten gagal diproses.");
        return payload;
    }

    function renderContentBlocks(items) {
        if (!Array.isArray(items) || !items.length) return;

        const section = document.createElement("section");
        section.className = "wb-admin-content";
        section.innerHTML = `
            <div class="wb-admin-content-head">
                <h2>Info Tambahan</h2>
                <span>Dikelola oleh admin</span>
            </div>
            <div class="wb-admin-content-grid">
                ${items.map(item => `
                    <article class="wb-admin-content-card">
                        ${item.image ? `<img src="${item.image}" alt="${item.title}">` : ""}
                        <div>
                            <small>${item.page === "all" ? "Semua Halaman" : item.page}</small>
                            <h3>${item.title}</h3>
                            <p>${item.body}</p>
                            ${item.linkUrl ? `<a href="${item.linkUrl}">${item.linkLabel || "Buka"}</a>` : ""}
                            ${window.webandooIsAdmin ? `<button type="button" data-delete-content="${item.id}"><i class="fas fa-trash"></i> Hapus</button>` : ""}
                        </div>
                    </article>
                `).join("")}
            </div>
        `;

        const target = document.querySelector("main.page, .main-content, .figma-frame") || document.body;
        target.appendChild(section);
        section.querySelectorAll("[data-delete-content]").forEach(button => {
            button.addEventListener("click", () => deleteContentBlock(button.dataset.deleteContent));
        });
    }

    async function loadContentBlocks() {
        try {
            const payload = await requestContent();
            renderContentBlocks(payload.data);
        } catch (error) {
            console.warn(error.message);
        }
    }

    function buildAdminContentButton() {
        if (document.querySelector("[data-open-content-admin]")) return;
        const button = document.createElement("button");
        button.type = "button";
        button.className = "wb-admin-fab";
        button.dataset.openContentAdmin = "true";
        button.innerHTML = `<i class="fas fa-plus"></i> Konten`;
        button.addEventListener("click", openContentAdminModal);
        document.body.appendChild(button);
    }

    function openContentAdminModal() {
        let modal = document.querySelector(".wb-admin-modal");
        if (!modal) {
            modal = document.createElement("div");
            modal.className = "wb-admin-modal";
            modal.innerHTML = `
                <form class="wb-admin-modal-box" data-content-form>
                    <h2>Tambah Konten Halaman</h2>
                    <select name="page" required>
                        <option value="${pageKey()}">Halaman ini (${pageKey()})</option>
                        <option value="all">Semua halaman</option>
                    </select>
                    <input name="title" placeholder="Judul kartu" required>
                    <textarea name="body" rows="4" placeholder="Isi konten" required></textarea>
                    <input name="image" placeholder="Nama file gambar atau URL (opsional)">
                    <input name="linkUrl" placeholder="Link tujuan (opsional)">
                    <input name="linkLabel" placeholder="Label link (opsional)">
                    <div>
                        <button type="button" data-close-content-admin>Batal</button>
                        <button type="submit">Simpan</button>
                    </div>
                </form>
            `;
            modal.querySelector("[data-close-content-admin]").addEventListener("click", () => modal.classList.remove("show"));
            modal.querySelector("[data-content-form]").addEventListener("submit", submitContentBlock);
            document.body.appendChild(modal);
        }
        modal.classList.add("show");
    }

    async function submitContentBlock(event) {
        event.preventDefault();
        const form = event.currentTarget;
        const data = new FormData(form);
        try {
            await requestContent({
                method: "POST",
                body: JSON.stringify({
                    page: data.get("page"),
                    title: data.get("title"),
                    body: data.get("body"),
                    image: data.get("image"),
                    linkUrl: data.get("linkUrl"),
                    linkLabel: data.get("linkLabel")
                })
            });
            window.location.reload();
        } catch (error) {
            alert(error.message);
        }
    }

    async function deleteContentBlock(id) {
        if (!window.confirm("Hapus konten ini?")) return;
        try {
            await requestContent({
                method: "DELETE",
                body: JSON.stringify({ id })
            });
            window.location.reload();
        } catch (error) {
            alert(error.message);
        }
    }

    function buildFooter() {
        const adminMapLink = currentFile() === "peta.php"
            ? `<li><a href="javascript:void(0)" id="adminTrigger" data-admin-only onclick="toggleAdminMode()"><i class="fas fa-user-shield"></i> Mode Admin Peta</a></li>`
            : "";
        const footer = document.createElement("footer");
        footer.className = "wb-footer";
        footer.innerHTML = `
            <div class="wb-footer-container">
                <div>
                    <h3>WeBandoo+</h3>
                    <p>Platform informasi wisata Bandung terintegrasi untuk peta, kuliner, kafe, budaya, event, rute, review, wishlist, dan estimasi biaya.</p>
                </div>
                <div>
                    <h4>Fitur Utama</h4>
                    <ul>
                        <li><a href="peta.php">Peta Interaktif Bandung</a></li>
                        <li><a href="kuliner.php">Informasi Kuliner</a></li>
                        <li><a href="kafe.php">Kafe & Tempat Nongkrong</a></li>
                        <li><a href="event.php">Event Bandung</a></li>
                        ${adminMapLink}
                    </ul>
                </div>
                <div>
                    <h4>Kebutuhan Pengguna</h4>
                    <ul>
                        <li>Harga dan estimasi biaya sebelum datang</li>
                        <li>Rute, jarak, jam buka, dan fasilitas</li>
                        <li>Hidden gem dan spot foto</li>
                        <li>Desain mudah dipahami semua umur</li>
                    </ul>
                </div>
                <div>
                    <h4>Kontak</h4>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Bandung, Jawa Barat</li>
                        <li><i class="fas fa-envelope"></i> info@webandoo.id</li>
                        <li><i class="fas fa-phone"></i> (022) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="wb-footer-bottom">2026 WeBandoo+ Bandung. Semua informasi dirancang untuk eksplorasi kota yang praktis.</div>
        `;
        document.body.appendChild(footer);
    }

    document.addEventListener("DOMContentLoaded", () => {
        if (document.body.classList.contains("login-page") || !isAppPage()) return;
        document.body.classList.add("has-shared-layout");
        removeOldLayout();
        buildNavbar();
        buildFooter();
        hydrateUserRole();
        loadContentBlocks();
    });
})();

