document.addEventListener("DOMContentLoaded", () => {
    const slides = Array.from(document.querySelectorAll(".promo-slide"));
    const dots = Array.from(document.querySelectorAll(".promo-dot"));
    const prev = document.querySelector("[data-promo-prev]");
    const next = document.querySelector("[data-promo-next]");
    if (!slides.length) return;

    let current = 0;
    let timer;

    function showPromo(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, slideIndex) => {
            slide.classList.toggle("active", slideIndex === current);
        });
        dots.forEach((dot, dotIndex) => {
            dot.classList.toggle("active", dotIndex === current);
        });
    }

    function startPromoAutoPlay() {
        window.clearInterval(timer);
        timer = window.setInterval(() => showPromo(current + 1), 4500);
    }

    prev?.addEventListener("click", () => {
        showPromo(current - 1);
        startPromoAutoPlay();
    });

    next?.addEventListener("click", () => {
        showPromo(current + 1);
        startPromoAutoPlay();
    });

    dots.forEach(dot => {
        dot.addEventListener("click", () => {
            showPromo(Number(dot.dataset.promoDot));
            startPromoAutoPlay();
        });
    });

    startPromoAutoPlay();
});

document.addEventListener("DOMContentLoaded", () => {
    const mapNode = document.getElementById("homeMiniMap");
    if (!mapNode || typeof L === "undefined") return;

    const locations = [
        {
            name: "Gedung Merdeka",
            desc: "Jl. Asia Afrika - bangunan konferensi bersejarah",
            lat: -6.9216,
            lng: 107.6078,
            icon: "fa-landmark"
        },
        {
            name: "Museum Pos Indonesia",
            desc: "Jl. Cilaki - museum komunikasi dan filateli",
            lat: -6.9015,
            lng: 107.6187,
            icon: "fa-envelope"
        },
        {
            name: "Gedung Sate",
            desc: "Ikon pemerintahan bersejarah Kota Bandung",
            lat: -6.9025,
            lng: 107.6188,
            icon: "fa-building-columns"
        }
    ];

    const miniMap = L.map("homeMiniMap", {
        zoomControl: true,
        scrollWheelZoom: false
    }).setView([-6.9115, 107.6135], 14);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap"
    }).addTo(miniMap);

    const markers = locations.map(location => {
        const icon = L.divIcon({
            className: "",
            html: `<div class="home-map-marker"><i class="fas ${location.icon}"></i></div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -30]
        });

        return L.marker([location.lat, location.lng], { icon })
            .addTo(miniMap)
            .bindPopup(`<strong>${location.name}</strong><br>${location.desc}`);
    });

    document.querySelectorAll(".nearby-item[data-map-lat]").forEach((item, index) => {
        item.addEventListener("click", () => {
            document.querySelectorAll(".nearby-item").forEach(node => node.classList.remove("active"));
            item.classList.add("active");
            const lat = Number(item.dataset.mapLat);
            const lng = Number(item.dataset.mapLng);
            const zoom = Number(item.dataset.mapZoom || 16);
            miniMap.flyTo([lat, lng], zoom, { duration: .8 });
            markers[index]?.openPopup();
        });
    });

    window.setTimeout(() => miniMap.invalidateSize(), 250);
});

