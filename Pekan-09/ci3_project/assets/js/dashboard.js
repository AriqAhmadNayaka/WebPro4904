function adjustMainContentMargin() {
    const navbar = document.querySelector('.navbar');
    const mainContent = document.querySelector('.main-content');

    if (navbar && mainContent) {
        const navbarHeight = navbar.getBoundingClientRect().height;
        mainContent.style.paddingTop = (navbarHeight + 10) + 'px';
    }
}

const locations = [
    {
        name: 'Gedung Sate',
        category: 'sejarah',
        lat: -6.9025,
        lng: 107.6188,
        icon: 'fa-landmark',
        color: '#1abc9c',
        desc: 'Ikon sejarah Jawa Barat.'
    },
    {
        name: 'Jalan Braga',
        category: 'sejarah',
        lat: -6.9211,
        lng: 107.6110,
        icon: 'fa-camera',
        color: '#e67e22',
        desc: 'Kawasan ikonik dengan arsitektur klasik.'
    }
];

let map;
let currentSlideIndex = 0;
let slideInterval;
let lastScrollY = window.scrollY;

function createCustomIcon(iconClass, color) {
    return L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="marker-pin" style="background:${color}"><i class="fas ${iconClass}"></i></div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 40]
    });
}

function renderDashboardMarkers(mapObj) {
    locations.forEach((loc) => {
        const marker = L.marker([loc.lat, loc.lng], {
            icon: createCustomIcon(loc.icon, loc.color)
        }).addTo(mapObj);

        marker.bindPopup(`<b>${loc.name}</b><br>${loc.desc}`);
        marker.on('click', () => {
            mapObj.flyTo([loc.lat, loc.lng], 16);
        });
    });
}

function focusToMap(lat, lng) {
    if (map) {
        map.flyTo([lat, lng], 16);
    }
}

function updateClock() {
    const now = new Date();
    const options = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    const timeElement = document.getElementById('current-time');

    if (timeElement) {
        timeElement.innerText = now.toLocaleTimeString('id-ID', options);
    }
}

function showSlide(index) {
    const slides = document.querySelectorAll('.hero-item');
    const dots = document.querySelectorAll('.dot');

    if (slides.length === 0) {
        return;
    }

    if (index >= slides.length) {
        currentSlideIndex = 0;
    } else if (index < 0) {
        currentSlideIndex = slides.length - 1;
    } else {
        currentSlideIndex = index;
    }

    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === currentSlideIndex);
    });

    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === currentSlideIndex);
    });
}

function startAutoPlay() {
    clearInterval(slideInterval);
    slideInterval = setInterval(() => {
        showSlide(currentSlideIndex + 1);
    }, 5000);
}

function resetAutoPlay() {
    startAutoPlay();
}

function animateNumbers() {
    const counters = document.querySelectorAll('.data[data-target]');

    counters.forEach((counter) => {
        const target = Number(counter.getAttribute('data-target'));

        if (!target) {
            return;
        }

        let current = 0;
        const increment = Math.ceil(target / 60);

        const update = () => {
            current += increment;
            if (current < target) {
                counter.innerText = current;
                requestAnimationFrame(update);
            } else {
                counter.innerText = target;
            }
        };

        update();
    });
}

function changeSlide(step) {
    showSlide(currentSlideIndex + step);
    resetAutoPlay();
}

function currentSlide(index) {
    showSlide(index);
    resetAutoPlay();
}

function addToCalendar() {
    const startDate = '20251220T100000';
    const endDate = '20251220T120000';
    const text = encodeURIComponent('Festival Angklung 2025');
    const details = encodeURIComponent('Festival Angklung 2025 di Kopi Mandja Progo.');
    const location = encodeURIComponent('Kopi Mandja Progo, Bandung');
    const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${text}&dates=${startDate}/${endDate}&details=${details}&location=${location}`;
    window.open(calendarUrl, '_blank');
}

window.addEventListener('load', adjustMainContentMargin);
window.addEventListener('resize', adjustMainContentMargin);

window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (!navbar) {
        return;
    }

    if (window.scrollY > lastScrollY) {
        navbar.classList.add('navbar--hidden');
    } else {
        navbar.classList.remove('navbar--hidden');
    }

    if (window.scrollY <= 10) {
        navbar.classList.remove('navbar--hidden');
    }

    lastScrollY = window.scrollY;
});

document.addEventListener('DOMContentLoaded', () => {
    const mapContainer = document.getElementById('map');
    if (mapContainer && typeof L !== 'undefined') {
        map = L.map('map').setView([-6.9175, 107.6191], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        renderDashboardMarkers(map);

        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    }

    showSlide(0);
    startAutoPlay();
    updateClock();
    setInterval(updateClock, 1000);
    animateNumbers();

    document.querySelectorAll('.js-slide-trigger').forEach((button) => {
        button.addEventListener('click', () => {
            changeSlide(parseInt(button.dataset.step, 10));
        });
    });

    document.querySelectorAll('.js-dot-trigger').forEach((dot) => {
        dot.addEventListener('click', () => {
            currentSlide(parseInt(dot.dataset.slideIndex, 10));
        });
    });

    document.querySelectorAll('.js-focus-map').forEach((card) => {
        const goToMap = () => focusToMap(parseFloat(card.dataset.lat), parseFloat(card.dataset.lng));
        card.addEventListener('click', goToMap);
        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                goToMap();
            }
        });
    });

    const saveCalendarButton = document.querySelector('.js-save-calendar');
    if (saveCalendarButton) {
        saveCalendarButton.addEventListener('click', addToCalendar);
    }
});
