(function () {
    const mounts = document.querySelectorAll("[data-split-bill]");
    if (!mounts.length) return;

    function formatRupiah(value) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            maximumFractionDigits: 0
        }).format(Number.isFinite(value) ? value : 0);
    }

    function parseAmount(value) {
        return Number(String(value || "0").replace(/[^\d]/g, "")) || 0;
    }

    function escapeHtml(value) {
        return String(value || "").replace(/[&<>"']/g, (char) => ({
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            "\"": "&quot;",
            "'": "&#39;"
        }[char]));
    }

    function createItemRow(item = {}, index = 0) {
        const row = document.createElement("div");
        row.className = "split-item-row";
        row.dataset.itemRow = "";
        row.innerHTML = `
            <label>
                Menu
                <input type="text" data-item-name value="${escapeHtml(item.name || "")}" placeholder="Contoh: Batagor" aria-label="Nama menu ${index + 1}">
            </label>
            <label>
                Harga
                <input type="text" data-item-amount inputmode="numeric" value="${escapeHtml(item.amount || "")}" placeholder="Contoh: 25000" aria-label="Harga menu ${index + 1}">
            </label>
            <button type="button" class="split-icon-button" data-remove-item aria-label="Hapus menu" title="Hapus menu">
                <i class="fas fa-trash"></i>
            </button>
        `;

        return row;
    }

    function createPersonRow(person = {}, index = 0) {
        const row = document.createElement("section");
        row.className = "split-person-row";
        row.dataset.personRow = "";
        row.innerHTML = `
            <div class="split-person-top">
                <label>
                    Nama
                    <input type="text" data-person-name value="${escapeHtml(person.name || `Orang ${index + 1}`)}" aria-label="Nama orang">
                </label>
                <div class="split-person-subtotal">
                    <small>Subtotal</small>
                    <strong data-person-subtotal>${formatRupiah(0)}</strong>
                </div>
                <button type="button" class="split-icon-button" data-remove-person aria-label="Hapus orang" title="Hapus orang">
                    <i class="fas fa-user-minus"></i>
                </button>
            </div>
            <div class="split-item-heading">
                <span>Menu</span>
                <span>Harga</span>
            </div>
            <div class="split-item-list" data-item-list></div>
            <div class="split-person-foot">
                <button type="button" class="split-add-menu-button" data-add-item>
                    <i class="fas fa-plus"></i>
                    Tambah menu
                </button>
            </div>
        `;

        const items = Array.isArray(person.items) && person.items.length ? person.items : [{ name: "", amount: "" }];
        const itemList = row.querySelector("[data-item-list]");
        items.forEach((item, itemIndex) => {
            itemList.appendChild(createItemRow(item, itemIndex));
        });

        return row;
    }

    function getPeople(mount) {
        return Array.from(mount.querySelectorAll("[data-person-row]")).map((row, index) => {
            const nameInput = row.querySelector("[data-person-name]");
            const items = Array.from(row.querySelectorAll("[data-item-row]")).map((itemRow) => {
                const itemName = itemRow.querySelector("[data-item-name]");
                const itemAmount = itemRow.querySelector("[data-item-amount]");

                return {
                    name: (itemName.value || "").trim(),
                    amount: parseAmount(itemAmount.value)
                };
            });
            const amount = items.reduce((sum, item) => sum + item.amount, 0);

            return {
                index,
                row,
                name: (nameInput.value || `Orang ${index + 1}`).trim(),
                items,
                amount
            };
        });
    }

    function addPerson(mount, person) {
        const list = mount.querySelector("[data-people-list]");
        list.appendChild(createPersonRow(person, list.children.length));
        updateRemoveButtons(mount);
        calculate(mount);
    }

    function addItem(personRow, item) {
        const list = personRow.querySelector("[data-item-list]");
        list.appendChild(createItemRow(item, list.children.length));
    }

    function updateRemoveButtons(mount) {
        const people = mount.querySelectorAll("[data-person-row]");
        people.forEach((personRow) => {
            const personButton = personRow.querySelector("[data-remove-person]");
            const itemButtons = personRow.querySelectorAll("[data-remove-item]");

            personButton.disabled = people.length <= 1;
            itemButtons.forEach((button) => {
                button.disabled = itemButtons.length <= 1;
            });
        });
    }

    function render(mount) {
        const title = mount.dataset.title || "Split Bill";
        const hint = mount.dataset.hint || "Masukkan menu yang dibeli tiap orang agar tagihannya dihitung otomatis.";
        const defaultPeople = [
            {
                name: "Andi",
                items: [
                    { name: "Batagor", amount: "25000" },
                    { name: "Es jeruk", amount: "12000" }
                ]
            },
            {
                name: "Bima",
                items: [
                    { name: "Mie kocok", amount: "30000" }
                ]
            },
            {
                name: "Citra",
                items: [
                    { name: "Nasi timbel", amount: "38000" },
                    { name: "Teh manis", amount: "8000" }
                ]
            },
            {
                name: "Dini",
                items: [
                    { name: "Soto Bandung", amount: "35000" },
                    { name: "Dessert", amount: "15000" }
                ]
            }
        ];

        mount.innerHTML = `
            <section class="split-bill-card" aria-label="${title}">
                <div class="split-bill-head">
                    <div>
                        <span><i class="fas fa-receipt"></i> Kalkulator</span>
                        <h2>${title}</h2>
                        <p>${hint}</p>
                    </div>
                    <strong data-split-total>${formatRupiah(0)}</strong>
                </div>
                <form class="split-bill-form">
                    <label>
                        Service / pajak (%)
                        <input type="number" name="service" min="0" max="100" step="0.5" value="10">
                    </label>
                    <label>
                        Tip opsional
                        <input type="text" name="tip" inputmode="numeric" placeholder="Contoh: 10000" value="0">
                    </label>
                    <label>
                        Pembulatan per orang
                        <select name="rounding">
                            <option value="1">Tanpa pembulatan</option>
                            <option value="500">Ke Rp500</option>
                            <option value="1000" selected>Ke Rp1.000</option>
                            <option value="5000">Ke Rp5.000</option>
                        </select>
                    </label>
                </form>
                <div class="split-people-head">
                    <h3>Pesanan per orang</h3>
                    <button type="button" class="split-add-button" data-add-person>
                        <i class="fas fa-user-plus"></i>
                        Tambah orang
                    </button>
                </div>
                <div class="split-people-list" data-people-list></div>
                <div class="split-bill-result">
                    <article>
                        <small>Subtotal pesanan</small>
                        <strong data-order-total>${formatRupiah(0)}</strong>
                    </article>
                    <article>
                        <small>Total akhir</small>
                        <strong data-final-total>${formatRupiah(0)}</strong>
                    </article>
                    <article>
                        <small>Total ditagih</small>
                        <strong data-paid-total>${formatRupiah(0)}</strong>
                    </article>
                    <article>
                        <small>Sisa pembulatan</small>
                        <strong data-rounding-extra>${formatRupiah(0)}</strong>
                    </article>
                </div>
                <div class="split-person-result" data-person-result></div>
                <p class="split-bill-note" data-split-note></p>
            </section>
        `;

        const form = mount.querySelector(".split-bill-form");
        form.addEventListener("input", () => calculate(mount));
        form.addEventListener("change", () => calculate(mount));

        mount.querySelector("[data-add-person]").addEventListener("click", () => {
            addPerson(mount, { items: [{ name: "", amount: "" }] });
        });

        mount.querySelector("[data-people-list]").addEventListener("input", () => calculate(mount));
        mount.querySelector("[data-people-list]").addEventListener("click", (event) => {
            const addItemButton = event.target.closest("[data-add-item]");
            const removeItemButton = event.target.closest("[data-remove-item]");
            const removePersonButton = event.target.closest("[data-remove-person]");

            if (addItemButton) {
                addItem(addItemButton.closest("[data-person-row]"), { name: "", amount: "" });
                updateRemoveButtons(mount);
                calculate(mount);
                return;
            }

            if (removeItemButton) {
                const personRow = removeItemButton.closest("[data-person-row]");
                const itemRows = personRow.querySelectorAll("[data-item-row]");
                if (itemRows.length <= 1) return;

                removeItemButton.closest("[data-item-row]").remove();
                updateRemoveButtons(mount);
                calculate(mount);
                return;
            }

            if (removePersonButton) {
                const rows = mount.querySelectorAll("[data-person-row]");
                if (rows.length <= 1) return;

                removePersonButton.closest("[data-person-row]").remove();
                updateRemoveButtons(mount);
                calculate(mount);
            }
        });

        defaultPeople.forEach((person) => addPerson(mount, person));
        calculate(mount);
    }

    function formatItems(items) {
        const filledItems = items.filter((item) => item.name || item.amount > 0);
        if (!filledItems.length) return "Belum ada menu";

        return filledItems.map((item, index) => {
            const itemName = item.name || `Menu ${index + 1}`;
            return `${escapeHtml(itemName)} ${formatRupiah(item.amount)}`;
        }).join(", ");
    }

    function calculate(mount) {
        const form = mount.querySelector(".split-bill-form");
        const serviceRate = Math.max(0, Number(form.elements.service.value) || 0);
        const tip = parseAmount(form.elements.tip.value);
        const rounding = Math.max(1, Number(form.elements.rounding.value) || 1);
        const people = getPeople(mount);
        const personCount = Math.max(1, people.length);
        const orderTotal = people.reduce((sum, person) => sum + person.amount, 0);
        const service = orderTotal * serviceRate / 100;
        const tipPerPerson = tip / personCount;

        const details = people.map((person) => {
            const serviceShare = person.amount * serviceRate / 100;
            const rawTotal = person.amount + serviceShare + tipPerPerson;
            const roundedTotal = Math.ceil(rawTotal / rounding) * rounding;

            person.row.querySelector("[data-person-subtotal]").textContent = formatRupiah(person.amount);

            return {
                ...person,
                serviceShare,
                tipShare: tipPerPerson,
                rawTotal,
                roundedTotal
            };
        });

        const total = orderTotal + service + tip;
        const paidTotal = details.reduce((sum, person) => sum + person.roundedTotal, 0);
        const extra = Math.max(0, paidTotal - total);

        mount.querySelector("[data-split-total]").textContent = formatRupiah(orderTotal);
        mount.querySelector("[data-order-total]").textContent = formatRupiah(orderTotal);
        mount.querySelector("[data-final-total]").textContent = formatRupiah(total);
        mount.querySelector("[data-paid-total]").textContent = formatRupiah(paidTotal);
        mount.querySelector("[data-rounding-extra]").textContent = formatRupiah(extra);
        mount.querySelector("[data-person-result]").innerHTML = details.map((person) => `
            <article>
                <div>
                    <strong>${escapeHtml(person.name || `Orang ${person.index + 1}`)}</strong>
                    <small>${formatItems(person.items)}. Subtotal ${formatRupiah(person.amount)} + pajak/service ${formatRupiah(person.serviceShare)} + tip ${formatRupiah(person.tipShare)}</small>
                </div>
                <b>${formatRupiah(person.roundedTotal)}</b>
            </article>
        `).join("");
        mount.querySelector("[data-split-note]").textContent =
            `${personCount} orang ditagih dari menu masing-masing. Pajak/service ${serviceRate}% mengikuti subtotal tiap orang, tip ${formatRupiah(tip)} dibagi rata.`;
    }

    mounts.forEach(render);
})();
