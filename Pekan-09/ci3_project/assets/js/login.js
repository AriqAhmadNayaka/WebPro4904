    const slides = [
        {
            imageUrl: 'FOTO/bdg1.jpg', 
            title: "Jelajahi Warisan Bandung",
            description: "Temukan sejarah, seni, dan budaya yang kaya di Kota Kembang."
        },
        {
            imageUrl: 'FOTO/bdg2.jpg', 
            title: "Asia Afrika yang Legendaris",
            description: "Susuri jalan bersejarah dengan arsitektur kolonial yang ikonik."
        },
        {
            imageUrl: 'FOTO/bdg3.jpg', 
            title: "Pesona Jalan Braga",
            description: "Nikmati suasana klasik, kafe, dan bangunan art deco yang memukau."
        },
        {
            imageUrl: 'FOTO/bdg4.jpg', 
            title: "Kota Kembang di Malam Hari",
            description: "Saksikan gemerlap lampu dan kehidupan malam yang eksotis."
        }
    ];

    const slideshowElement = document.getElementById('slideshow-bg');
    const titleElement = document.getElementById('slide-title');
    const descriptionElement = document.getElementById('slide-description');
    const imageContent = document.querySelector('.image-content');

    let currentIndex = 0;
    const intervalTime = 2000;

    function changeSlide() {
        const activeSlide = slides[currentIndex];
        imageContent.style.opacity = 0; 

        setTimeout(() => {
            titleElement.textContent = activeSlide.title;
            descriptionElement.textContent = activeSlide.description;
            
            const tempImg = new Image();
            tempImg.onload = function() {
                slideshowElement.style.backgroundImage = `url('${activeSlide.imageUrl}')`;
                imageContent.style.opacity = 1;
            };
            tempImg.src = activeSlide.imageUrl;
        }, 500);

        currentIndex = (currentIndex + 1) % slides.length;
    }

    document.addEventListener('DOMContentLoaded', () => {
        changeSlide();
        setInterval(changeSlide, intervalTime);
    });

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

